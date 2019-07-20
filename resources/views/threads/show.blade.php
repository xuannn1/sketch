@extends('layouts.default')
@section('title', $thread->title )

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @include('shared.errors')
        @include('threads._site_map')
        {{ $posts->links() }}
        @if($show_config['show_profile'])
            @include('threads._thread_profile')
        @endif
        @include('threads._post_selector')
        @if($show_config['show_selected'])
            @include('threads._post_selected')
        @endif
        <!-- 展示该主题下每一个帖子 -->
        @include('threads._posts')
        {{ $posts->links() }}
        <!-- 回复输入框 -->
        @if(Auth::check())
        @include('threads._reply')
        @endif
    </div>
</div>
@stop
