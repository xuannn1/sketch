<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $user->name }}的邮箱修改确认</title>
</head>
<body>
    <h1>「{{ $user->name }}」你好，你刚刚发起了邮箱更改请求！</h1>
    <h2>请求具体要求为：</h2>
    <h3>将原邮箱：</h3>
    <div class="">
        <code>{{$record->old_email}}</code>
        (本邮箱已验证)
    </div>
    <h3>更改为下述邮箱地址：</h3>
    <div class="">
        <code>{{$record->new_email}}</code>
    </div>
    <br>
    <h5>本邮箱已验证。</h5>
    <h5>如果这是你本人的操作，请点击下列链接，完成邮箱更改。</h5>
    <a href="{{ route('update_email_by_token', $record->token) }}">
        {{ route('update_email_by_token', $record->token) }}
    </a>
    <h5>如果这不是你本人的操作，请及时修改个人密码。</h5>
</body>
</html>
