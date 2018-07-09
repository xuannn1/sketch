@extends('layouts.default')
@section('title', '编辑个人资料')
@section('content')
<div class="container">
    <style media="screen">
    </style>
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>编辑个人资料</h5>
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('users.update') }}">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="old-password">旧密码 (必填，用于身份验证)：</label>
                        <input type="password" name="old-password" class="form-control">
                    </div>

                    <p>(以下只需填写需要修改的内容)</p>
                    <div class="form-group">
                        <label for="name">用户名（笔名）：</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }} " disabled>
                    </div>

                    <div class="form-group">
                        <label for="email">邮箱：</label>
                        <input type="text" name="email" class="form-control" value="{{ $user->email }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="password">新密码：</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">确认新密码：</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="introduction">个人介绍：</label>
                        <textarea name="introduction" data-provide="markdown" rows="12" class="form-control">{{$user->introduction}}</textarea>
                    </div>

                    <button type="submit" class="btn btn-danger sosad-button">更新个人资料</button>
                    @if(Auth::user()->user_level>=3)
                    <a href="{{ route('linkedaccounts.create') }}" class="btn btn-danger sosad-button pull-right">关联其他账户</a>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@stop
