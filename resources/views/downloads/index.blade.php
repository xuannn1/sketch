@extends('layouts.default')
@section('title', '下载页面')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h4>
                @include('threads._thread_title')
              </h4>
            </div>
            <div class="panel-body">
                <!-- <h2>下载</h2> -->
                @if($thread->book_id>0)
                <div class="margin5">
                  <a href=" {{ route('download.book_noreview_text', $thread->id) }} " class="sosad-button-post">
                    <i class="fas fa-download"></i>
                    下载书籍
                  </a>
                </div>
                <br>
                @endif
                <div class="">
                  <a href=" {{ route('download.thread_txt', $thread->id) }} " class="sosad-button-post">
                    <i class="fas fa-download"></i>
                    下载txt讨论贴
                  </a>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
@stop
