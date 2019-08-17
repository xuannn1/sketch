@extends('layouts.default')
@section('title', '切换关联用户')

@section('content')
<div class="container-fluid">
    <div class="col-sm-offset-3 col-sm-6">
        @if($branchaccounts->count()>0)
        <div class="panel panel-default">
            <div class="panel-heading font-2">
                本账户已关联账户
            </div>
            <div class="panel-body">
                @foreach($branchaccounts as $account)
                <h3><a class="btn btn-lg btn-primary sosad-button-control" href="{{ route('linkedaccounts.switch', $account->id) }}">切换至：{{ $account->name }}</a></h3>
                @endforeach
            </div>
        </div>
        @endif
        @if($user->level>=3||$user->isAdmin()||$user->isEditor())
        <a href="{{ route('linkedaccounts.create') }}" class="btn btn-lg btn-info sosad-button ">管理关联账户</a>
        @else
        <h4>你的等级不够，暂时不能关联马甲账户</h4>
        @endif
        <h6 class="grayout">友情提醒，关联账户是单向关联关系</h6>
    </div>
</div>
@stop
