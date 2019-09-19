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
    <h5>本邮箱此前已验证激活，享受邮箱锁定保护，信息尚未更改。</h5>
    <h5>如果这是你本人的操作，请复制下列链接，从浏览器空白页面中打开，一键完成邮箱更改的最后步骤。</h5>
    <a href="{{ route('update_email_by_token', $record->token) }}">
        {{ route('update_email_by_token', $record->token) }}
    </a>
    <br>
    <h5>如果这【不是】你本人的操作，请【不要】点击上面的链接。收到本邮件，说明有其他用户正在尝试修改你的邮箱，请及时修改个人密码，加强密码安全性。请放心，在不点击上述链接的情况下，系统不会对邮箱进行修改，你的账户信息仍然安全。</h5>
    <h5>其他邮箱问题，可直接搜索关键词“盗号”获取: <a href="https://sosad.fun/search?search=%E7%9B%97%E5%8F%B7">https://sosad.fun/search?search=%E7%9B%97%E5%8F%B7</a></h5>
    <h5><span style="color:#d66666">（邮件来自自动发件信箱，请不要直接回复本邮件）</span></h5>
</body>
</html>
