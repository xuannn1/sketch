@extends('layouts.default')
@section('title', '搜索结果')
@section('content')
<div class="container">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="panel panel-default">
         <div class="panel-heading lead">搜索主题</div>
         <div class="panel-body">
            @include('threads._simple_threads')
            {{ $simplethreads->links() }}
         </div>
      </div>
   </div>
</div>
@stop
