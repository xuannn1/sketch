@extends('layouts.default')
@section('title', '往期短推')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading lead">往期短推</div>
            <div class="panel-body">
                {{ $recommend_books->links() }}
                @include('recommend_books._recommend_records')
                {{ $recommend_books->links() }}
            </div>
        </div>
    </div>
</div>
@stop
