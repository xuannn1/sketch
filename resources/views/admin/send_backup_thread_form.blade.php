@extends('layouts.default')
@section('title', '发送')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading"><h4>备份相关主题文章</h4></div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('admin.storebackupthreadrequest') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="body">请输入需要备份的主题贴正文：<input type="text" name="thread_id" class="form-control" placeholder="请输入需要备份的thread_id" value="{{ old('thread_id') }}"></label>
                    </div>
                    <button type="submit" class="btn btn-primary">发布</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
