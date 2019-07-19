@extends('layouts.default')
@section('title', '文库')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('books._book_selector')
                @include('books._book_selected')
                {{ $threads->links() }}
                @include('books._books')
                {{ $threads->links() }}
            </div>
        </div>
    </div>
</span>
</div>
@stop
