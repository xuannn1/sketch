@extends('layouts.default')
@section('title', $thread->title )

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @include('shared.errors')
        @include('threads._site_map')
        @include('threads._thread_profile')
        @if($thread->channel()->type==='book')
        <div class="panel panel-default">
            <div class="panel-body">
                @include('chapters._chapters')
            </div>
        </div>
        @endif
        <div class="h4 text-center">
            <a href=" {{ route('thread.show', $thread->id) }} ">>>进入论坛查看更多评论</a>
        </div>
        @include('threads._posts')
        @if(Auth::check())
        @include('threads._reply')
        @endif
    </div>
</div>
@stop
