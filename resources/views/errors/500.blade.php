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
                    <h5>如果参数内含有“too many connection”字样，说明数据库连接过多（人多），这种情况静待流量高峰过去后刷新即可。</h5>
                    <h5>如果是其他问题，可以携带完整页面信息和操作过程前往<a href="https://sosad.fun/threads/16807">《bug楼》</a>反馈，非常感谢。</h5>
                    <h6>详情/参数：{{ $exception->getMessage() }}</h6>
                </div>
            </div>
        </div>
    </body>
</html>
