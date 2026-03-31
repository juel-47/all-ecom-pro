<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Job Application Received</title>
</head>
<body>
    <p>Dear {{ $application->name }},</p>

    <p>Thank you for applying for the position of <strong>{{ $application->position }}</strong>.</p>

    <p>We have received your application and will review it carefully. Our team will contact you if you are shortlisted.</p>

    <p>Best regards,<br>
        {{ $settings->site_name }}</p>
</body>
</html>
