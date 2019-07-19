<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $user->name }}的邮箱修改确认</title>
</head>
<body>
    <h1>「{{ $user->name }}」您好，您刚刚发起了邮箱更改请求！</h1>
    <h2>请求具体要求为：</h2>
    <h3>将原邮箱：</h3>
    <div class="">
        <code>{{$record->old_email}}</code>
    </div>
    <h3>更改为下述邮箱地址：</h3>
    <div class="">
        <code>{{$record->new_email}}</code>
    </div>
    <br>
    <div class="">
        本操作验证token：{{ $record->token }}
    </div>
    <h5>如果这是您本人的操作，请忽略本邮件。</h5>
    <h5>如果这不是您本人的操作，请前往微博@废文网大内总管处申诉，申诉时请提供本邮件截图。</h5>
</body>
</html>
