@extends('layouts.default')
@section('title', '更新密码')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">更新密码</div>
                <div class="panel-body">
                    @include('shared.errors')
                    <form action="{{ route('password.request') }}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="token" value="{{ $token }}">

                        <h6>如果遇到“密码重置令牌错误”，说明您发送了不止一封重置密码/重激活邮件，而安全起见，新发送的邮件会使旧邮件失效。遇到这种情况，不要惊慌！这说明邮件是<code>可以</code>发送过去的！只要您仔细查找您的邮件箱，找到时间<code>最新</code>的一封信就可以了。如果还不能成功，请您耐心等待一小时后，重新点【一次】重置健。然后选择在您点选重置键之后收到的<code>新鲜邮件</code>进行重置。</h6>

                        <div class="form-group">
                            <label class="col-md-4 control-label">邮箱地址：</label>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">密码：</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">确认密码：</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-danger sosad-button">
                            更新密码
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
