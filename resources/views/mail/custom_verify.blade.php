<!-- resources/views/emails/custom_verify.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email | {{ $settings->site_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* --- Global Styles & Resets --- */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f4f7f6; }
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* --- Main Styles --- */
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden; /* To contain the header and footer corners */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .header {
            background-color: #f0f4f8;
            padding: 30px 40px;
            text-align: center;
            border-bottom: 1px solid #e1e8ed;
        }
        .header img {
            width: 150px;
            height: auto;
        }
        .content {
            padding: 40px;
            font-family: 'Poppins', sans-serif;
            color: #333333;
            font-size: 16px;
            line-height: 1.7;
        }
        .content h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .content p {
            margin-bottom: 20px;
        }
        .btn-container {
            text-align: center;
            margin: 35px 0;
        }
        .btn-verify {
            display: inline-block;
            padding: 15px 35px;
            background-color: #007bff;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }
        .btn-verify:hover {
            background-color: #0056b3;
        }
        .footer {
            background-color: #f0f4f8;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e1e8ed;
        }
        .footer p {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #6c757d;
            margin: 0 0 10px;
        }
        .footer .social-links a {
            margin: 0 10px;
            text-decoration: none;
        }
        .footer .social-links img {
            width: 24px;
            height: 24px;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }
        .footer .social-links img:hover {
            opacity: 1;
        }
        .footer .copyright {
            margin-top: 20px;
            font-size: 12px;
        }

    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        {{-- <div class="header">
            <img src="{{ asset($logoSetting->logo) }}" alt="Hyggo Cotton Logo">
        </div> --}}

        <!-- Content -->
        <div class="content">
            {{-- <h1>Verify Your Email Address</h1> --}}

            <p>Dear Sir,</p>

            <p>Thank you for registering an account with Hyggo Cotton. To complete your registration and ensure the security of your information, please verify your email address by selecting the button below:</p>

            <div class="btn-container">
                <a href="{{ $url }}" class="btn-verify">Verify Email</a>
            </div>

            <p>Upon successful verification, you will gain full access to your account and all associated services.</p>

            <p>If you did not initiate this registration, please disregard this message. No further action is required, and your email address will remain unaffected.</p>

            <p>Should you require any assistance, please contact our customer support team.</p>

            <p>Sincerely,<br>
            Hyggo Cotton</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="copyright">
                &copy; {{ date('Y') }} Hyggo Cotton. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>