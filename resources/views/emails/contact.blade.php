<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Form Message</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1 style="color: #1a1a1a; border-bottom: 2px solid #4f46e5; padding-bottom: 10px;">
        New Contact Form Message
    </h1>

    <table style="width: 100%; margin: 20px 0;">
        <tr>
            <td style="padding: 10px 0; font-weight: bold; width: 100px;">From:</td>
            <td style="padding: 10px 0;">{{ $senderName }}</td>
        </tr>
        <tr>
            <td style="padding: 10px 0; font-weight: bold;">Email:</td>
            <td style="padding: 10px 0;">
                <a href="mailto:{{ $senderEmail }}" style="color: #4f46e5;">{{ $senderEmail }}</a>
            </td>
        </tr>
    </table>

    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 20px;">
        <h2 style="margin-top: 0; color: #1a1a1a; font-size: 16px;">Message:</h2>
        <p style="white-space: pre-wrap; margin-bottom: 0;">{{ $messageBody }}</p>
    </div>

    <p style="margin-top: 30px; color: #666; font-size: 14px;">
        This message was sent via the contact form on your portfolio website.
    </p>
</body>
</html>
