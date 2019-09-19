@extends('layouts.default')
@section('title', '赞助我们')
@section('content')

<div class="container-fluid">
    <div class="col-sm-offset-2 col-sm-8">
        <!-- 首页／版块／导航 -->
        <div class="">
            <a type="btn btn-lg btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            /
            <a href="{{ route('donation.donate') }}">赞助我们</a>
        </div>
        <h1>历史赞助信息</h1>
        <div class="panel panel-default">
            <div class="panel-body">
                <div>
                    @include('donations._public_donation_records')
                    {{ $donation_records->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
