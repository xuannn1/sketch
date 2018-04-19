@extends('layouts.default')
@section('title', '所有用户')
@section('content')
<div class="container">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="panel panel-default">
         <div class="panel-heading lead">全部用户</div>
         <div class="panel-body">
            {{ $users->links() }}
            @include('users._users')
            {{ $users->links() }}
         </div>
      </div>
   </div>
</div>
@stop
