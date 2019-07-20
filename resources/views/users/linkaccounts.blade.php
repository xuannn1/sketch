@extends('layouts.default')
@section('title', '关联用户')

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
                <div class="linkedaccount{{$user->id}}-{{$account->id}}">
                    <li><a href="{{ route('user.show', $account->id) }}">{{ $account->name }}</a>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" name="button" class="btn btn-danger btn-md sosad-button-control" onclick="cancellink({{$user->id}}, {{$account->id}})">取消关联{{$account->name}}</button></li>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @if($masteraccounts->count()>0)
        <div class="panel panel-default">
            <div class="panel-heading font-2">
                本账户被以下账户关联
            </div>
            <div class="panel-body">
                @foreach($masteraccounts as $account)
                <div class="linkedaccount{{$account->id}}-{{$user->id}}">
                    <li><a href="{{ route('user.show', $account->id) }}">{{ $account->name }}</a>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" name="button" class="btn btn-danger btn-md sosad-button-control" onclick="cancellink({{$account->id}}, {{$user->id}})">取消{{$account->name}}对我的关联</button></li>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($branchaccounts->count() < $user->level-3)
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2>关联其他用户</h2>
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{route('linkedaccounts.store')}}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="email">邮箱：</label>
                        <input type="text" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="form-group">
                        <label for="password">密码：</label>
                        <input type="password" name="password" class="form-control" value="{{ old('password') }}">
                    </div>
                    <button type="submit" class="btn btn-danger sosad-button">关联此账户</button>
                </form>
            </div>
        </div>
        @else
        <h4>您的等级不足，不能增添新的关联账户</h4>
        @endif
    </div>
</div>
@stop
