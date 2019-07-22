@extends('layouts.default')
@section('title', $thread->title.'-目录列表' )

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="">
            <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            /
            <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
            /
            <a href="{{ route('thread.show_profile',$thread->id) }}">{{ $thread->title }}</a>/
            <a href="{{ route('thread.chapter_index',$thread->id) }}">章节目录</a>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="font-1">
                    <a href="{{ route('thread.show_profile', $thread->id) }}">{{ $thread->title }}</a>
                </div>
                <div class="">
                    <span class="font-4">{{ $thread->brief }}</span>
                    @if(Auth::check()&&Auth::id()===$thread->user_id)
                    <a href="{{route('books.edit_chapter_index', $thread->id)}}" class="btn btn-md btn-info sosad-button-control pull-right">
                        调整章节顺序
                    </a>
                    @endif
                </div>
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
