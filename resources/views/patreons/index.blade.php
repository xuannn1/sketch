@extends('layouts.default')
@section('title', '我的赞助中心')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>我的赞助者身份</h1>
            </div>
            @if($patreon)
            <div class="panel-body">
                <div class="form-group">
                    <label for="email">patreon账户邮箱：</label>
                    <input type="text" name="email" class="form-control" value="{{ $patreon->patreon_email }}" disabled>
                </div>
                @if($patreon->is_approved)
                <p><span class="glyphicon glyphicon-ok">Patreon赞助者信息已验证</span></p>
                @else
                <p><span class="glyphicon question-sign">Patreon赞助者信息验证中</span></p>
                @endif
                <a href="#">删除我的patreon身份信息</a>
            </div>
            @else
            <a href="#">提交我的patreon身份信息</a>
            @endif
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>我的赞助记录</h4>
                <div class="panel-body">
                    @include('patreons._patreon_records')
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>全部福利码列表</h4>
                <div class="panel-body">
                    @include('patreons._patreon_tokens')
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>我曾发放福利的人</h4>
                <div class="panel-body">
                    {{ $users->links() }}
                    @include('users._users')
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
