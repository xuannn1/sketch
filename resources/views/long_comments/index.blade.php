@extends('layouts.default')
@section('title', '论坛')
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
                {{$posts->links()}}
                @include('posts._posts')
                {{$posts->links()}}
            </div>
        </div>
    </div>
</div>
@stop
