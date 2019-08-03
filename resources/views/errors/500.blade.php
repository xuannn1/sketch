<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <link rel="stylesheet" href="/css/app.css">
        <title>发生了未知错误</title>
    </head>

    <body class="error-page">
        <div class="container-fluid">
            <div class="content">
                <div class="title">
                    <h1>发生了未知错误</h1>
                    <h4>因为不确定的原因，发生了未知错误。</h4>
                    <h6>详情/参数：{{ $exception->getMessage() }}</h6>
                </div>
            </div>
        </div>
    </body>
</html>
