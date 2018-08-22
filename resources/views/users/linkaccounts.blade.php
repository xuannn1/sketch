@extends('layouts.default')
@section('title', '关联用户')

@section('content')
<div class="container">
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading h5">已关联用户</div>
            <div class="panel-body">
                <div>
                    <?php $linkedaccounts = Auth::user()->linkedaccounts(); ?>
                    @foreach($linkedaccounts as $account)
                    <div class="linkedaccount{{$account->id}}">
                        <a href="{{ route('user.show', $account->id) }}">{{ $account->name }}</a>&nbsp;<button type="button" name="button" class="btn-xs sosad-button-tag" onclick="cancellink({{$account->id}})">
                          <i class="fas fa-unlink"></i>
                          取消关联
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading h5">关联其他用户</div>
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
                    <button type="submit" class="sosad-button-auth">关联此账户</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
