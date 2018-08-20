@extends('layouts.default')
@section('title', '我的收藏单')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="sosad-heading">我的收藏</h2>
                <ul class="nav nav-pills nav-fill nav-justified">
                    @include('collections._collection_stats')
                </ul>
            </div>
            <div class="panel-body">
                <h4><a href="">我建立的清单：</a></h4>
                <?php $collection_lists = $own_collection_lists; $show_as_collections=0; ?>
                @include('collections._collection_lists')
            </div>
            <div class="panel-body">
                <?php $collection_lists = $collected_lists ; $show_as_collections=1; ?>
                <h4><a href="">我收藏的清单：</a></h4>
                @include('collections._collection_lists')
            </div>
            <div class="panel-heading">
                <a class="btn-lg sosad-button-post" href="{{ route('collections.collection_list_create') }}">新建清单</a>
            </div>
            <div class="panel-body">

            </div>
        </div>
    </div>
</div>
@stop
