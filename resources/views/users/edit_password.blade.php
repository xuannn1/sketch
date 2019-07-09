@extends('layouts.default')
@section('title', '修改密码资料')
@section('content')
<div class="container-fluid">
    <style media="screen">
    </style>
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>修改 {{ $user->name }} 的密码</h3>
            </div>
            <div class="panel-body">
                    <h4>修改密码</h4>
                @include('shared.errors')
                <form method="POST" action="{{ route('users.update_password') }}">
                        {{ csrf_field() }}
                    <div class="form-group">
                        <label for="old-password">旧密码 (必填，用于身份验证)：</label>
                        <input type="password" name="old-password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="password">新密码：</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">确认新密码：</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-danger sosad-button">更新密码</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
