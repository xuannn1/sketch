@extends('layouts.default')
@section('title', '新建收藏页')
@section('content')
<div class="container-fluid">
    <style media="screen">
    </style>
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>新建收藏页</h1>
            </div>
            <br>

            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('collection_group.store') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">收藏夹名称：</label>
                        <input type="text" name="name" class="form-control" value="">
                        <span class="font-6 grayout">（名称不超过10字）</span>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="set_as_default_group" value=true>
                            <span>新添加的收藏默认放在这里？</span>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label for="order_by">收藏页排序方式：</label>
                        <div class="">
                            @foreach (config('selectors.collection_filter.order_by') as $key => $orderby)
                            <label class="radio-inline"><input type="radio" name="order_by" value="{{ $key }}" {{$key===0?'checked':''}}>{{ $orderby }}</label>&nbsp;&nbsp;
                            @endforeach
                        </div>
                    </div>
                    <br>
                    <div class="text-left">
                        <button type="submit" class="btn btn-md btn-primary sosad-button">确认添加收藏页</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
