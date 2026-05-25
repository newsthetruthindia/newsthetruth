<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to News The Truth</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px 20px;">
        <h1 style="font-size: 28px; font-weight: 900; color: #111827; margin-bottom: 8px;">News The Truth</h1>
        <hr style="border: none; border-top: 3px solid #8c0000; margin: 16px 0 32px; width: 60px;">
        
        <h2 style="font-size: 20px; color: #111827; margin-bottom: 16px;">Welcome, {{ $user->firstname }}!</h2>
        
        <p style="color: #4b5563; font-size: 15px; line-height: 1.6; margin-bottom: 24px;">
            Thank you for subscribing to <strong>News The Truth</strong>. We are thrilled to have you join our community!
        </p>
        
        <p style="color: #4b5563; font-size: 15px; line-height: 1.6; margin-bottom: 24px;">
            As a registered subscriber, you can now:
        </p>
        <ul style="color: #4b5563; font-size: 15px; line-height: 1.6; margin-bottom: 24px; padding-left: 20px;">
            <li>Receive our daily trending news straight to your inbox.</li>
            <li>Submit your own local stories through our <strong>Citizen Journalism</strong> portal.</li>
            <li>Customize your news feed with topics you care about.</li>
            <li>Engage with the community via comments and polls.</li>
        </ul>
        
        <div style="text-align: center; margin-top: 32px; margin-bottom: 32px;">
            <a href="{{ env('FRONTEND_URL', 'https://newsthetruth.com') }}" style="display: inline-block; background: #8c0000; color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px;">Explore The News</a>
        </div>
        
        <p style="color: #9ca3af; font-size: 12px; margin-top: 32px; line-height: 1.5; text-align: center;">
            If you did not sign up for this account, please ignore this email.
        </p>
    </div>
</body>
</html>
