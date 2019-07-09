@extends('layouts.default')
@section('title', '切换关联用户')

@section('content')
<div class="container-fluid">
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading lead">已关联用户</div>
            <div class="panel-body">
                @foreach($accounts as $account)
                <h3><a href="{{ route('linkedaccounts.switch', $account->id) }}">切换：{{ $account->name }}</a></h3>
                @endforeach
            </div>
        </div>
        @if(Auth::user()->user_level>=3)
        <a href="{{ route('linkedaccounts.create') }}" class="btn btn-info sosad-button ">关联其他账户</a>
        @else
        <h4>您的等级不够，暂时不能关联马甲账户</h4>
        @endif
    </div>
</div>
@stop
