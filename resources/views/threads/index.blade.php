@extends('layouts.default')
@section('title', '所有主题贴')
@section('content')
<div class="container-fluid">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 首页／版块／类型 -->
        <div class="panel panel-default">
            <h2 class="sosad-heading">论坛</h2>
            <ul class="nav nav-pills nav-fill nav-justified">
                    @include('threads._discussions_stats')
                </ul>
            <div class="panel-body">
                @include('threads._threads')
                {{ $threads->links() }}
            </div>
        </div>
   </div>
</div>
@stop
