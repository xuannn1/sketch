@extends('layouts.default')
@section('title', ' 重置密码')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading h5">
                    向你的邮箱发送 <strong>重置密码</strong> 邮件
                </div>
                <div class="panel-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    <form action="{{ route('password.email') }}" method="POST">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-4 control-label">邮箱地址:</label>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="form-group col-md-4">
                                <div class="captcha">
                                    <span>{!! captcha_img() !!}</span>
                                    <button type="button" class="btn btn-success"><i class="fa fa-refresh" id="refresh"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="form-group col-md-4">
                                <input id="captcha" type="text" class="form-control" placeholder="输入验证码" name="captcha">
                            </div>
                        </div>
                        <label for="" class="h6">(有时重置邮件会发送至个人垃圾箱，请注意查看。请注意,<code>不要重复点击</code>“重置”。重复发送邮件会被邮箱系统判断为<code>垃圾邮件</code>拒收。)如果按钮无法打开或无法激活，请尝试清理浏览器缓存，或复制链接到其他浏览器内打开完成激活。如果遇到“密码重置令牌错误”，说明你发送了不止一封重置密码/重激活邮件，而安全起见，新发送的邮件会使旧邮件失效，请寻找最新邮件，或重新重置。</label>

                        <button type="submit" class="btn btn-lg btn-danger sosad-button">重置</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
