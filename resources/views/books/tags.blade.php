@extends('layouts.default')
@section('title', '全站标签列表')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-10 col-xs-offset-1">
                                <h1>类别/标签列表</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="">
                        <div class="">
                            <span class="lead">原创性：</span>
                            <a class="btn btn-xs btn-primary sosad-button-control" href="{{ route('books.index', ['channel'=>'1']) }}">原创</a>
                            <a class="btn btn-xs btn-primary sosad-button-control" href="{{ route('books.index', ['channel'=>'2']) }}">同人</a>
                        </div>

                        <div class="">
                            <span class="lead">篇幅：</span>
                            @foreach(config('constants.book_info.book_length_info') as $key=>$book_length)
                            <a class="btn btn-xs btn-primary sosad-button-control" href="{{ route('books.index', ['book_length'=>$key]) }}">{{ $book_length }}</a>
                            @endforeach
                        </div>

                        <div class="">
                            <span class="lead">进度：</span>
                            @foreach(config('constants.book_info.book_status_info') as $key=>$book_status)
                            <a class="btn btn-xs btn-primary sosad-button-control" href="{{ route('books.index', ['book_status'=>$key]) }}">{{ $book_status }}</a>
                            @endforeach
                        </div>

                        <div class="">
                            <span class="lead">性向：</span>
                            @foreach(config('constants.book_info.sexual_orientation_info') as $key=>$sexual_orientation)
                            <a class="btn btn-xs btn-primary sosad-button-control" href="{{ route('books.index', ['sexual_orientation'=>$key]) }}">{{ $sexual_orientation }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="">
                        <h4>通用标签：</h4>
                        <?php $tag_info = 0; ?>
                        @foreach(Helper::tags_general() as $key=>$tag)
                            @if((Auth::check()&&(Auth::user()->user_level>2))||($tag->tag_group!==5))
                                @if(($tag_info<$tag->tag_info)&&($tag_info>0))
                                <br>
                                @endif
                            <a href="{{ route('books.index', ['book_tag'=>$tag->id]) }}" class="btn btn-xs btn-primary sosad-button-control">{{ $tag->tagname }}</a>
                            &nbsp;&nbsp;&nbsp;
                            <?php $tag_info = $tag->tag_info ?>
                            @endif
                        @endforeach
                    </div>
                    <button type="button" name="button" onclick="toggle_tags_tongren_yuanzhu()" class="btn btn-sm btn-primary sosad-button">同人原著标签</button>
                    <div class="tongren_yuanzhu hidden">
                        <h4>同人原著标签：</h4>
                        @foreach(Helper::tags_tongren_yuanzhu() as $key=>$tag)
                        <a href="{{ route('books.index', ['book_tag'=>$tag->id]) }}"  class="btn btn-xs btn-primary sosad-button-control">{{ $tag->tagname }}</a>
                        &nbsp;&nbsp;&nbsp;
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@stop
