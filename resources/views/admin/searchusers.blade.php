@extends('layouts.default')
@section('title', '搜索用户结果')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>搜索用户结果</h4>
            </div>

            <div class="panel-body">
                @include('shared.errors')
                @foreach($users as $user) <b></b>
                <div class="">
                    <p><b>用户id：</b>{{$user->id}}</p>
                    <p><b>用户名：</b>{{$user->name}}</p>
                    <p><b>email：</b>{{$user->email}}</p>
                    <p><b>创建时间：</b>{{$user->created_at}}</p>
                    <p><b>最后登陆时间：</b>{{$user->last_login}}</p>
                </div>
                @endforeach
                {{$users->links()}}
            </div>
        </div>
    </div>
</div>
@stop
