@extends('layouts.default')
@section('title', '登录')

@section('content')
   <div class="container">
      <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
          <div class="panel-heading lead">登录</div>
          <div class="panel-body">
            @include('shared.errors')
            <form method="POST" action="{{ route('login') }}">
              {{ csrf_field() }}
              <div class="form-group">
                <label for="email">邮箱：</label>
                <input type="text" name="email" class="form-control" value="{{ old('email') }}">
              </div>
              <div class="form-group">
                <label for="password">密码：</label>
                <input type="password" name="password" class="form-control" value="{{ old('password') }}">
              </div>
              <div class="checkbox">
                <label><input type="checkbox" name="remember">记住我</label>
              </div>
              <button type="submit" class="btn btn-danger sosad-button">登录</button>
              <a href="{{ route('register') }}" class="btn btn-success sosad-button">我要注册</a>
            </form>
            <br>
            <div class="">
                <u><a href="{{ route('password.request') }}">忘记密码/重新激活</a></u>
            </div>
            <hr>
          </div>
        </div>
      </div>
   </div>

@stop
