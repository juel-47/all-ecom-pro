{{-- <!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Reset Request</title>
  <style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: #333;
    background-color: #f5f7fa;
  }

  .email-container {
    max-width: 600px;
    margin: 0 auto;
    background-color: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .header {
    background: linear-gradient(135deg, #4a6cf7 0%, #2541b2 100%);
    padding: 30px 20px;
    text-align: center;
  }

  .logo {
    font-size: 28px;
    font-weight: bold;
    color: #ffffff;
    letter-spacing: 1px;
  }

  .content {
    padding: 40px 30px;
  }

  .greeting {
    font-size: 24px;
    margin-bottom: 20px;
    color: #2c3e50;
  }

  .message {
    font-size: 16px;
    margin-bottom: 30px;
    color: #5a6c7d;
  }

  .reset-button {
    display: inline-block;
    background: linear-gradient(135deg, #4a6cf7 0%, #2541b2 100%);
    color: #ffffff !important;
    text-decoration: none;
    padding: 14px 30px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 16px;
    text-align: center;
    margin: 30px 0;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(74, 108, 247, 0.3);
  }

  .reset-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(74, 108, 247, 0.4);
  }

  .security-note {
    background-color: #f8f9fa;
    border-left: 4px solid #4a6cf7;
    padding: 15px;
    margin: 30px 0;
    border-radius: 0 4px 4px 0;
  }

  .security-note p {
    font-size: 14px;
    color: #5a6c7d;
  }

  .footer {
    background-color: #f8f9fa;
    padding: 20px 30px;
    text-align: center;
    border-top: 1px solid #eaeaea;
  }

  .footer p {
    font-size: 14px;
    color: #8898aa;
    margin-bottom: 10px;
  }

  .footer-links {
    margin-top: 15px;
  }

  .footer-links a {
    color: #4a6cf7;
    text-decoration: none;
    margin: 0 10px;
    font-size: 14px;
  }

  .footer-links a:hover {
    text-decoration: underline;
  }

  .divider {
    height: 1px;
    background-color: #eaeaea;
    margin: 20px 0;
  }

  @media only screen and (max-width: 600px) {
    .content {
      padding: 30px 20px;
    }

    .reset-button {
      display: block;
      width: 100%;
    }
  }
  </style>
</head>

<body>
  <div class="email-container">
    <div class="header">
      <div class="logo">{{$settings->site_name}}</div>
    </div>
    <div class="content">
      <h2 class="greeting">Password Reset Request</h2>
      <p class="message">Hi,</p>
      <p class="message">We received a request to reset the password for your account. Click the button below to create
        a new password:</p>

      <div style="text-align: center;">
        <a href="{{ url('/reset-password?token=' . $token . '&email=' . $email) }}" class="reset-button">Reset
          Password</a>
      </div>

      <div class="security-note">
        <p><strong>Security Notice:</strong> If you did not request a password reset, please ignore this email. Your
          password will remain unchanged. This link will expire in 24 hours for your security.</p>
      </div>

      <p class="message">If you're having trouble clicking the button, you can also copy and paste the following link
        into your browser:</p>
      <p style="word-break: break-all; color: #4a6cf7; font-size: 14px;">
        {{ url('/reset-password?token=' . $token . '&email=' . $email) }}</p>
    </div>

    <div class="divider"></div>

    <div class="footer">
      <p>© {{ date('Y') }} {{$settings->site_name}}. All rights reserved.</p>
      <p style="margin-top: 15px; font-size: 12px;">This is an automated message. Please do not reply to this email.</p>
    </div>
  </div>
</body>

</html> --}}

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Reset Request</title>
  <style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: #333;
    background-color: #f5f7fa;
  }

  .email-container {
    max-width: 600px;
    margin: 0 auto;
    background-color: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .header {
    background: linear-gradient(135deg, #4a6cf7 0%, #2541b2 100%);
    padding: 30px 20px;
    text-align: center;
  }

  .logo {
    font-size: 28px;
    font-weight: bold;
    color: #ffffff;
    letter-spacing: 1px;
  }

  .content {
    padding: 40px 30px;
  }

  .greeting {
    font-size: 24px;
    margin-bottom: 20px;
    color: #2c3e50;
  }

  .message {
    font-size: 16px;
    margin-bottom: 30px;
    color: #5a6c7d;
  }

  .reset-button {
    display: inline-block;
    background: linear-gradient(135deg, #4a6cf7 0%, #2541b2 100%);
    color: #ffffff !important;
    text-decoration: none;
    padding: 14px 30px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 16px;
    text-align: center;
    margin: 30px 0;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(74, 108, 247, 0.3);
  }

  .reset-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(74, 108, 247, 0.4);
  }

  .security-note {
    background-color: #f8f9fa;
    border-left: 4px solid #4a6cf7;
    padding: 15px;
    margin: 30px 0;
    border-radius: 0 4px 4px 0;
  }

  .security-note p {
    font-size: 14px;
    color: #5a6c7d;
  }

  .footer {
    background-color: #f8f9fa;
    padding: 20px 30px;
    text-align: center;
    border-top: 1px solid #eaeaea;
  }

  .footer p {
    font-size: 14px;
    color: #8898aa;
    margin-bottom: 10px;
  }

  .footer-links {
    margin-top: 15px;
  }

  .footer-links a {
    color: #4a6cf7;
    text-decoration: none;
    margin: 0 10px;
    font-size: 14px;
  }

  .footer-links a:hover {
    text-decoration: underline;
  }

  .divider {
    height: 1px;
    background-color: #eaeaea;
    margin: 20px 0;
  }

  @media only screen and (max-width: 600px) {
    .content {
      padding: 30px 20px;
    }

    .reset-button {
      display: block;
      width: 100%;
    }
  }
  </style>
</head>

<body>
  <div class="email-container">
    <div class="header">
      <div class="logo">{{ $settings->site_name }}</div>
    </div>
    <div class="content">
      <h2 class="greeting">Password Reset Request</h2>
      <p class="message">Hi,</p>
      <p class="message">We received a request to reset the password for your account. Click the button below to create a new password:</p>

      <div style="text-align: center;">
        <!-- CORRECT LINK: Uses your exact route name with token in path -->
        <a href="{{ route('customer.password.reset', $token) }}?email={{ urlencode($email) }}" class="reset-button">
          Reset Password
        </a>
      </div>

      <div class="security-note">
        <p><strong>Security Notice:</strong> If you did not request a password reset, please ignore this email. Your password will remain unchanged. This link will expire in 24 hours for your security.</p>
      </div>

      <p class="message">If you're having trouble clicking the button, you can also copy and paste the following link into your browser:</p>
      <p style="word-break: break-all; color: #4a6cf7; font-size: 14px;">
        {{ route('customer.password.reset', $token) }}?email={{ urlencode($email) }}
      </p>
    </div>

    <div class="divider"></div>

    <div class="footer">
      <p>© {{ date('Y') }} {{ $settings->site_name }}. All rights reserved.</p>
      <p style="margin-top: 15px; font-size: 12px;">This is an automated message. Please do not reply to this email.</p>
    </div>
  </div>
</body>

</html>