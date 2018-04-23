@extends('layouts.default')
@section('title', '文库')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>文章列表</h3>
                <div class="row">
                    <div class="col-xs-10">
                        @foreach ($all_book_tags['tags_feibianyuan'] as $key=>$tag)
                        <a class="book-tag tags_feibianyuan badge newchapter-badge badge-tag {{$key>5? 'hidden extra-tag':''}} " href="{{ route('books.booktag', $tag->id) }}">{{ $tag->tagname }}</a>
                        @endforeach
                        @foreach ($all_book_tags['tags_tongren'] as $key=>$tag)
                        <a class="book-tag tags_tongren badge bianyuan-tag badge-tag {{$key>1? 'hidden extra-tag':''}}" href="{{ route('books.booktag', $tag->id) }}">{{ $tag->tagname }}</a>
                        @endforeach
                        @foreach ($all_book_tags['tags_tongren_yuanzhu'] as $key=>$tag)
                        <a class="book-tag tags_tongren_yuanzhu badge bianyuan-tag badge-tag {{$key>3? 'hidden extra-tag':''}}" href="{{ route('books.booktag', $tag->id) }}">{{ $tag->tagname }}</a>
                        @endforeach
                        @if(Auth::check())
                        @foreach ($all_book_tags['tags_bianyuan'] as $key=>$tag)
                        <a class="book-tag tags_bianyuan badge newchapter-badge badge-tag hidden extra-tag" href="{{ route('books.booktag', $tag->id) }}">{{ $tag->tagname }}</a>
                        @endforeach
                        @endif
                        <br>
                    </div>
                    <div class="col-xs-2">
                        <button class="btn btn-sm sosad-button-control" onclick="toggle_tags()" style="white-space:normal">更多<br>标签</button>
                    </div>
                </div>
                @include('books._book_selector')
            </div>
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
