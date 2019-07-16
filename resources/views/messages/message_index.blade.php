@extends('layouts.default')
@section('title', $user->name.'的站内信箱')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 导航 -->
        <div class="">
            <a href="{{ route('activity.index') }}">消息中心</a>
            &nbsp;/&nbsp;
            <a href="{{ route('message.index') }}">站内信箱</a>
        </div>
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h1>{{$user->name}}的站内信箱</h1>
                总计{{$message_reminders}}条未读信息
                @include('messages._new_public_notices')
                @include('messages._new_administrations')
                <br>
                @include('messages._message_tab')
            </div>
            <div class="panel-body">
                @include('messages._public_notices')
                {{ $messages->links() }}
                @include('messages._messages')
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</div>
@stop
