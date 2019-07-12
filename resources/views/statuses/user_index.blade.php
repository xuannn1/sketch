@extends('layouts.default')
@section('title', '全部用户')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>全部用户</h3>
                <ul class="nav nav-tabs">
                    @include('statuses._status_tab')
                </ul>
            </div>
            @if(Auth::check())
            @include('statuses._status_form')
            @endif
            <div class="panel-body">
                {{ $users->links() }}
                @include('users._users')
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@stop
