@extends('layouts.default')
@section('title', '编辑个人资料')
@section('content')
<div class="container-fluid">
    <style media="screen">
    </style>
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>编辑资料</h1>
            </div>
            <br>
            <div class="panel-body">

                <div class="form-group">
                    <label for="name">用户名（笔名）：</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }} " disabled>
                </div>

                <div class="form-group">
                    <label for="old-email">邮箱：</label>
                    <h6 class="grayout"><a href="{{route('thread.show', 14361)}}">（戳我看《万能的邮箱激活验证/修改/使用指南》）</a> </h6>
                    <input type="text" name="email" class="form-control" value="{{ $user->email }}" disabled>
                    @if($email_confirmed)
                    <p><span class="glyphicon glyphicon-ok">该邮箱已验证</span></p>
                    @else
                    <p><span class="glyphicon glyphicon-remove"></span><span style="color:#d66666">该邮箱尚未验证，请尽快验证。未验证邮箱的账户会限制一部分使用功能。</span></p>
                    <h4>验证邮箱/激活账户</h4>

                    <a href="{{ route('email_confirmation.send') }}" class="btn btn-lg btn-danger sosad-button">向该邮箱发送验证邮件</a>

                    @if($last_email_sent)
                    <h6>最新验证邮件发送于 {{ $last_email_sent->diffForHumans() }}</h6>
                    <h6 class="grayout">请仔细检查个人收件箱/垃圾箱，修改自己的垃圾邮件设置，再重发邮件。<br>友情提醒，新邮件会取消所有旧邮件的验证资格（显示为“令牌不存在”），且重复发件容易被收件箱拒收，因此请您等待恰当时间间隔再行重发邮件。<br>如果一直无法收到验证邮件，建议彻底更换邮箱。<br>qq邮箱容易拒收邮件，请慎重使用。</h6>
                    @endif
                    @endif
                </div>

                <a href="{{ route('user.edit_email') }}" class="btn btn-lg btn-danger sosad-button">修改邮箱</a>&nbsp;&nbsp;

                <a href="{{ route('user.edit_password') }}" class="btn btn-lg btn-danger sosad-button pull-right">修改密码</a>&nbsp;&nbsp;
                <br>
                <br>
            </div>
        </div>
    </div>
</div>
@stop
