<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>Đăng nhập - ĐT-17</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md border-t-4 border-blue-600">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800"><i class="fa-solid fa-bus-school text-blue-600"></i> ĐT-17 EduTour</h1>
            <p class="text-gray-500 text-sm mt-2">Đăng nhập hệ thống quản lý</p>
        </div>

        <form action="/login" method="POST">
            <!-- Thẻ CSRF bắt buộc của Laravel -->
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email tài khoản</label>
                <input type="email" name="email" required placeholder="vidu: admin@demo.test" class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Mật khẩu</label>
                <input type="password" name="password" required value="MatKhau123!" class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập
            </button>
        </form>

        <div class="mt-6 text-xs text-gray-500 bg-gray-50 p-3 rounded border">
            <strong>Tài khoản Demo (Xem file Excel):</strong><br>
            - BGH/Tổ chức: <i>admin@demo.test</i><br>
            - Giáo viên: <i>school1@demo.test</i><br>
            - Phụ huynh: <i>parent01@demo.test</i>
        </div>
    </div>
</body>

</html>