@extends('layouts.default')
@section('title', '编辑收藏页资料')
@section('content')
<div class="container-fluid">
    <style media="screen">
    </style>
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>编辑收藏页资料</h1>
            </div>
            <br>

            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('collection_group.update', $collection_group->id) }}">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="form-group">
                        <label for="name">收藏页名称：</label>
                        <input type="text" name="name" class="form-control" value="{{ $collection_group->name }}">
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="set_as_default_group" value=true {{ $info->default_collection_group_id==$collection_group->id? 'checked':'' }}>
                            <span>新添加的收藏默认放在这里？</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="order_by">收藏页排序方式：</label>
                       <div class="">
                           @foreach (config('selectors.collection_filter.order_by') as $key => $orderby)
                           <label class="radio-inline"><input type="radio" name="order_by" value="{{ $key }}" {{$key===$collection_group->order_by?'checked':''}}>{{ $orderby }}</label>&nbsp;&nbsp;
                           @endforeach
                       </div>
                    </div>
                    <br>

                    <div class="text-left">
                        <button type="submit" class="btn btn-lg btn-danger sosad-button">确认修改收藏页</button>
                        <a href="{{ route('collection_group.destroy', $collection_group->id) }}" class="btn btn-lg btn-danger sosad-button-control pull-right">删除本收藏页</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
