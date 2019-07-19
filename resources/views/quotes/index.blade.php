@extends('layouts.default')
@section('title', '题头列表')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-header text-center">
                <h1>题头列表</h1>
                @include('quotes._quote_tab')
            </div>
            <div class="panel-body">
                {{ $quotes->links() }}
                @include('quotes._quotes')
                {{ $quotes->links() }}
            </div>
        </div>
    </div>
</div>
@stop
