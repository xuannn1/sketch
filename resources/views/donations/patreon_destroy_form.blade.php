@extends('layouts.default')
@section('title', '删除Patreon赞助者信息')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">删除「{{$user->name}}」Patreon赞助者信息</div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="email">Patreon账户邮箱：</label>
                        <input type="text" name="email" class="form-control" value="{{ $patreon->patreon_email }}" disabled>
                    </div>
                    <h3>你好，赞助者信息一旦删除，所有对应的赞助者福利均将取消，且不能补偿，只保留赞助记录。请问你确定要删除本条信息吗？</h3>
                    <form method="POST" action="{{ route('donation.patreon_destroy', $patreon->id)}}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="pull-right btn btn-md btn-danger sosad-button-control">确定删除</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
