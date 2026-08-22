<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f7;
            color: #51545e;
            margin: 0;
            padding: 0;
            width: 100% !important;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f4f7;
            padding: 24px;
        }
        .email-content {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .email-header {
            background-color: #cc9966;
            padding: 24px;
            text-align: center;
            color: #ffffff;
        }
        .email-header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }
        .email-body {
            padding: 32px;
            line-height: 1.6;
        }
        .email-body h2 {
            margin-top: 0;
            color: #333333;
            font-size: 18px;
            border-bottom: 1px solid #edf2f7;
            padding-bottom: 10px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .info-table td {
            padding: 8px 0;
            vertical-align: top;
        }
        .info-table td.label {
            font-weight: bold;
            color: #333333;
            width: 120px;
        }
        .message-box {
            background-color: #f8fafc;
            border-left: 4px solid #cc9966;
            padding: 16px;
            font-style: italic;
            margin-top: 10px;
            border-radius: 0 4px 4px 0;
        }
        .email-footer {
            background-color: #f4f4f7;
            padding: 24px;
            text-align: center;
            font-size: 12px;
            color: #b0adc5;
            border-top: 1px solid #edf2f7;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-content">
            <div class="email-header">
                <h1>New Contact Us Message</h1>
            </div>
            <div class="email-body">
                <h2>Contact Information</h2>
                <table class="info-table">
                    <tr>
                        <td class="label">Name:</td>
                        <td>{{ $contact['name'] }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email:</td>
                        <td><a href="mailto:{{ $contact['email'] }}" style="color: #cc9966;">{{ $contact['email'] }}</a></td>
                    </tr>
                    @if(!empty($contact['phone']))
                    <tr>
                        <td class="label">Phone:</td>
                        <td>{{ $contact['phone'] }}</td>
                    </tr>
                    @endif
                    @if(!empty($contact['subject']))
                    <tr>
                        <td class="label">Subject:</td>
                        <td>{{ $contact['subject'] }}</td>
                    </tr>
                    @endif
                </table>

                <h2>Message Details</h2>
                <div class="message-box">
                    {!! nl2br(e($contact['message'])) !!}
                </div>
            </div>
            <div class="email-footer">
                <p>This message was sent from the contact form on your website.</p>
            </div>
        </div>
    </div>
</body>
</html>
