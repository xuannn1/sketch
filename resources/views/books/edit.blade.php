@extends('layouts.default')
@section('title', '修改书籍')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @include('shared.errors')
        <!-- 首页／版块／导航 -->
        <div class="">
            <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            /
            <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
            /
            <a href="{{ route('thread.show_profile',$thread->id) }}">{{ $thread->title }}</a>/修改书籍
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>{{$thread->title}}</h1>
                <h4>{{$thread->brief}}</h4>
            </div>
            <div class="panel-body text-center">
                <a href="{{route('books.edit_profile', $thread->id)}}" class="btn btn-md btn-info btn-block sosad-button">
                    编辑文案/设置
                    <h5>（修改标题/简介/文案/显示设置/下载设置）</h5>
                </a>
                <hr>
                <a href="{{route('books.edit_tag', $thread->id)}}" class="btn btn-md btn-info btn-block sosad-button">
                    编辑标签
                <h5>（修改进度/篇幅/时代等信息）</h5>
                </a>
                @if($thread->channel_id==2)
                <hr>
                <a href="{{route('books.edit_tongren', $thread->id)}}" class="btn btn-md btn-info btn-block sosad-button">
                    编辑同人信息
                <h5>（修改同人原著/同人CP）</h5>
                </a>
                @endif
                <hr>
                <a href="#" class="btn btn-md btn-info btn-block sosad-button">
                    调整章节顺序（待做）
                </a>
            </div>
        </div>
    </div>
</div>
@stop
