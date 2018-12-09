@extends('layouts.default')
@section('title', ' 重置密码/重新激活')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-6 col-sm-offset-3">
      <div class="panel panel-default">
        <!-- <div class="panel-heading">
          向您的邮箱发送 重置密码/重新激活 邮件
        </div> -->
        <div class="panel-body">
          @include('shared.errors')
          <form action="{{ route('password.email') }}" method="POST">
            {{ csrf_field() }}

            <div class="form-group">
              <!-- <label class="col-md-4 control-label">邮箱地址:</label> -->
              <div class="col-md-6">
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="邮箱地址" style="margin-top:18px">
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-danger sosad-button-auth">
                  发送重置邮件
                </button>
              </div>
            </div>
        </div>
    </div>
</div>
@stop
