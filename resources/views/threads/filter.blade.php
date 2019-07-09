@extends('layouts.default')
@section('title', '筛选')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>筛选</h3>
            </div>
            <div class="panel-body">
                {{ $threads->links() }}
                @include('threads._threads')
                {{ $threads->links() }}
            </div>
        </div>
    </div>
</div>
@stop
