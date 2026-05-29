<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Daily Digest - News The Truth</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px 20px;">
        <h1 style="font-size: 28px; font-weight: 900; color: #111827; margin-bottom: 8px;">News The Truth</h1>
        <hr style="border: none; border-top: 3px solid #8c0000; margin: 16px 0 32px; width: 60px;">
        
        <h2 style="font-size: 20px; color: #111827; margin-bottom: 16px;">Hello {{ $user->firstname }},</h2>
        
        <p style="color: #4b5563; font-size: 15px; line-height: 1.6; margin-bottom: 24px;">
            Here are the top 5 trending stories you might have missed today:
        </p>

        @foreach($posts as $post)
        <div style="margin-bottom: 24px; border-bottom: 1px solid #e5e7eb; padding-bottom: 16px;">
            <h3 style="font-size: 18px; margin-bottom: 8px; color: #111827;">{{ $post->title }}</h3>
            <p style="color: #4b5563; font-size: 14px; line-height: 1.5; margin-bottom: 12px;">
                {{ \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?? $post->description), 120) }}
            </p>
            <a href="{{ env('FRONTEND_URL', 'https://newsthetruth.com') }}/news/{{ $post->slug }}" style="color: #8c0000; font-weight: bold; text-decoration: none; font-size: 14px;">Read More &rarr;</a>
        </div>
        @endforeach
        
        <div style="text-align: center; margin-top: 32px; margin-bottom: 32px;">
            <a href="{{ env('FRONTEND_URL', 'https://newsthetruth.com') }}" style="display: inline-block; background: #8c0000; color: white; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px;">View All News</a>
        </div>
        
        <p style="color: #9ca3af; font-size: 12px; margin-top: 32px; line-height: 1.5; text-align: center;">
            You are receiving this because you are subscribed to News The Truth.<br>
            <a href="{{ env('FRONTEND_URL', 'https://newsthetruth.com') }}/settings" style="color: #6b7280;">Unsubscribe or Manage Preferences</a>
        </p>
    </div>
</body>
</html>
