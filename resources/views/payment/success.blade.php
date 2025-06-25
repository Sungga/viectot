<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán thành công</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }
        .message {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            width: 300px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .message h1 {
            font-size: 24px;
        }
        .message p {
            font-size: 16px;
        }
        .button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0b7dda;
        }
    </style>
</head>
<body>
    <div class="message">
        <h1>Thanh toán thành công!</h1>
        <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi. Giao dịch của bạn đã được xử lý thành công.</p>
        <a href="{{ route('home') }}">Quay lại trang chủ</a>
    </div>
</body>
</html>
