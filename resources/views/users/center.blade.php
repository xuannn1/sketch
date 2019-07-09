@extends('layouts.default')
@section('title', $user->name."的个人中心")

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading text-center">
                @include('users._user')
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="h3">
                    <ul><a href="#">我的发布<span class="grayout h4">&nbsp;&nbsp;&nbsp;(动态、讨论、评论、赞赏、题头)</span></a></ul>
                    <ul><a href="#">我的文章<span class="grayout h4">&nbsp;&nbsp;&nbsp;(原创小说、同人小说、随笔)</span></a></ul>
                    <ul><a href="#">我的清单<span class="grayout h4">&nbsp;&nbsp;&nbsp;(书单、电影单)</span></a></ul>
                    <ul><a href="#">我的问答<span class="grayout h4">&nbsp;&nbsp;&nbsp;(我的提问、我的回答)</span></a></ul>

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="h3">
                    <ul><a href="#">我的投票</a></ul>
                    <ul><a href="#">我的打赏</a></ul>
                    <ul><a href="{{ route('administrationrecords.self') }}">管理记录</a></ul>
                    <ul><a href="{{ route('quiz.taketest') }}">我要答题</a></ul>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="h3">
                    <ul><a href="#">隐私设置</a></ul>
                    <ul><a href="{{ route('users.edit') }}">编辑信息</a></ul>
                    <ul><a href="{{ route('linkedaccounts.index') }}">切换账户</a></ul>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="h3">
                    <ul><a href="{{ route('about') }}">关于</a></ul>
                    <ul><a href="{{ route('help') }}">帮助</a></ul>
                    <ul><a href="{{ route('contacts') }}">联系我们</a></ul>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
