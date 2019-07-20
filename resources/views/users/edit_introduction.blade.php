@extends('layouts.default')
@section('title', '修改个人简介')
@section('content')
<div class="container-fluid">
    <style media="screen">
    </style>
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2>修改「{{ $user->name }}」的个人介绍</h2>
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('user.update_introduction') }}">
                        {{ csrf_field() }}
                    <div class="form-group">
                        <label for="brief_intro">短介绍：</label>
                        <input type="text" name="brief_intro" class="form-control" value="{{ $info->brief_intro }}">
                        <h6>（短介绍45字内）</h6>
                    </div>

                    <div class="form-group">
                        <label for="introduction">个人介绍：</label>
                        <textarea name="introduction" id="introduction" data-provide="markdown" rows="5" class="form-control" placeholder="在这里输入个人介绍文字哦！支持BBCode格式！可以插图插链接～">{{ $intro?$intro->body:'' }}</textarea>
                        <button type="button" onclick="retrievecache('introduction')" class="sosad-button-control addon-button">恢复数据</button>
                    </div>

                    <button type="submit" class="btn btn-md btn-primary sosad-button">更新个人介绍</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
