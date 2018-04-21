@extends('layouts.default')
@section('title', '出错啦')

@section('content')
<div class="container">
    <div class="content text-center">
        <div class="title">
            <h1>{{ $error_message }}</h1>
        </div>
    </div>
</div>
@stop
