@extends('layouts.default')
@section('title', '搜索结果')
@section('content')
<div class="container">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="panel panel-default">
         <div class="panel-heading lead">搜索用户</div>
         <div class="panel-body">
            @include('users._users')
            {{ $users->links() }}
         </div>
         <div class="panel-heading lead">搜索主题</div>
         <div class="panel-body">
            @include('threads._threads')
            {{ $threads->links() }}
         </div>
         <div class="panel-heading lead">搜索帖子</div>
         <div class="panel-body">
            @include('posts._posts')
            {{ $posts->links() }}
         </div>
      </div>
   </div>
</div>
@stop
