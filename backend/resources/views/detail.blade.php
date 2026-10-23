<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>{{ $program->title }} - Chi tiết hành trình</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-blue-800 text-white shadow-lg z-10 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
            <a href="/" class="font-bold text-xl flex items-center gap-2 hover:text-gray-200 transition">
                <i class="fa-solid fa-arrow-left"></i> Trở về Trang chủ
            </a>
        </div>
    </nav>

    <!-- Nội dung chính -->
    <main class="flex-grow max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
        <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
            @php
            $tourImages = [
            1 => 'https://upload.wikimedia.org/wikipedia/commons/6/62/An_Duong_Vuong_Temple_Gate_Co_Loa.jpg?utm_source=vi.wikipedia.org&utm_campaign=index&utm_content=original', // Cổ Loa
            2 => 'https://images.vietnamtourism.gov.vn/vn//images/2023/thang12/0512.pu_luong.jpg', // Pù Luông
            3 => 'https://bizweb.dktcdn.net/100/006/093/files/thien-ha-2-57d166a1-e72c-4466-9f65-6364e2cf15ea.jpg?v=1577160060750', // Tràng An
            4 => 'https://langngheviet.com.vn/stores/news_dataimages/langnghevietcomvn/042021/26/09/lang-lua-van-phuc-31-.6337.jpg', // Vạn Phúc
            5 => 'https://statics.vinpearl.com/van-mieu-mon-van-mieu-quoc-tu-giam_1752765402.jpg', // Văn Miếu
            6 => 'https://www.vietnambooking.com/wp-content/uploads/2017/08/du-lich-can-tho-30-8-2017-sinh-thai.jpg', // Cần Thơ
            ];
            $imgUrl = $tourImages[$program->id] ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=1200&auto=format&fit=crop';
            @endphp

            <div class="relative h-80 w-full bg-cover bg-center" style="background-image: url('{{ $imgUrl }}')">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/50 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-8 w-full">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded shadow-sm">CẤP: {{ $program->edu_level }}</span>
                        <span class="bg-white/20 backdrop-blur-sm text-white border border-white/30 text-xs font-bold px-3 py-1 rounded shadow-sm">MÔN: {{ mb_strtoupper($program->subject_name) }}</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2 tracking-tight drop-shadow-md">{{ $program->title }}</h1>
                    <p class="text-gray-200 font-medium flex items-center gap-2 drop-shadow">
                        <i class="fa-solid fa-location-dot text-red-400"></i> Điểm đến: {{ $program->destination_name }} ({{ $program->province }})
                    </p>
                </div>
            </div>
            <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="md:col-span-2">
                    <h2 class="text-xl font-bold text-gray-800 border-b pb-2 mb-4"><i class="fa-solid fa-bullseye text-blue-600 mr-2"></i>Mục tiêu giáo dục (Cần đạt)</h2>
                    <p class="text-gray-700 leading-relaxed mb-10">{{ $program->learning_objectives }}</p>

                    <h2 class="text-xl font-bold text-gray-800 border-b pb-2 mb-6"><i class="fa-solid fa-route text-blue-600 mr-2"></i>Lịch trình chi tiết</h2>
                    <div class="space-y-0 mt-4 pl-2">
                        @foreach($itineraries as $iti)
                        <div class="flex items-start gap-5 group relative">
                            <div class="flex-shrink-0 w-14 text-right font-bold text-blue-600 mt-0.5">{{ $iti->time_slot }}</div>
                            <div class="relative z-10 w-3.5 h-3.5 bg-blue-400 rounded-full mt-1.5 ring-4 ring-white group-hover:bg-blue-600 transition shadow-sm"></div>
                            <div class="flex-grow pb-8 border-l-2 border-gray-200 pl-5 -ml-[23px] relative z-0">
                                <h4 class="font-bold text-gray-900 text-lg">{{ $iti->checkpoint_name }}</h4>
                                <p class="text-gray-600 text-sm mt-1.5 leading-relaxed">{{ $iti->edu_activity }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-gray-50 p-6 md:p-7 rounded-xl border border-gray-100 h-fit shadow-sm">
                    <div class="text-gray-500 text-sm mb-1 font-semibold uppercase tracking-wider">Chi phí dự kiến</div>
                    <div class="text-3xl font-extrabold text-red-600 mb-6">{{ number_format($program->base_price, 0, ',', '.') }}đ</div>

                    <div class="space-y-4 text-sm text-gray-700 mb-8 border-t border-gray-200 pt-5">
                        <p class="flex items-center gap-3"><i class="fa-solid fa-users text-blue-500 text-lg w-5 text-center"></i> <span>Sĩ số chuẩn: <strong>{{ $program->capacity }} học sinh</strong></span></p>
                        <p class="flex items-start gap-3"><i class="fa-solid fa-shield-heart text-green-500 text-lg w-5 text-center mt-0.5"></i> <span>An toàn: <strong>{{ $program->safety_protocol }}</strong></span></p>
                    </div>

                    @if(session('user_role') == 'Ban Giám Hiệu')
                    <button class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3.5 rounded-lg text-center transition-all shadow-md transform hover:scale-105" onclick="alert('Xác nhận đăng ký thành công! Mã hồ sơ chuyến đi đã được khởi tạo. Vui lòng theo dõi tiến độ phê duyệt tại Bảng Điều Khiển Ban Giám Hiệu.')">
                        <i class="fa-solid fa-check-circle mr-1"></i> Đăng ký chuyến đi
                    </button>
                    @else
                    <button class="w-full bg-gray-200 text-gray-500 font-bold py-3.5 rounded-lg text-center cursor-not-allowed shadow-inner border border-gray-300" onclick="alert('Từ chối truy cập: Tính năng này chỉ dành cho tài khoản Ban giám hiệu nhà trường!')">
                        <i class="fa-solid fa-lock mr-1"></i> Đăng ký cho Lớp
                    </button>
                    @endif
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