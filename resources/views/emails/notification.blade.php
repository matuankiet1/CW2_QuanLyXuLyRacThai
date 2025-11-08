<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f5f5f5;">
    <div style="background-color: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div style="background-color: #007bff; padding: 20px; border-radius: 5px 5px 0 0; margin: -30px -30px 20px -30px;">
            <h2 style="color: #ffffff; margin: 0; font-size: 24px;">{{ $title }}</h2>
        </div>
        
        <!-- Greeting -->
        @if(isset($userName) && $userName)
        <p style="color: #666; margin-bottom: 20px;">Xin chào <strong>{{ $userName }}</strong>,</p>
        @endif
        
        <!-- Content -->
        <div style="background-color: #f8f9fa; padding: 20px; border-left: 4px solid #007bff; border-radius: 4px; margin-bottom: 20px;">
            <p style="margin: 0; white-space: pre-wrap; color: #333;">{{ $message }}</p>
        </div>
        
        <!-- Footer -->
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6; text-align: center; color: #6c757d; font-size: 12px;">
            <p style="margin: 5px 0;">Đây là thông báo tự động từ hệ thống quản lý xử lý rác thải.</p>
            <p style="margin: 5px 0;">Vui lòng không trả lời email này.</p>
            <p style="margin: 5px 0;">© {{ date('Y') }} Hệ thống quản lý xử lý rác thải. Tất cả các quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
