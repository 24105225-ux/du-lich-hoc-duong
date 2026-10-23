<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Quản lý lớp & Điểm danh - ĐT-17</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans flex flex-col min-h-screen">
    <main class="flex-grow p-8">
        <div class="max-w-5xl mx-auto">
            

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800"><i class="fa-solid fa-users text-green-600"></i> Danh Sách Lớp & Điểm Danh</h1>
                <a href="/" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded text-sm font-semibold transition shadow-sm">Về trang chủ</a>
            </div>

            @if(session('success'))
            <div class="bg-green-100 text-green-800 border-l-4 border-green-500 p-3 rounded mb-6 text-sm font-bold shadow-sm">
                <i class="fa-solid fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <!-- Header của bảng -->
                <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-list-check text-blue-600 mr-2"></i> Trạng thái điểm danh hành trình</h2>
                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">Sĩ số: {{ count($students) }} học sinh</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                <th class="px-6 py-4 font-semibold">Hồ sơ Học sinh</th>
                                <th class="px-6 py-4 font-semibold">Ngày Sinh</th>
                                <th class="px-6 py-4 font-semibold">Y tế / Sức khỏe</th>
                                <th class="px-6 py-4 font-semibold text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($students as $st)
                            <tr class="hover:bg-blue-50/50 transition duration-150 ease-in-out group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <!-- Avatar giả lập -->
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-md ring-2 ring-white">
                                            <i class="fa-solid fa-user-graduate"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 group-hover:text-blue-700 transition">{{ $st->full_name }}</div>
                                            <div class="text-xs text-gray-400">Mã HS: {{ sprintf('HS2026%04d', $st->id) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ \Carbon\Carbon::parse($st->dob)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    @if($st->medical_notes)
                                        <span class="inline-flex items-center gap-1.5 py-1.5 px-2.5 rounded-md text-xs font-bold bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20 shadow-sm">
                                            <i class="fa-solid fa-triangle-exclamation animate-pulse"></i> {{ $st->medical_notes }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 py-1.5 px-2.5 rounded-md text-xs font-bold bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20">
                                            <i class="fa-solid fa-check-circle"></i> Đủ điều kiện
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(in_array($st->id, session('attended_ids', [])))
                                        <!-- Nút ĐÃ điểm danh -->
                                        <span class="inline-flex w-36 justify-center items-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md cursor-default transition-all">
                                            <i class="fa-solid fa-check-double"></i> Có mặt
                                        </span>
                                    @else
                                        <!-- Nút CHƯA điểm danh -->
                                        <a href="/diem-danh/{{ $st->id }}" class="inline-flex w-36 justify-center items-center gap-2 bg-white text-blue-600 hover:bg-blue-600 hover:text-white border-2 border-blue-600 hover:border-transparent px-4 py-2 rounded-lg text-sm font-bold transition-all shadow-sm">
                                            <i class="fa-solid fa-fingerprint"></i> Điểm danh
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 text-gray-400 py-8 text-center mt-auto w-full">
        <p class="mb-2 text-gray-300">Học phần: <strong>CSE703073 - Lập trình ứng dụng web trong du lịch 2</strong> &mdash; Nhóm: <strong>Nhóm ĐT-17</strong></p>
        <p class="text-sm"><em>Sản phẩm học thuật &ndash; phục vụ mục đích đào tạo.</em></p>
    </footer>
</body>
</html>