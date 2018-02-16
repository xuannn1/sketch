@extends('layouts.default')
@section('title', '长评列表')

@section('content')
<div class="container">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="panel panel-default">
         <div class="panel-heading lead">长评列表</div>
         <div class="panel-body">
            {{$posts->links()}}
            @include('posts._posts')
            {{$posts->links()}}
         </div>
      </div>
   </div>
</div>
@stop
