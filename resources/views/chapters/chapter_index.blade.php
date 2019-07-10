@extends('layouts.default')
@section('title', $thread->title.'-目录列表' )

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="h3">
            <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            &nbsp;/&nbsp;
            <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
            &nbsp;/&nbsp;
            <a href="{{ route('thread.show_profile',$thread->id) }}">{{ $thread->title }}</a>&nbsp;/&nbsp;
            <a href="{{ route('thread.chapter_index',$thread->id) }}">章节目录</a>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1> <a href="{{ route('thread.show_profile', $thread->id) }}">{{ $thread->title }}</a> </h1>
                <h5>{{ $thread->brief }}</h5>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                @include('chapters._chapters')
            </div>
        </div>
    </div>
</div>

@stop
