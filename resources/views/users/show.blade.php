@extends('layouts.default')
@section('title', $user->name)

@section('content')
<div class="container-fluid">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        @include('shared.errors')
         <div class="panel-heading text-center">
            @include('users._user')
         </div>
      </div>
      <div class="panel panel-default">
         <div class="panel-body">
            @include('statuses._statuses')
            @if($statuses->hasMorePages())
            <div class="text-center h5">
               <a href="{{ route('user.showstatuses', $user->id) }}">查看全部</a>
            </div>
            @endif
         </div>
      </div>
      <div class="panel panel-default">
         <div class="panel-heading">
            <h4>文章目录</h4>
         </div>
         <div class="panel-body">
            @include('books._books')
            @if($books->hasMorePages())
            <div class="text-center h5">
               <a href="{{ route('user.showbooks', $user->id) }}">查看全部</a>
            </div>
            @endif
         </div>
      </div>
      <div class="panel panel-default">
         <div class="panel-heading">
            <h4>讨论帖目录</h4>
         </div>
         <div class="panel-body">
            @include('threads._threads')
            @if($threads->hasMorePages())
            <div class="text-center h5">
               <a href="{{ route('user.showthreads', $user->id) }}">查看全部</a>
            </div>
            @endif
         </div>
      </div>
      <div class="panel panel-default">
         <div class="panel-heading">
            <h4>长评目录</h4>
         </div>
         <div class="panel-body">
            @include('posts._posts')
            @if($posts->hasMorePages())
            <div class="text-center h5">
               <a href="{{ route('user.showlongcomments', $user->id) }}">查看全部</a>
            </div>
            @endif
         </div>
      </div>
      <div class="panel panel-default">
         <div class="panel-heading">
            <h4>点赞目录</h4>
         </div>
         <div class="panel-body">
            @include('posts._upvotes')
            @if($upvotes->hasMorePages())
            <div class="text-center h5">
               <a href="{{ route('user.showupvotes', $user->id) }}">查看全部</a>
            </div>
            @endif
         </div>
      </div>
   </div>
</div>
@stop
