@extends('layouts.default')
@section('title', '修改个人简介')
@section('content')
<div class="container-fluid">
    <style media="screen">
    </style>
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>修改 {{ $user->name }} 的个人简介</h3>
            </div>
            <div class="panel-body">
                <h4>修改个人简介</h4>
                @include('shared.errors')
                <form method="POST" action="{{ route('user.update_introduction') }}">
                        {{ csrf_field() }}
                    <div class="form-group">
                        <label for="brief_intro">短介绍：</label>
                        <input type="text" name="brief_intro" class="form-control" value="{{ $info->brief_intro }}">
                    </div>

                    <div class="form-group">
                        <label for="introduction"><h5>个人简介：</h5></label>
                        <textarea name="introduction" id="introduction" data-provide="markdown" rows="5" class="form-control" placeholder="改一下吧">{{ $info->introduction }}</textarea>
                        <button type="button" onclick="retrievecache('introduction')" class="sosad-button-control addon-button">恢复数据</button>
                    </div>

                    <button type="submit" class="btn btn-danger sosad-button">更新个人简介</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
