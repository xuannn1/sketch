@extends('layouts.default')
@section('title', '打赏列表')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-header text-left">
                <div class="font-1">
                    打赏列表
                </div>
                @include('rewards._model')
            </div>
            <hr>
            <div class="panel-body">
                {{ $rewards->links() }}
                @include('rewards._brief_rewards')
                {{ $rewards->links() }}
            </div>
        </div>
    </div>
</div>
@stop
