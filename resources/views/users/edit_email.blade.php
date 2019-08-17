@extends('layouts.default')
@section('title', '修改邮箱资料')
@section('content')
<div class="container-fluid">
    <style media="screen">
    </style>
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>修改邮箱资料</h3>
            </div>
            <div class="panel-body">

                    <div class="form-group">
                        <div class="form-group">
                            <label for="email">当前邮箱：</label>
                            <input type="text" name="email" class="form-control" value="{{ $user->email }}" disabled>
                        </div>
                    </div>
                    <hr>
                    <h4>修改邮箱</h4>
                    <h6>（你本周已修改{{ $previous_history_counts }}次邮箱，一周最多只能修改1次）</h6>
                @include('shared.errors')
                <form method="POST" action="{{ route('user.update_email') }}">
                        {{ csrf_field() }}
                        @method('PATCH')
                    <div class="form-group">
                        <label for="old-password">旧密码 (必填，用于身份验证)：</label>
                        <input type="password" name="old-password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="email">新邮箱：</label>
                        <input type="text" name="email" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="email_confirmation">确认新邮箱：</label>
                        <input type="text" name="email_confirmation" class="form-control">
                        <h6><span style="color:#d66666">友情提醒，请【仔细】检查邮箱输入情况，确认邮箱无误！</span>输入错误的邮箱将无法激活自己的账户，也无法找回自己的账户。<br>虽然很多用户都说自己的输入“绝对没有问题”，然而实践表明——几乎——所有的登陆问题都是邮箱输入错误导致……<br>为了确保验证邮件正常送达，请务必使用个人<code>目前常用、可用的</code>邮箱地址。</h6>
                        <br>
                        <h6>由于邮件业务存在跨国跨墙情况，有时会出现运营商截停/延误邮件的情况，请给邮箱预留一天以上的空余时间接收，不要连续重复发件。<br>如果确认邮箱正确仍不能收到邮件，请酌情更换自己常用、可用的其他邮箱。<br>qq邮箱容易拒收邮件，请慎重使用。</h6>
                    </div>

                    <button type="submit" class="btn btn-md btn-danger sosad-button">更新邮箱</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
