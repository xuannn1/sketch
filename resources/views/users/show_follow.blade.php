@extends('layouts.default')
@section('title', $user->name.'-'.$follow_title)

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            @include('shared.errors')
            <div class="panel-body text-center">
                @include('users._user_profile')
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>{{$follow_title}}</h1>
            </div>
            <div class="panel-body">
                {{$users->links()}}
                @include('users._users')
                {{$users->links()}}
            </div>
        </div>
    </div>
</div>
@stop
