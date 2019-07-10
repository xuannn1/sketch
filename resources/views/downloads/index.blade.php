@extends('layouts.default')
@section('title', '下载页面')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading lead">
                <a href="{{ route('thread.show_profile',$thread->id) }}" class="h1">{{ $thread->title }}</a>
            </div>
            <div class="panel-body">
                <h2>可选下载项：</h2>
                <div class="h4">
                    @if($thread->download_as_book)
                    <div class="">
                        页面调整中，暂停服务，请稍后
                    </div>
                    @endif
                    @if($thread->download_as_thread)
                    <div class="">
                        页面调整中，暂停服务，请稍后
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop
