<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Challan Link</title>
    <style>
        .btn-link {
            display: inline-block;
            padding: 8px 16px;
            border: 2px solid #007BFF;
            color: #007BFF;
            text-decoration: none;
            border-radius: 4px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-link:hover {
            background-color: #007BFF;
            color: white;
        }
    </style>
</head>
<body>
    <p>Dear {{ $student->name }},</p>
    <p>Your challan is ready. You can view it at the link below:</p>
    <p><a href="{{ $link }}" class="btn-link">Download Challan</a></p>
    <p>Thank you,<br>STMU Finance Office</p>
</body>
</html>
