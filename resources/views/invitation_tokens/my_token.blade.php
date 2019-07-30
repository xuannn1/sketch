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
            <h6>您可以创建的邀请码余额为{{$info->token_limit}}个。</h6>
            @if($info->token_limit>0)
            <form method="POST" action="{{ route('invitation_token.store_my_token') }}" name="store_my_token">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-lg btn-danger sosad-button">新建邀请码</button>
            </form>
            @endif
        </div>
    </div>
</div>
@stop
