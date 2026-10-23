<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nền Tảng Du Lịch Học Đường ĐT-17</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased flex flex-col min-h-screen">

    <nav class="bg-blue-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 font-bold text-xl flex items-center gap-2">
                    <i class="fa-solid fa-bus-school"></i> ĐT-17 EduTour
                </div>
                <div class="hidden md:flex space-x-4 items-center">
                    @if(session()->has('user_name'))
                    <span class="text-sm font-semibold text-yellow-300"><i class="fa-solid fa-user-check"></i> Chào, {{ session('user_name') }} ({{ session('user_role') }})</span>

                    @if(session('user_role') == 'Giáo Viên' || session('user_role') == 'Ban Giám Hiệu')
                    <a href="/quan-ly" class="bg-green-600 hover:bg-green-500 px-4 py-2 rounded text-sm font-bold shadow"><i class="fa-solid fa-clipboard-list"></i> Điểm Danh Lớp</a>
                    @endif

                    <a href="/logout" class="bg-red-600 hover:bg-red-500 px-4 py-2 rounded text-sm"><i class="fa-solid fa-sign-out-alt"></i> Thoát</a>
                    @else
                    <a href="/login" class="bg-green-600 hover:bg-green-500 px-4 py-2 rounded text-sm font-bold shadow"><i class="fa-solid fa-lock"></i> Đăng nhập hệ thống</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-blue-100 py-16 text-center border-b border-blue-200">
        <h1 class="text-4xl font-extrabold text-blue-900 mb-4">Hành Trình Trải Nghiệm - Gắn Liền Mục Tiêu Giáo Dục</h1>
        <p class="text-lg text-blue-700 max-w-2xl mx-auto mb-8">Nền tảng kết nối Nhà trường, Phụ huynh và Đơn vị tổ chức. Đảm bảo an toàn, minh bạch hồ sơ y tế và bám sát khung chương trình GDPT.</p>
        <div class="flex justify-center gap-6">
            <div class="bg-white p-4 rounded shadow text-center w-32 border-t-4 border-blue-500">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['schools'] }}</div>
                <div class="text-xs text-gray-500 uppercase">Trường học</div>
            </div>
            <div class="bg-white p-4 rounded shadow text-center w-32 border-t-4 border-green-500">
                <div class="text-2xl font-bold text-green-600">{{ $stats['students'] }}</div>
                <div class="text-xs text-gray-500 uppercase">Học sinh</div>
            </div>
            <div class="bg-white p-4 rounded shadow text-center w-32 border-t-4 border-orange-500">
                <div class="text-2xl font-bold text-orange-600">{{ $stats['tours'] }}</div>
                <div class="text-xs text-gray-500 uppercase">Chương trình</div>
            </div>
        </div>
    </div>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Danh Mục Chương Trình Tiêu Biểu</h2>
                <p class="text-gray-600 mt-2">Ánh xạ trực tiếp từ yêu cầu cần đạt của Bộ GD&ĐT.</p>
            </div>

            <form action="/" method="GET" class="w-full md:w-96 flex">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên tour, điểm đến..." class="w-full border border-gray-300 rounded-l px-4 py-2 focus:outline-none focus:border-blue-500">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r"><i class="fa-solid fa-search"></i></button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($programs as $prog)
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition flex flex-col">
                @php
                // Map ID tour với link ảnh chất lượng cao
                $tourImages = [
                1 => 'https://upload.wikimedia.org/wikipedia/commons/6/62/An_Duong_Vuong_Temple_Gate_Co_Loa.jpg?utm_source=vi.wikipedia.org&utm_campaign=index&utm_content=original', // Cổ Loa
                2 => 'https://images.vietnamtourism.gov.vn/vn//images/2023/thang12/0512.pu_luong.jpg', // Pù Luông
                3 => 'https://bizweb.dktcdn.net/100/006/093/files/thien-ha-2-57d166a1-e72c-4466-9f65-6364e2cf15ea.jpg?v=1577160060750', // Tràng An
                4 => 'https://langngheviet.com.vn/stores/news_dataimages/langnghevietcomvn/042021/26/09/lang-lua-van-phuc-31-.6337.jpg', // Vạn Phúc
                5 => 'https://statics.vinpearl.com/van-mieu-mon-van-mieu-quoc-tu-giam_1752765402.jpg', // Văn Miếu
                6 => 'https://www.vietnambooking.com/wp-content/uploads/2017/08/du-lich-can-tho-30-8-2017-sinh-thai.jpg', // Cần Thơ
                ];
                $imgUrl = $tourImages[$prog->id] ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=600&auto=format&fit=crop';
                @endphp

                <div class="h-40 w-full bg-cover bg-center relative" style="background-image: url('{{ $imgUrl }}')">
                    <div class="absolute inset-0 bg-black bg-opacity-20 transition hover:bg-opacity-10"></div>
                    <span class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded shadow-md z-10">Cấp: {{ $prog->edu_level }}</span>
                </div>
                <div class="p-5 flex-grow">
                    <div class="text-xs text-blue-600 font-semibold uppercase mb-1">{{ $prog->subject_name }}</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 truncate">{{ $prog->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2" title="{{ $prog->learning_objectives }}">{{ $prog->learning_objectives }}</p>

                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <i class="fa-solid fa-location-dot w-5"></i> {{ $prog->destination_name }}, {{ $prog->province }}
                    </div>
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <i class="fa-solid fa-shield-halved w-5 text-green-600"></i> <span class="truncate">{{ $prog->safety_protocol }}</span>
                    </div>
                </div>
                <div class="p-5 pt-0 mt-auto border-t border-gray-100 pt-4 flex justify-between items-center">
                    <span class="text-lg font-bold text-red-600">{{ number_format($prog->base_price, 0, ',', '.') }}đ</span>
                    <a href="/chuong-trinh/{{ $prog->id }}" class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2 rounded text-sm font-semibold transition">Xem chi tiết <i class="fa-solid fa-arrow-right ml-1"></i></a>
                </div>
            </div>
            @endforeach
        </div>
    </main>


    <footer class="bg-gray-800 text-gray-400 py-8 text-center mt-auto">
        <p class="mb-2 text-gray-300">Học phần: <strong>CSE703073 - Lập trình ứng dụng web trong du lịch 2</strong> &mdash; Nhóm: <strong>Nhóm ĐT-17</strong></p>
        <p class="text-sm"><em>Sản phẩm học thuật &ndash; phục vụ mục đích đào tạo.</em></p>
    </footer>

</body>

</html>