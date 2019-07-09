@extends('layouts.default')
@section('title', $thread->title )

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 首页／版块／导航 -->
        <div class="">
            <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            &nbsp;/&nbsp;
            <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
            &nbsp;/&nbsp;
            <a href="{{ route('thread.show',$thread->id) }}">{{ $thread->title }}</a>
        </div>
        {{ $posts->links() }}
        @if($posts->currentPage()==1)
            <div class="panel panel-default">
                <div class="panel-body">
                    <!-- 主题介绍部分 -->
                    @include('threads._thread_profile')
                </div>
                <div class="panel-vote">
                    <!-- 对主题进行投票／收藏／赞赏等操作 -->
                    @if(Auth::check())
                    @include('threads._thread_vote')
                    @else
                    <h6 class="display-4">请 <a href="{{ route('login') }}">登录</a> 后参与讨论</h6>
                    @endif
                </div>
            </div>
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
