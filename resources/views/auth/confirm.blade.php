<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $user->name }}的邮箱确认链接</title>
</head>
<body>
    <h1>{{ $user->name }}&nbsp;你好，感谢你在 废文网 进行注册！</h1>
    <p>
        请点击下面的链接完成邮箱验证：
        <a href="{{ route('confirm_email', $user->info->activation_token) }}">
            {{ route('confirm_email', $user->info->activation_token) }}
        </a>
    </p>
    <p>
        如果这不是你本人的操作，请忽略此邮件。
    </p>
    <p>
        如果链接无法打开或无法激活，请尝试清理浏览器缓存，或复制链接到其他浏览器内打开完成激活。
    </p>
</body>
</html>
