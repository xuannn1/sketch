@extends('layouts.default')
@section('title', '搜索用户')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>搜索用户</h4>
            </div>
            <div class="panel-body">
                @include('shared.errors')

                <form method="GET" action="{{ route('admin.searchusers') }}" name="searchusers">

                    <div class="form-group">
                        <label for="name">用户名相似字段：</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    </div>
                    <br>

                    <div class="form-group">
                        <label for="email">邮箱相似字段：</label>
                        <input type="text" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <br>

                    <button type="submit" class="btn btn-lg btn-danger sosad-button">发布</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
