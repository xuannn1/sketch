@extends('layouts.default')
@section('title', '我关注的动态')

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

            </div>
        </div>
        <div class="status-container">
            {{ $statuses->links() }}
            @include('statuses._statuses')
            {{ $statuses->links() }}
        </div>
    </div>
</div>
@stop
