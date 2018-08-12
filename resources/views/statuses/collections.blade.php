@extends('layouts.default')
@section('title', '关注对象的动态')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <ul class="nav nav-pills nav-fill">
                    @include('statuses._statuses_stats')
                </ul>
            </div>
            @if(Auth::check())
            @include('statuses._status_form')
            @endif
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
