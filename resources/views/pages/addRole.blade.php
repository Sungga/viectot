<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- icon --}}
    <link rel="icon" href="{{ asset('storage/uploads/VIECTOT.png') }}" type="image/x-icon" />

    <!-- font google -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <title>Document</title>

    @vite(['resources/css/base/base.css'])
    @vite(['resources/css/base/reset.css'])

    <style>
        * {
            text-decoration: none;
            margin: 0;
        }

        .container {
            width: 100%;
            height: 100vh;
            position: relative;
            display: flex;
        }

        .exit {
            position: absolute;
            top: 10px;
            left: 10px;
            color: #046bc9;
            font-size: 1.4rem;
        }

        form {
            margin: auto;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            width: 400px;
            padding: 8px;
            border: 1px solid #000;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 16px;
            width: 100%;
            color: #046bc9;
            text-align: center
        }

        select {
            font-size: 1.4rem;
            padding: 8px 16px;
        }

        .btn {
            width: 100%;
            text-align: center;
            margin-top: 12px;
        }

        input {
            widows: 100%;
            font-size: 1.4rem;
            padding: 8px 16px;
            border: 1px solid var(--color-black);
            outline: none;
        }

        button {
            padding: 8px 16px;
            border: 1px solid var(--color-base);
            font-size: 1.4rem;
            color: var(--color-white);
            background: var(--color-base);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <a class="exit" href="{{ route('home') }}"><i class="fa-solid fa-arrow-left"></i> Quay về trang chủ</a>
        <form action="{{ route('addRole') }}" method="POST">
            @csrf
            <h2>Bạn đến với chúng tôi với vai trò gì</h2>
            <input type="hidden" name="id" value="{{ $id }}">
            <select name="role" id="">
                <option value="2">Ứng viên</option>
                <option value="1">Nhà tuyển dụng</option>
            </select>
            <div class="btn">
                <input type="text" name="name" placeholder="Nhập tên của bạn">
            </div>
            <div class="btn">
                <button>Tiếp tục</button>
            </div>
        </form>
    </div>
</body>
</html>