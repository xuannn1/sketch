@extends('layouts.default')
@section('title', '出错啦')

@section('content')
<div class="container-fluid">
    <div class="content text-center">
        <div class="title">
            <h1>普通错误</h1>
            <h4>出现了错误，本页面无法访问</h4>
            <h6>详情/参数：{{ $exception->getMessage() }}</h6>
        </div>
    </div>
</div>
@stop
