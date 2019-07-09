@extends('layouts.default')
@section('title', '数据冲突')

@section('content')
<div class="container-fluid">
    <div class="content text-center">
        <div class="title">
            <h1>数据冲突错误</h1>
            <h2>{{ $exception->getMessage() }}</h2>
        </div>
    </div>
</div>
@stop
