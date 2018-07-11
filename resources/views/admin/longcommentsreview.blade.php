@extends('layouts.default')
@section('title', '审核长评')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>审核长评</h4>
                </div>
                <div class="panel-body">
                    {{$posts->links()}}
                    @include('posts._posts')
                    {{$posts->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
