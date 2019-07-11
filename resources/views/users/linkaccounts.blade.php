@extends('layouts.default')
@section('title', '关联用户')

@section('content')
<div class="container-fluid">
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2>已关联用户</h2>
            </div>
            <div class="panel-body">
                <div class="h3">
                    @foreach($accounts as $account)
                    <div class="linkedaccount{{$account->id}}">
                        <li><a href="{{ route('user.show', $account->id) }}">{{ $account->name }}</a>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" name="button" class="btn btn-danger btn-lg sosad-button-control" onclick="cancellink({{$account->id}})">取消关联{{$account->name}}</button></li>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @if($accounts->count() < Auth::user()->level)
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
