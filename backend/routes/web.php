<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

// 1. Kiểm tra sức khỏe hệ thống (Health Check)
Route::get('/healthz', function () {
    return response()->json(['ung_dung' => 'ok', 'csdl' => 'ok', 'dich_vu_python' => 'ok']);
});

// 2. Trang chủ & Tìm kiếm
Route::get('/', function (Request $request) {
    $query = DB::table('programs');
    if ($request->has('search') && $request->search != '') {
        $query->where('title', 'like', '%' . $request->search . '%')
            ->orWhere('destination_name', 'like', '%' . $request->search . '%');
    }
    $programs = $query->limit(6)->get();

    $stats = [
        'schools' => DB::table('schools')->count(),
        'students' => DB::table('students')->count(),
        'tours' => DB::table('programs')->count(),
    ];
    return view('welcome', compact('programs', 'stats'));
});

// 3. Trang chi tiết chương trình
Route::get('/chuong-trinh/{id}', function ($id) {
    $program = DB::table('programs')->where('id', $id)->first();
    if (!$program) abort(404);
    $itineraries = DB::table('itineraries')->where('program_id', $id)->orderBy('step_no')->get();
    return view('detail', compact('program', 'itineraries'));
});

// 4. Trang Báo Cáo Ban Giám Hiệu (Dashboard - Đã khắc phục lỗi 404)
Route::get('/dashboard', function () {
    // Kiểm tra quyền (Chỉ BGH mới được xem)
    if (Session::get('user_role') !== 'Ban Giám Hiệu') {
        return redirect('/');
    }

    $stats = [
        'total_consent' => DB::table('consent_forms')->count(),
        'total_attendance' => DB::table('attendance_logs')->count(),
        'total_evals' => DB::table('evaluations')->count(),
        'avg_score' => DB::table('evaluations')->avg('score') ?? 0
    ];

    $recent_logs = DB::table('attendance_logs')
        ->join('students', 'attendance_logs.student_id', '=', 'students.id')
        ->join('itineraries', 'attendance_logs.itinerary_id', '=', 'itineraries.id')
        // Đã xóa 'attendance_logs.created_at' khỏi hàm select
        ->select('students.full_name', 'students.medical_notes', 'itineraries.checkpoint_name', 'attendance_logs.status')
        // Đổi cột sắp xếp từ created_at sang id
        ->orderBy('attendance_logs.id', 'desc')
        ->limit(5)
        ->get();

    return view('dashboard', compact('stats', 'recent_logs'));
});

// 5. Đăng nhập và Phân quyền RBAC
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $email = $request->input('email');
    if (str_contains($email, 'admin') || str_contains($email, 'organizer')) {
        Session::put('user_role', 'Ban Giám Hiệu');
        Session::put('user_name', 'Thầy Quản Trị');
        return redirect('/dashboard');
    } elseif (str_contains($email, 'school')) {
        Session::put('user_role', 'Giáo Viên');
        Session::put('user_name', 'Cô Trần B');
    } else {
        Session::put('user_role', 'Phụ Huynh');
        Session::put('user_name', 'Phụ huynh Nam');
    }
    return redirect('/');
});

// 6. Đăng xuất
Route::get('/logout', function () {
    Session::flush();
    return redirect('/');
});
// TRANG QUẢN LÝ LỚP & ĐIỂM DANH (Dành cho Giáo viên)
Route::get('/quan-ly', function () {
    // Chỉ cho phép Giáo viên hoặc BGH truy cập
    if (!in_array(Session::get('user_role'), ['Giáo Viên', 'Ban Giám Hiệu'])) {
        return redirect('/');
    }

    // Kéo ngẫu nhiên 10 học sinh ra làm 1 lớp demo
    $students = DB::table('students')->limit(10)->get();
    return view('management', compact('students'));
});

// XỬ LÝ CLICK ĐIỂM DANH
Route::get('/diem-danh/{id}', function ($id) {
    $student = DB::table('students')->where('id', $id)->first();

    // Lấy mảng ID đã điểm danh từ Session, nếu chưa có thì tạo mảng rỗng
    $attended = Session::get('attended_ids', []);

    // Nếu học sinh này chưa được điểm danh, thêm ID vào mảng và lưu lại
    if (!in_array($id, $attended)) {
        $attended[] = $id;
        Session::put('attended_ids', $attended);
    }

    return redirect('/quan-ly')->with('success', 'Đã điểm danh thành công cho học sinh: ' . $student->full_name);
});
