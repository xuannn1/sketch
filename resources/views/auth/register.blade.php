@extends('layouts.default')
@section('title', '注册')
@section('content')
<div class="container">
    <style media="screen">
    </style>
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading lead">
                <h1>注册</h1>
                <h5 class="text-center">成为废文网的一条咸鱼吧～</h5>
            </div>
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
                        <h6 class="grayout">（请输入您的可用邮箱，便于未来找回密码。）</h6>
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
                        <h6 class="grayout">（邀请码可以从朋友处得到，可以从站内公用邀请码楼得到，也可以关注站子答疑总微博 @废文网大内总管 ，私信留言关键词“邀请码”自动获取～）</h6>
                        <input type="text" name="invitation_token" class="form-control" value="{{ old('invitation_token') }}">
                    </div>
                    <div class="panel panel-default text-center">
                        <div class="panel-title">
                            <h4>注册协议</h4>
                        </div>
                        <div >
                            <p>丧病之家，您的精神墓园</p>
                            <p>比欲哭无泪更加down，不抑郁不要钱</p>
                            <p>本站<u><em><b>禁抄袭，禁人身攻击，禁人肉，禁恋童</b></em></u></p>
                            <p>请<u><em><b>不要发布侵犯他人版权的文字</b></em></u></p>
                            <p>请确保您已<u><em><b>年满十八岁</b></em></u></p>
                            <p>祝您玩得愉快</p>
                            <br>
                        </div>
                        <div class="panel-footer text-center">
                            <input type="checkbox" name="have_read_policy" value=true>
                            <span>我已阅读并同意注册协议</span>&nbsp;<u><a href="{{'about'}}">更多内容</a></u>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-lg btn-danger sosad-button">一键注册</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
