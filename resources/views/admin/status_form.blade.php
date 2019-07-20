@extends('layouts.default')
@section('title', '主题贴高级管理')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @include('shared.errors')
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>管理动态</h1>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="h4">
                        <span>
                            <a href="{{ route('user.show', $status->user_id) }}">{{ $status->author->name }}</a>&nbsp;
                            {{ $status->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <div class="h4 brief">
                        <span class="smaller-10">
                            {!! StringProcess::wrapParagraphs($status->body) !!}
                        </span>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <form action="{{ route('admin.statusmanagement',$status->id)}}" method="POST">
                    {{ csrf_field() }}
                    <div class="admin-symbol">
                        <h4>管理员权限专区：警告！请勿进行私人用户操作</h4>
                    </div>
                    <div class="checkbox">
                        <p class="h4 admin-symbol pull-right"><label><input type="checkbox" name="delete">删除动态</label></p>
                    </div>
                    <div class="form-group">
                        <label for="reason"></label>
                        <textarea name="reason"  rows="3" class="form-control" placeholder="请输入处理理由(理由将会公示)，以及处理参数（如禁言时间，精华时间）。"></textarea>
                    </div>
                    <div class="">
                        <button type="submit" class="btn btn-md btn-danger sosad-button btn-md admin-button">确定管理</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
