@extends('layouts.default')
@section('title', Auth::user()->name.'收到的私信')

@section('content')
<div class="container-fluid">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="panel panel-default">
         <div class="panel-heading text-center">
           <h5><a href="{{ route('messages.index') }}">全部消息</a>&nbsp;/&nbsp;<a href="{{ route('messages.messagebox') }}">信箱</a>&nbsp;/&nbsp;<strong>{{Auth::user()->name}}</strong>&nbsp;发出的私信</h5>
         </div>
         <div class="panel-body">
            @include('messages._messages_sent')
            {{ $messages_sent->links() }}
         </div>
      </div>
   </div>
</div>
@stop
