@extends('layouts.default')
@section('title', Auth::user()->name.'的公共通知')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5><a href="{{ route('messages.index') }}">全部消息</a>&nbsp;/&nbsp;<strong>{{Auth::user()->name}}</strong>&nbsp;收到的公共通知</h5>
            </div>
            <div class="panel-body">
                @include('messages._public_notices')
                {{ $public_notices->links() }}
            </div>
        </div>
    </div>
</div>
@stop
