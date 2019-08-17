@extends('layouts.default')
@section('title', '出错啦')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>普通错误</h1>
            </div>
            <div class="panel-body">
                <h4>出现了错误，本页面无法访问</h4>
                <h5>如果你有时间和意愿，可以携带完整页面信息和操作过程前往<a href="https://sosad.fun/threads/16807">《bug楼》</a>反馈，非常感谢。</h5>
                <h6>详情/参数：{{ $exception->getMessage() }}</h6>
            </div>
        </div>
    </div>
</div>
@stop
