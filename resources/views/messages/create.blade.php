@extends('layouts.default')
@section('title', '发送私信')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>收信人：<a href="{{route('user.show', $user->id)}}">{{ $user->name }}</a></h4>
            </div>
            <div class="panel-body">
                @if((!$user->isFollowing(Auth::id()))&&(!Auth::user()->admin))
                @if(!$user->receive_messages_from_stranger&&(!$recent_previous_message))
                <p>很抱歉，{{ $user->name }}未关注您，且并不接收陌生人的私信，</p>
                @elseif(Auth::user()->message_limit>0)
                <p>{{ $user->name }}未关注您，您今日的陌生人私信限额还有{{ Auth::user()->message_limit }}条</p>
                @include('messages._create_message_form')
                @else
                <p>很抱歉，{{ $user->name }}未关注您，您今日的陌生人私信限额已用完，请明日签到后再发私信</p>
                @endif
                @else
                @include('messages._create_message_form')
                @endif
            </div>
        </div>
    </div>
</div>
@stop
