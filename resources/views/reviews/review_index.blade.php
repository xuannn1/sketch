@extends('layouts.default')
@section('title', $thread->title.'-评论目录' )

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="">
            <a type="btn btn-lg btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            /
            <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
            /
            <a href="{{ route('thread.show_profile',$thread->id) }}">{{ $thread->title }}</a>/
            <a href="{{ route('thread.review_index',$thread->id) }}">评论目录</a>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1><a href="{{ route('thread.show_profile', $thread->id) }}">{{ $thread->title }}</a> </h1>
                <h4>{{ $thread->brief }}</h4>
            </div>
        </div>
        @include('reviews._reviews')
    </div>
</div>

@stop
