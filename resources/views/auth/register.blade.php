@extends('layouts.default')
@section('title', '注册')
@section('content')
<div class="container">
    <style media="screen">
    </style>
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading lead">注册</div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">用户名（笔名）：</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label for="email">邮箱：</label>
                        <input type="text" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label for="password">密码：</label>
                        <input type="password" name="password" class="form-control" value="{{ old('password') }}">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">确认密码：</label>
                        <input type="password" name="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}">
                    </div>
                    <div class="form-group">
                        <label for="invitation_token">邀请码：</label>
                        <input type="text" name="invitation_token" class="form-control" value="{{ old('invitation_token') }}">
                    </div>
                    <div class="panel panel-default text-center">
                        <div class="panel-title">
                            <h4>注册协议</h4>
                        </div>
                        <div >
                            <p>丧病之家，您的精神墓园</p>
                            <p>比欲哭无泪更加down，不抑郁不要钱</p>
                            <p>本站禁抄袭，禁人身攻击，禁人肉，禁恋童</p>
                            <p>请不要发布侵犯他人版权的文字</p>
                            <p>请确保您已年满十八岁</p>
                            <p>祝您玩得愉快</p>
                            <br>
                        </div>
                        <div class="panel-footer text-center">
                            <input type="checkbox" name="have_read_policy" value=true>
                            <span>我已阅读并同意注册协议</span>&nbsp;<u><a href="{{'about'}}">更多内容</a></u>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger sosad-button">注册</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
