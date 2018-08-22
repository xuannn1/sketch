@extends('layouts.default')
@section('title', Auth::user()->name.'的消息中心')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-center">
                    <h4>您好&nbsp;<strong>{{Auth::user()->name}}</strong>！</h4>
                    @include('messages._receive_stranger_messages_button')
                    @include('messages._receive_upvote_reminders_button')
                </div>

                <br>
                <ul class="nav nav-pills nav-fill nav-justified">
                    <li role="presentation" class = ""><a href="{{ route('messages.unread') }}">未读</a></li>
                    <li role="presentation"><a href="{{ route('messages.index') }}" class = "active">全部</a></li>
                    <li role="presentation"><a href="{{ route('messages.messagebox') }}">信箱</a></li>
                    <li role="presentation" class="pull-right"><a class="btn sosad-button-ghost grayout" href="{{ route('messages.clear') }}">
                      <i class="fas fa-check"></i>
                      清理未读
                    </a></li>
                </ul>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>公共通知：</h4>
                @include('messages._public_notices')
                @if($public_notices->hasMorePages())
                <div class="text-center">
                    <a href="{{ route('messages.public_notices') }}">查看全部</a>
                </div>
                @endif
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <h4><a href="{{ route('messages.messages') }}">个人信息：</a></h4>
                @include('messages._messages')
                @if($messages->hasMorePages())
                <div class="text-center">
                    <a href="{{ route('messages.messages') }}">查看全部</a>
                </div>
                @endif
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <h4><a href="{{ route('messages.posts') }}">主题跟帖：</a></h4>
                @include('messages._posts')
                @if($posts->hasMorePages())
                <div class="text-center">
                    <a href="{{ route('messages.posts') }}">查看全部</a>
                </div>
                @endif
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <h4><a href="{{ route('messages.replies') }}">回帖讨论：</a></h4>
                @include('messages._replies')
                @if($replies->hasMorePages())
                <div class="text-center">
                    <a href="{{ route('messages.replies') }}">查看全部</a>
                </div>
                @endif
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <h4><a href="{{ route('messages.postcomments') }}">帖子点评：</a></h4>
                @include('messages._postcomments')
                @if($postcomments->hasMorePages())
                <div class="text-center">
                    <a href="{{ route('messages.postcomments') }}">查看全部</a>
                </div>
                @endif
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <h4><a href="{{ route('messages.upvotes') }}">帖子赞赏：</a></h4>
                @include('messages._upvotes')
                @if($upvotes->hasMorePages())
                <div class="text-center">
                    <a href="{{ route('messages.upvotes') }}">查看全部</a>
                </div>
                @endif
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>系统消息：</h4>
                @include('messages._system_reminders')
                @if($system_reminders->hasMorePages())
                <div class="text-center">
                    <a href="{{ route('questions.index', Auth::id()) }}">查看全部</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
