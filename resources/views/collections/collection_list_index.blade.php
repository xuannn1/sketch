@extends('layouts.default')
@section('title', '全部收藏单')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>全部收藏单</h3>
            </div>
            <div class="panel-body">
                @include('collections._collection_lists')
            </div>
            <div class="panel-body">
                <a class="btn btn-lg btn-primary sosad-button" href="{{ route('collections.collection_list_create') }}">新建收藏单</a>
            </div>
        </div>
    </div>
</div>
@stop
