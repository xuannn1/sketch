@extends('layouts.default')
@section('title', '和'.$speaker->name.'的对话')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 导航 -->
        <div class="">
            <a href="{{ route('activity.index') }}">消息中心</a>
            &nbsp;/&nbsp;
            <a href="{{ route('message.index') }}">站内信箱</a>
            &nbsp;/&nbsp;
            和{{$speaker->name}}的对话
        </div>
        @include('shared.errors')
        <div class="panel panel-default">
            <h1>和{{$speaker->name}}的对话</h1>

            <div class="panel-body">
                {{ $messages->links() }}
                @include('messages._messages')
                {{ $messages->links() }}
                <br>
                @if((!$speaker->isFollowing($user->id))&&(!$user->isAdmin())&&(!$user->isEditor()))
                   @if($speaker_info->no_stranger_msg&&(!$recent_previous_message))
                   <p>很抱歉，{{ $speaker->name }}未关注您，且并不接收陌生人的私信，</p>
                   @elseif($info->message_limit>0)
                   <p>{{ $speaker->name }}未关注您，您今日的陌生人私信限额还有{{ $info->message_limit }}条</p>
                   @include('messages._create_message_form')
                   @else
                   <p>很抱歉，{{ $speaker->name }}未关注您，您今日的陌生人私信限额已用完，请明日签到后再发私信</p>
                   @endif
                @else
                    @include('messages._create_message_form')
                @endif
            </div>
        </div>
    </div>
</div>
@stop
