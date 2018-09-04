@extends('layouts.default')
@section('title', '下载页面')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading lead">
                @include('threads._thread_title')
            </div>
            <div class="panel-body">
                <h2>可选下载项：</h2>
                <div class="h4">
                    @if($thread->book_id>0)
                    <div class="">
                        <a href=" {{ route('download.book_noreview_text', $thread->id) }} ">下载脱水书籍（不含评论的正文，按章节顺序排列）</a>
                    </div>
                    @endif
                    <div class="">
                        <a href=" {{ route('download.thread_txt', $thread->id) }} ">下载txt讨论贴（正文加评论，按时间顺序排列）</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
