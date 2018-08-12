@extends('layouts.default')
@section('title', '所有主题贴')
@section('content')
<div class="container-fluid">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 首页／版块／类型 -->
        <div class="site-map">
            <a href="{{ route('home') }}">
                <span><i class="fa fa-home"></i>&nbsp;首页</span>
            </a>&nbsp;/&nbsp;
            全部主题
        </div>
        <div class="panel panel-default">
            <h2 class="sosad-heading">所有主题贴</h2>
            <div class="panel-body">
                @include('threads._threads')
                {{ $threads->links() }}
            </div>
        </div>
   </div>
</div>
@stop
