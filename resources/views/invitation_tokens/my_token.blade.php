@extends('layouts.default')
@section('title', '我的邀请列表')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>全部邀请码列表</h4>
                <div class="panel-body">
                    @include('invitation_tokens._invitation_tokens')
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>我的被邀请人</h4>
                <div class="panel-body">
                    {{ $users->links() }}
                    @include('users._users')
                    {{ $users->links() }}
                </div>
            </div>
        </div>
        <div class="text-center">
            <h6>你可以创建的邀请码余额为{{$info->token_limit}}个。新建邀请码需支付5火腿/码，使用私人邀请码注册的用户，注册即有2级。</h6>
            @if($info->token_limit>0)
            <form method="POST" action="{{ route('invitation_token.store_my_token') }}" name="store_my_token">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-lg btn-danger sosad-button">新建限时邀请码</button>
                <h6>（如果被邀请人在一月内违反版规，邀请人需负连带责任。）</h6>
            </form>
            @endif
        </div>
    </div>
</div>
@stop
