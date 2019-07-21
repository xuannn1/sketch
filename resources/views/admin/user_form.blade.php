@extends('layouts.default')
@section('title', '用户高级管理')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @include('shared.errors')
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2>管理用户</h2>
                <h1><a href="{{route('user.show',$user->id)}}">{{$user->name}}</a></h1>
            </div>
            <div class="panel-body">
                <form action="{{ route('admin.usermanagement',$user->id)}}" method="POST">
                    {{ csrf_field() }}
                    <div class="admin-symbol">
                        <h1>管理员权限专区：警告！请勿进行私人用户操作！</h1>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="controluser" value="13">设置禁言时间</label>
                        <label><input type="text" style="width: 40px" name="noposting-days" value="0">天</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="controluser" value="14">解除用户禁言</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="controluser" value="18">设置禁止登陆时间</label>
                        <label><input type="text" style="width: 40px" name="nologging-days" value="0">天</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="controluser" value="19">解除禁止登陆用户</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="controluser" value="20">用户等级积分归零</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="controluser" value="50">分值管理（正加分，负减分，系统会自动记录分值）</label>
                        <label><input type="text" style="width: 40px" name="salt" value="0">盐粒</label>
                        <label><input type="text" style="width: 40px" name="fish" value="0">咸鱼</label>
                        <label><input type="text" style="width: 40px" name="ham" value="0">丧点</label>
                        <label><input type="text" style="width: 40px" name="level" value="0">等级</label>
                    </div>
                    <div class="form-group">
                        <label for="reason"></label>
                        <textarea name="reason"  rows="3" class="form-control" placeholder="请输入处理理由。"></textarea>
                    </div>
                    <div class="">
                        <button type="submit" class="btn btn-danger sosad-button btn-md admin-button">确定管理</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
