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
                        <!-- 原创性标记 -->
                        @foreach($book_info['originality_info'] as $key=>$originality)
                        <a class="badge badge-tag" href="{{ route('books.index',['channel'=>(int)($key+1)]) }}">{{$originality}}</a>
                        @endforeach
                        <!-- 书本篇幅标记 -->
                        @foreach($book_info['book_lenth_info'] as $key=>$book_lenth)
                        <a class="badge badge-tag" href="{{ route('books.index',['book_length'=>$key]) }}">{{$book_lenth}}</a>
                        @endforeach
                        <!-- 书本状态标记 -->
                        @foreach($book_info['book_status_info'] as $key=>$book_status)
                        <a class="badge badge-tag" href="{{ route('books.index',['book_status'=>$key]) }}">{{$book_status}}</a>
                        @endforeach
                        <!-- 书本性向标记 -->
                        @foreach($book_info['sexual_orientation_info'] as $key=>$sexual_orientation)
                        <a class="badge badge-tag" href="{{ route('books.index',['sexual_orientation'=>$key]) }}">{{$sexual_orientation}}</a>
                        @endforeach
                        <!-- 不是边缘的tag -->
                        @foreach ($all_book_tags['tags_feibianyuan'] as $key=>$tag)
                        <a class="book-tag tags_feibianyuan badge newchapter-badge badge-tag {{$key>5? 'hidden extra-tag':''}} " href="{{ route('books.booktag', $tag->id) }}">{{ $tag->tagname }}</a>
                        @endforeach
                        <!-- 同人特殊tag -->
                        @foreach ($all_book_tags['tags_tongren'] as $key=>$tag)
                        <a class="book-tag tags_tongren badge bianyuan-tag badge-tag {{$key>1? 'hidden extra-tag':''}}" href="{{ route('books.booktag', $tag->id) }}">{{ $tag->tagname }}</a>
                        @endforeach
                        <!-- 同人原著tag -->
                        @foreach ($all_book_tags['tags_tongren_yuanzhu'] as $key=>$tag)
                        <a class="book-tag tags_tongren_yuanzhu badge bianyuan-tag badge-tag {{$key>3? 'hidden extra-tag':''}}" href="{{ route('books.booktag', $tag->id) }}">{{ $tag->tagname }}</a>
                        @endforeach
                        @if(Auth::check())
                        <!-- 边缘特殊tag -->
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
