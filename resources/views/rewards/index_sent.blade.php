@extends('layouts.default')
@section('title', $user->name.'给出的打赏')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 导航 -->
        <div class="">
            <a href="{{ route('user.center') }}">个人中心</a>
            /我的打赏
        </div>
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h1>{{$user->name}}给出的打赏</h1>
                <h6>（“删除打赏”不会返回虚拟物，只是消除打赏记录）</h6>
                <br>
                @include('rewards._reward_tab')
            </div>
            <div class="panel-body">
                {{ $rewards->links() }}
                @include('rewards._rewards')
                {{ $rewards->links() }}
            </div>
        </div>
    </div>
</div>
@stop
