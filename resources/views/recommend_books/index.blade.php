@extends('layouts.default')
@section('title', '所有推荐')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>全部推荐书籍</h3>
                <ul class="nav nav-tabs">
                    @include('recommend_books._recommend_stats')
                </ul>
            </div>
            <div class="panel-body">
              {{ $recommend_books->links() }}
              @include('recommend_books._recommend_books')
              {{ $recommend_books->links() }}
            </div>
        </div>
    </div>
</div>
@stop
