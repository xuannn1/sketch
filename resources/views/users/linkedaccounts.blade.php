@extends('layouts.default')
@section('title', '切换关联用户')

@section('content')
<div class="container-fluid">
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading font-1">
                已关联用户
            </div>
            <div class="panel-body">
                @foreach($accounts as $account)
                <h3><a href="{{ route('linkedaccounts.switch', $account->id) }}">切换：{{ $account->name }}</a></h3>
                @endforeach
            </div>
        </div>
        @if(Auth::user()->level>=3)
        <a href="{{ route('linkedaccounts.create') }}" class="btn btn-lg btn-info sosad-button ">管理关联账户</a>
        @else
        <h4>您的等级不够，暂时不能关联马甲账户</h4>
        @endif
    </div>
</div>
@stop
