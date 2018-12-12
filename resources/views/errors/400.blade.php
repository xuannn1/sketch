@extends('layouts.default')
@section('title', '出错啦')

@section('content')
<div class="container">
    <div class="content text-center">
        <div class="title">
            <h2>{{ $exception->getMessage() }}</h2>
        </div>
    </div>
</div>
@stop
