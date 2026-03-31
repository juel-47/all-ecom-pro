<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>New Contact Message</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  /* Base Styles */
  body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 20px;
  }

  /* Container Styles */
  .email-container {
    max-width: 600px;
    margin: 0 auto;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }

  /* Header Styles */
  .email-header {
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    color: white;
    padding: 30px;
    text-align: center;
  }

  .email-header h1 {
    margin: 0;
    font-weight: 600;
    font-size: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .email-header p {
    margin: 10px 0 0;
    opacity: 0.9;
  }

  /* Body Styles */
  .email-body {
    padding: 30px;
  }

  /* Card Styles */
  .info-card {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    border-left: 4px solid #2575fc;
  }

  .message-card {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
  }

  /* Info Item Styles */
  .info-item {
    margin-bottom: 15px;
    display: flex;
    align-items: center;
  }

  .info-item:last-child {
    margin-bottom: 0;
  }

  .info-icon {
    width: 40px;
    height: 40px;
    background-color: #2575fc;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
  }

  .info-content {
    flex-grow: 1;
  }

  .info-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 5px;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .info-value {
    color: #212529;
    font-size: 16px;
  }

  /* Message Styles */
  .message-title {
    font-weight: 600;
    color: #495057;
    margin-bottom: 15px;
    font-size: 18px;
    display: flex;
    align-items: center;
  }

  .message-content {
    background-color: white;
    border-radius: 6px;
    padding: 15px;
    border: 1px solid #e9ecef;
    min-height: 100px;
    white-space: pre-wrap;
  }

  /* Footer Styles */
  .email-footer {
    background-color: #f8f9fa;
    padding: 20px 30px;
    text-align: center;
    color: #6c757d;
    font-size: 14px;
    border-top: 1px solid #e9ecef;
  }

  /* Button Styles */
  .action-button {
    display: inline-block;
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    color: #ffffff !important;
    padding: 12px 25px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 500;
    margin-top: 20px;
    transition: all 0.3s ease;
  }

  .action-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(37, 117, 252, 0.3);
  }

  /* Responsive Styles */
  @media (max-width: 576px) {
    .email-container {
      border-radius: 0;
    }

    .info-item {
      flex-direction: column;
      align-items: flex-start;
    }

    .info-icon {
      margin-right: 0;
      margin-bottom: 10px;
    }
  }
  </style>
</head>

<body>
  <!-- Get footer info from database -->
  @php
  $footerInfo = \App\Models\FooterInfo::select('copyright')->first();
  @endphp

  <!-- Email Container -->
  <div class="email-container">
    <!-- Email Header -->
    <div class="email-header">
      <h1>

        New Contact Message
      </h1>
      <p>You've received a new message from your website {{ optional($settings)->site_name ?? 'Your Website' }} contact form</p>
    </div>

    <!-- Email Body -->
    <div class="email-body">
      <!-- Contact Information Card -->
      <div class="info-card">
        <!-- Name -->
        <div class="info-item">

          <div class="info-content">
            <div class="info-label">Name</div>
            <div class="info-value">{{ $data['first_name'] ?? '' }} {{ $data['last_name'] ?? '' }}</div>
          </div>
        </div>

        <!-- Email -->
        <div class="info-item">

          <div class="info-content">
            <div class="info-label">Email Address</div>
            <div class="info-value">{{ $data['email'] ?? '' }}</div>
          </div>
        </div>

        <!-- Phone -->
        <div class="info-item">

          <div class="info-content">
            <div class="info-label">Phone Number</div>
            <div class="info-value">{{ $data['phone'] ?? '' }}</div>
          </div>
        </div>
      </div>

      <!-- Message Card -->
      <div class="message-card">
        <div class="message-title">

          Message
        </div>
        <div class="message-content">
          {{ $data['message'] ?? '' }}
        </div>
      </div>

      <!-- Action Button -->
      <div class="text-center">
        <a href="mailto:{{ $data['email'] ?? '' }}" class="action-button">

          Reply to Sender
        </a>
      </div>
    </div>

    <!-- Email Footer -->
    <div class="email-footer">
      <p>This message was sent from your website contact form on {{ date('F j, Y, g:i a') }}</p>
      <p>{{ $footerInfo->copyright ?? '&copy; ' . date('Y') . ' Your Company. All rights reserved.' }}</p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>