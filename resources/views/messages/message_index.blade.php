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
        </div>
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h1>{{$user->name}}的站内信箱</h1>
                <div class="font-3">
                    本页总计{{$messagebox_reminders}}条未读信息：
                </div>
                <div class="{{ConstantObjects::system_variable()->latest_public_notice_id-$user->public_notice_id>0? 'unread-reminders':''}}">
                    <a href="{{route('message.public_notice')}}" class="font-5">
                        {{ConstantObjects::system_variable()->latest_public_notice_id-$user->public_notice_id}}条新的公共通知&nbsp;&nbsp;>>全部公共通知
                    </a>
                </div>
                <div class="{{$info->administration_reminders>0? 'unread-reminders':''}}">
                    <a href="{{route('administrationrecords', ['user_id'=>$user->id])}}" class="font-5">
                        {{$info->administration_reminders}}条新的管理信息&nbsp;&nbsp;>>我的管理记录
                    </a>
                </div>
                <div class="{{$message_reminders>0? 'unread-reminders':''}}">
                     {{$message_reminders}}条未读私信
                </div>
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
