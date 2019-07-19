@extends('layouts.default')
@section('title', '公共通知')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 导航 -->
        <div class="">
            <a href="{{ route('activity.index') }}">消息中心</a>
            &nbsp;/&nbsp;
            <a href="{{ route('message.index') }}">站内信箱</a>
            &nbsp;/&nbsp;
            往期站内公共信息
        </div>
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h1>往期站内公共信息</h1>
            </div>
            <div class="panel-body">
                @include('messages._public_notices')
            </div>
        </div>
    </div>
</div>
@stop
