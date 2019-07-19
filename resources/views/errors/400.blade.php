@extends('layouts.default')
@section('title', '出错啦')

@section('content')
<div class="container-fluid">
    <div class="content text-center">
        <div class="title">
            <h1>普通错误</h1>
            <h2>{{ $exception->getMessage() }}</h2>
        </div>
    </div>
</div>
@stop
