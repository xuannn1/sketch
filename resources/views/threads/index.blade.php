@extends('layouts.default')
@section('title', '论坛')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>论坛</h3>
                <ul class="nav nav-tabs">
                    @include('threads._discussions_stats')
                </ul>
            </div>
            <div class="panel-body">
                @include('threads._simple_threads')
                {{ $threads->links() }}
                @include('threads._threads')
                {{ $threads->links() }}
            </div>
        </div>
    </div>
</div>
@stop
