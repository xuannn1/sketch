@extends('layouts.default')
@section('title', '评票列表')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-header text-left">
                <div class="font-1">
                    评票列表
                </div>
                @include('rewards._model')
            </div>
            <hr>
            <div class="panel-body">
                {{ $votes->links() }}
                @include('votes._brief_votes')
                {{ $votes->links() }}
            </div>
        </div>
    </div>
</div>
@stop
