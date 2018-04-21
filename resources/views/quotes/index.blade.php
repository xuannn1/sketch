@extends('layouts.default')
@section('title', '所有题头')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading lead">全部题头</div>
            <div class="panel-body">
                <?php $quotes = DB::table('quotes')->paginate(20); ?>
                @foreach ($quotes as $quote)
                @include('quotes._quote')
                @endforeach
            </div>
            {{ $quotes->links() }}
        </div>
    </div>
</div>
@stop
