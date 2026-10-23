<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>Báo cáo BGH - ĐT-17</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-8 font-sans">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800"><i class="fa-solid fa-chart-pie text-blue-600"></i> Bảng Điều Khiển Ban Giám Hiệu</h1>
            <a href="/" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Về trang chủ</a>
        </div>

        <!-- Chỉ số dữ liệu ẩn -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                <div class="text-gray-500 text-sm">Phiếu Đồng Ý Đã Ký</div>
                <div class="text-3xl font-bold text-gray-800">{{ $stats['total_consent'] }}</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                <div class="text-gray-500 text-sm">Lượt Điểm Danh Chặng</div>
                <div class="text-3xl font-bold text-gray-800">{{ $stats['total_attendance'] }}</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
                <div class="text-gray-500 text-sm">Bài Thu Hoạch & Đánh Giá</div>
                <div class="text-3xl font-bold text-gray-800">{{ $stats['total_evals'] }}</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow border-l-4 border-orange-500">
                <div class="text-gray-500 text-sm">Điểm Đánh Giá Trung Bình</div>
                <div class="text-3xl font-bold text-gray-800">{{ number_format($stats['avg_score'], 1) }}/10</div>
            </div>
        </div>

        <!-- Bảng theo dõi -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-gray-800 text-white px-6 py-4 font-bold">Lịch Sử Điểm Danh & Y Tế (Real-time)</div>
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-gray-600">Học Sinh</th>
                        <th class="px-6 py-3 text-gray-600">Lưu ý Y tế</th>
                        <th class="px-6 py-3 text-gray-600">Chặng Hành Trình</th>
                        <th class="px-6 py-3 text-gray-600">Trạng Thái</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($recent_logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold">{{ $log->full_name }}</td>
                        <td class="px-6 py-4 text-red-600 text-sm">
                            @if($log->medical_notes)
                            <i class="fa-solid fa-triangle-exclamation"></i> {{ $log->medical_notes }}
                            @else
                            Không
                            @endif
                        </td>
                        <td class="px-6 py-4 text-blue-600">{{ $log->checkpoint_name }}</td>
                        <td class="px-6 py-4">
                            @if($log->status == 'present')
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Có mặt</span>
                            @else
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Vắng mặt</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>