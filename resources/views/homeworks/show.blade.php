@extends('layouts.default')
@section('title', '第'.$homework->id.'次作业')
@section('content')
<div class="container">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="panel panel-default">
         <div class="panel-heading lead">作业详情</div>
         <div class="panel-body">
            @include('homeworks._homework_profile')
         </div>
      </div>
   </div>
</div>
@stop
