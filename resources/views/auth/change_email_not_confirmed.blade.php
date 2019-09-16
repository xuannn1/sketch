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
        （本邮箱未验证）
    </div>
    <h3>更改为下述邮箱地址：</h3>
    <div class="">
        <code>{{$record->new_email}}</code>
    </div>
    <br>
    <div class="">
        本操作验证token：{{ $record->token }}
    </div>
    <h5>本邮箱此前未经验证，无法享受邮箱锁定保护，上述信息已经更改。</h5>
    <h5>如果这是你本人的操作，请忽略本邮件。</h5>
    <br>
    <h5>如果这【不是】你本人的操作，请按照下述流程，通过废文网公共邮箱完成盗号申诉，申诉时请提供本邮件截图。</h5>
    <h5>具体申诉流程及格式: <a href="https://sosad.fun/search?search=%E7%9B%97%E5%8F%B7"></a></h5>
</body>
</html>
