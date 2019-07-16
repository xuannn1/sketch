@extends('layouts.default')
@section('title', $user->name.'的消息中心')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 导航 -->
        <div class="">
            <a href="{{ route('activity.index') }}">消息中心</a>
        </div>
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h1>{{$user->name}}的消息中心</h1>
                总计{{$unread_reminders}}条未读回复
                @include('messages._new_upvotes')
                @include('messages._new_rewards')
                <br>
                @include('messages._message_tab')
            </div>
            <div class="panel-body">
                {{ $activities->links() }}
                @include('messages._simple_posts')
                {{ $activities->links() }}
            </div>
        </div>
    </div>
</div>
@stop
