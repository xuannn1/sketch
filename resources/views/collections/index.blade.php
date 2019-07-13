@extends('layouts.default')
@section('title', '我的收藏')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>我的收藏</h1>
                <ul class="nav nav-tabs">
                    @include('collections._collection_tab')
                </ul>
            </div>
            <div class="panel-body">
                {{ $threads->links() }}
                @include('collections._threads')
                {{ $threads->links() }}
            </div>
        </div>
    </div>
</div>
@stop
