@extends('layouts.default')
@section('title', $thread->title )

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @include('shared.errors')
        @include('threads._site_map')
        @include('threads._thread_profile')
        <div class="h4 text-center">
            以下显示高赞评论
            <a href=" {{ route('thread.show', $thread->id) }} " class="pull-right">>>进入论坛</a>
        </div>
        @include('threads._posts')
        @if(Auth::check())
        @include('threads._reply')
        @endif
    </div>
</div>
@stop
