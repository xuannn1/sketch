@extends('layouts.default')
@section('title', '登录')

@section('content')
   <div class="container">
      <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
          <div class="panel-heading h2">登录</div>
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
                <input type="checkbox" name="remember" id="checkbox-input">
                <label for="checkbox-input" class="input-helper input-helper--checkbox">记住我</label>
                <p class="small" style="display:inline;float:right"><a href="{{ route('password.request') }}" style="opacity:0.6;border:none">忘记密码/重新激活</a></p>
              </div>
              <button type="submit" class="btn btn-danger sosad-button-auth">登录</button>
            </form>
            <hr>
            <p>还没账号？ <a href="{{ route('register') }}">现在注册</a>！</p>
          </div>
        </div>
      </div>
   </div>

@stop
