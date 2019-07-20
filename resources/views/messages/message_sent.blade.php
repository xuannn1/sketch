@extends('layouts.default')
@section('title', $user->name.'的站内信箱')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 导航 -->
        <div class="">
            <a href="{{ route('activity.index') }}">消息中心</a>
            /
            <a href="{{ route('message.index') }}">站内信箱</a>
            /
            <a href="{{ route('message.sent') }}">发件箱</a>
        </div>
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h1>{{$user->name}}发送的站内信</h1>
                @include('messages._message_tab')
            </div>
            <div class="panel-body">
                {{ $messages->links() }}
                @include('messages._messages')
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</div>
@stop
