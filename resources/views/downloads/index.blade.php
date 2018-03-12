@extends('layouts.default')
@section('title', '下载页面')
@section('content')
<div class="container-fluid">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="panel panel-default">
         <div class="panel-heading lead">
            <h1>可选下载项</h1>
         </div>
         <div class="panel-body">
            @if($thread->book_id>0)
              书籍下载链接
            @else
              讨论帖下载链接
            @endif
         </div>
      </div>
   </div>
</div>
@stop
