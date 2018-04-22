@extends('layouts.default')
@section('title', '文库')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @include('books._book_selector')
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>文章列表</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-10">
                        @foreach ($all_book_tags['tags_feibianyuan'] as $key=>$tag)
                        <a class="book-tag tags_feibianyuan badge newchapter-badge badge-tag {{$key>5? 'hidden extra-tag':''}} " href="{{ route('books.booktag', $tag->id) }}">{{ $tag->tagname }}</a>
                        @endforeach
                        @if(Auth::check())
                        @foreach ($all_book_tags['tags_bianyuan'] as $key=>$tag)
                        <a class="book-tag tags_bianyuan badge bianyuan-tag badge-tag {{$key>5? 'hidden extra-tag':''}} " href="{{ route('books.booktag', $tag->id) }}">{{ $tag->tagname }}</a>
                        @endforeach
                        @endif
                        @foreach ($all_book_tags['tags_tongren'] as $key=>$tag)
                        <a class="book-tag tags_tongren badge newchapter-badge badge-tag {{$key>2? 'hidden extra-tag':''}}" href="{{ route('books.booktag', $tag->id) }}">{{ $tag->tagname }}</a>
                        @endforeach
                        @foreach ($all_book_tags['tags_tongren_yuanzhu'] as $key=>$tag)
                        <a class="book-tag tags_tongren_yuanzhu badge newchapter-badge badge-tag {{$key>2? 'hidden extra-tag':''}}" href="{{ route('books.booktag', $tag->id) }}">{{ $tag->tagname }}</a>
                        @endforeach
                        <br>
                    </div>
                    <div class="col-xs-2">
                        <button class="btn btn-sm sosad-button-control" onclick="toggle_tags()" style="white-space:normal">更多<br>标签</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="panel-body">
                {{ $books->appends(request()->query())->links() }}
                @include('books._books')
                {{ $books->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@stop
