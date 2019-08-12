@extends('layouts.default')
@section('title', '我的福利码')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <a href="{{route('donation.mydonations')}}">福利中心</a>&nbsp;/&nbsp;<a href="{{route('donation.my_reward_tokens')}}">全部福利码</a>
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h1>「{{$user->name}}」创建的福利码列表</h1>
            </div>
            <div class="panel-body">
                <?php $tokens = $reward_tokens ?>
                @include('donations._reward_tokens')
            </div>
        </div>
    </div>
</div>
@stop
