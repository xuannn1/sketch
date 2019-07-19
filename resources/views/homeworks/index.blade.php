@extends('layouts.default')
@section('title', '所有作业')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading lead">全部作业</div>
            <div class="panel-body">
                @include('homeworks._homeworks')
                {{ $homeworks->links() }}
            </div>
        </div>
    </div>
</div>
@stop
