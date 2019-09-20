@extends('layouts.default')
@section('title', '修改密码资料')
@section('content')
<div class="container-fluid">
    <style media="screen">
    </style>
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>修改 {{ $user->name }} 的密码</h3>
            </div>
            <div class="panel-body">
                    <h4>修改密码</h4>
                @include('shared.errors')
                <form method="POST" action="{{ route('user.update_password') }}">
                        {{ csrf_field() }}
                        @method('PATCH')
                    <div class="form-group">
                        <label for="old-password">旧密码 (必填，用于身份验证)：</label>
                        <input type="password" name="old-password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="password">新密码：</label>
                        <input type="password" name="password" class="form-control">
                        <h6>(密码需包含至少一个大写字母，至少一个小写字母，至少一个数字，至少一个特殊字符)</h6>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">确认新密码：</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <div class="captcha">
                                <span>{!! captcha_img() !!}</span>
                                <button type="button" class="btn btn-success"><i class="fa fa-refresh" id="refresh"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <input id="captcha" type="text" class="form-control" placeholder="输入验证码" name="captcha">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-lg btn-danger sosad-button">更新密码</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
