@extends('layouts.default')
@section('title', '论坛')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>论坛</h3>
                @include('threads._thread_tab')
            </div>
            <div class="panel-body">
                @include('threads._simple_threads')
                <hr>
            </div>
            <div class="panel-body">
                {{ $threads->links() }}
                @include('threads._threads')
                {{ $threads->links() }}
            </div>
        </div>
    </div>
</div>
@stop
