@extends('layouts.default')
@section('title', '我的收藏单')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>我的收藏单</h3>
                <ul class="nav nav-tabs">
                    @include('collections._collection_stats')
                </ul>
            </div>
            <div class="panel-body">
                <h4><a href="">我建立的收藏单：</a></h4>
                <?php $collection_lists = $own_collection_lists ?>
                @include('collections._collection_lists')
            </div>
            <div class="panel-body">
                <?php $collection_lists = $collected_lists ?>
                <h4><a href="">我收藏的收藏单：</a></h4>
                @include('collections._collection_lists')
            </div>
            <div class="panel-body">
                <a class="btn btn-lg btn-primary sosad-button" href="{{ route('collections.collection_list_create') }}">新建收藏单</a>
            </div>
        </div>
    </div>
</div>
@stop
