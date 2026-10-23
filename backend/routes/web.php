<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

Route::get('/healthz', function () {
    return response()->json(['ung_dung' => 'ok', 'csdl' => 'ok', 'dich_vu_python' => 'ok']);
});

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

Route::get('/chuong-trinh/{id}', function ($id) {
    $program = DB::table('programs')->where('id', $id)->first();
    if (!$program) abort(404);
    $itineraries = DB::table('itineraries')->where('program_id', $id)->orderBy('step_no')->get();
    return view('detail', compact('program', 'itineraries'));
});

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
        ->select('students.full_name', 'students.medical_notes', 'itineraries.checkpoint_name', 'attendance_logs.status')
        ->orderBy('attendance_logs.id', 'desc')
        ->limit(5)
        ->get();

    return view('dashboard', compact('stats', 'recent_logs'));
});

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
Route::get('/quan-ly', function () {
    if (!in_array(Session::get('user_role'), ['Giáo Viên', 'Ban Giám Hiệu'])) {
        return redirect('/');
    }
    $students = DB::table('students')->limit(10)->get();
    return view('management', compact('students'));
});

Route::get('/diem-danh/{id}', function ($id) {
    $student = DB::table('students')->where('id', $id)->first();
    $attended = Session::get('attended_ids', []);
    if (!in_array($id, $attended)) {
        $attended[] = $id;
        Session::put('attended_ids', $attended);
    }

    return redirect('/quan-ly')->with('success', 'Đã điểm danh thành công cho học sinh: ' . $student->full_name);
});
