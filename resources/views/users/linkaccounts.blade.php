@extends('layouts.default')
@section('title', '关联用户')

@section('content')
<div class="container">
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading lead">已关联用户</div>
            <div class="panel-body">
                <div class="h5">
                    <?php $linkedaccounts = Auth::user()->linkedaccounts(); ?>
                    @foreach($linkedaccounts as $account)
                    <div class="linkedaccount{{$account->id}}">
                        <li><a href="{{ route('user.show', $account->id) }}">{{ $account->name }}</a>&nbsp;<button type="button" name="button" class="btn btn-danger btn-xs sosad-button-control" onclick="cancellink({{$account->id}})">取消关联</button></li>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading lead">关联其他用户</div>
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
    </div>
</div>
@stop
