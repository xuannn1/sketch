@extends('layouts.default')
@section('title', $user->name."的个人中心")

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                @include('users._user_stat')
            </div>
            <div class="">
                <div class="row text-center">
                    <div class="col-xs-4">
                        <a href="{{ route('user.show', $user->id) }}" class="btn btn-info btn-lg sosad-button-control">查看个人主页</a>
                    </div>
                    <div class="col-xs-4">
                        <a href="{{ route('user.edit_introduction') }}" class="btn btn-info btn-lg sosad-button-control">修改个人介绍</a>
                    </div>
                    <div class="col-xs-4">
                        <a href="" class="btn btn-info btn-lg sosad-button-control">佩戴头衔</a>
                    </div>
                </div>
                <br>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="h3">
                    <ul><a href="#">我的书籍</a></ul>
                    <ul><a href="#">我的讨论帖</a></ul>
                    <ul><a href="#">我的清单</a></ul>
                    <ul><a href="#">我的问题箱</a></ul>

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="h3">
                    <ul><a href="#">我的题头</a></ul>
                    <ul><a href="#">我的投票</a></ul>
                    <ul><a href="#">我的打赏</a></ul>
                    <ul><a href="{{ route('administrationrecords', ['user_id'=> $user->id]) }}">我的管理记录</a></ul>
                    <ul><a href="{{ route('quiz.taketest') }}">我要答题</a></ul>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="h3">
                    <ul><a href="#">隐私设置</a></ul>
                    <ul><a href="{{ route('users.edit') }}">编辑资料</a></ul>
                    <ul><a href="{{ route('linkedaccounts.index') }}">管理马甲账户</a></ul>
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
