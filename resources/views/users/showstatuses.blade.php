@extends('layouts.default')
@section('title', $user->name.'-'.'动态列表')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading text-center">
                @include('users._user')
            </div>
        </div>
        <div class="panel panel-default" style="background: none;">
            <div class="panel-heading" style="background: none;">
                <h4>动态列表</h4>
            </div>
        </div>
        <div class="status-container">
            {{$statuses->links()}}
            @include('statuses._statuses')
            {{$statuses->links()}}
        </div>
    </div>
</div>
@stop
