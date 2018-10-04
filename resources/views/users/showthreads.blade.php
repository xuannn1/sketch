@extends('layouts.default')
@section('title', $user->name.'-'.'讨论帖列表')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                @include('users._user')
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>讨论帖目录</h4>
            </div>
            <div class="panel-body">
                {{$threads->links()}}
                @include('threads._threads')
                {{$threads->links()}}
            </div>
        </div>
    </div>
</div>
@stop
