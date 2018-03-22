@extends('layouts.default')
@section('title', '所有主题')
@section('content')
<div class="container">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 首页／版块／类型 -->
        <div class="site-map">
            <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>&nbsp;/&nbsp;全部主题
        </div>
        <div class="panel panel-default">
            <div class="panel-heading lead">全部主题</div>
            <div class="panel-body">
                @include('threads._threads')
                {{ $threads->links() }}
            </div>
        </div>
   </div>
</div>
@stop
