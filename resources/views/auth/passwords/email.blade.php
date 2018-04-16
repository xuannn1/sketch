@extends('layouts.default')
@section('title', ' 重置密码/重新激活')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="panel panel-default">
        <div class="panel-heading h5">
          向您的邮箱发送 <strong>重置密码/重新激活</strong> 邮件
        </div>
        <div class="panel-body">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
          <form action="{{ route('password.email') }}" method="POST">
            {{ csrf_field() }}

            <div class="form-group">
              <label class="col-md-4 control-label">邮箱地址:</label>
              <div class="col-md-6">
                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
              </div>
            </div>

            <button type="submit" class="btn btn-danger sosad-button">重置</button>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
