@extends('layouts.default')
@section('title', '全部动态')
@section('content')
<div class="container">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="panel panel-default">
         <div class="panel-heading lead">全部动态</div>
         <div class="panel-body">
            @include('statuses._statuses')
            {{ $statuses->links() }}
         </div>
      </div>
   </div>
</div>
@stop
