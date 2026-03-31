<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            background: #f5f5f5;
            padding: 30px;
        }
        .email-box {
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            width: 100%;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="email-box">
    <h2 class="title">Order Successfully Completed 🎉</h2>

    <p>Dear {{ $order->customer->name ?? 'Customer' }},</p>

    <p>We have successfully received your order. Please check the order details below:</p>

    <p><strong>Order ID:</strong> {{ $order->invoice_id  }}</p>
    <p><strong>Total Amount:</strong> ${{ $order->amount  }}</p>

    <p>Thank you for shopping with us. Your order will be shipped very soon.</p>

    <div class="footer">
        © {{ date('Y') }}  Hygge Cotton. All rights reserved.
    </div>
</div>

</body>
</html>
