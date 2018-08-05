@extends('layouts.default')
@section('title', '文库')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            @include('books._book_selector')
            <hr>
            <div class="panel-body">
                {{ $books->links() }}
                @include('books._books')
                {{ $books->links() }}
            </div>
        </div>
    </div>
</div>
@stop
