@extends('layouts.default')
@section('title', $user->name.'收到的评票')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 导航 -->
        <div class="">
            <a href="{{ route('user.center') }}">个人中心</a>
            &nbsp;/&nbsp;我的评票
        </div>
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h1>{{$user->name}}收到的评票</h1>
                <br>
                @include('votes._vote_tab')
            </div>
            <div class="panel-body">
                {{ $votes->links() }}
                @include('votes._votes')
                {{ $votes->links() }}
            </div>
        </div>
    </div>
</div>
@stop
