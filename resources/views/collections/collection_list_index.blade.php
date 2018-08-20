@extends('layouts.default')
@section('title', '全部收藏单')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="sosad-heading">论坛</h2>
                <ul class="nav nav-pills nav-fill nav-justified">
                    @include('threads._discussions_stats')
                </ul>
            </div>
            <div class="panel-body">
                {{ $collection_lists->links() }}
                @include('collections._collection_lists')
                {{ $collection_lists->links() }}
            </div>
            <div class="panel-heading">
                <a class="btn-lg sosad-button-post" href="{{ route('collections.collection_list_create') }}">
                    <i class="fa fa-plus"></i>
                    新建清单
                </a>
            </div>
            <div class="panel-body">

            </div>
        </div>
    </div>
</div>
@stop
