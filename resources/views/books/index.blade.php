@extends('layouts.default')
@section('title', '文库')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('books._book_selector')

            </div>
            <div class="panel-body">
                {{ $books->links() }}
                @if(Auth::check())
                <div class="text-center">
                    <a class="btn btn-primary sosad-button" href="{{ route('books.index', ['showbianyuan' => request()->showbianyuan?'':'1']) }}" role="button">{{request()->showbianyuan?'取消':''}}显示边限</a>
                </div>
                @endif
                @include('books._books')
                {{ $books->links() }}
            </div>
        </div>
    </div>
</div>
@stop
