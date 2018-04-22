@extends('layouts.default')
@section('title', '文库')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @include('books._book_selector')
        <div class="panel panel-default">
            <div class="panel-heading lead">
                文章列表
            </div>
            <div class="">
                @foreach ($all_book_tags['tags_feibianyuan'] as $tag)
                <a class="badge newchapter-badge badge-tag" href="{{ route('books.booktag', $tag->id) }}">{{ $tag->tagname }}</a>
                @endforeach
                <br>
                @foreach ($all_book_tags['tags_tongren_yuanzhu'] as $tag)
                <a class="badge bianyuan-tag badge-tag" href="{{ route('books.booktag', $tag->id) }}">{{ $tag->tagname }}</a>
                @endforeach
            </div>
            <div class="panel-body">
                {{ $books->appends(request()->query())->links() }}
                @include('books._books')
                {{ $books->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@stop
