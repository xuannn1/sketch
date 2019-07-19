@extends('layouts.default')
@section('title', '全站标签列表')
@section('content')
<div class="container">
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
                            <a class="btn  btn-md btn-primary sosad-button-control" href="{{ route('books.index', ['inChannel'=>'1']) }}">原创</a>
                            <a class="btn  btn-md btn-primary sosad-button-control" href="{{ route('books.index', ['inChannel'=>'2']) }}">同人</a>
                        </div>
                        <br>
                        <div class="">
                            <span class="lead">篇幅：</span>
                            @foreach ($tag_range['book_length_tags'] as $tag)
                            <a class="btn  btn-md btn-primary sosad-button-control" href="{{route('books.index', ['withTag'=>$tag->id])}}">{{$tag->tag_name}}</a>
                            @endforeach
                        </div>
                        <br>
                        <div class="">
                            <span class="lead">进度：</span>
                            @foreach ($tag_range['book_status_tags'] as $tag)
                            <a class="btn  btn-md btn-primary sosad-button-control" href="{{route('books.index', ['withTag'=>$tag->id])}}">{{$tag->tag_name}}</a>
                            @endforeach
                        </div>
                        <br>
                        <div class="">
                            <span class="lead">性向：</span>
                            @foreach ($tag_range['sexual_orientation_tags'] as $tag)
                            <a class="btn  btn-md btn-primary sosad-button-control" href="{{route('books.index', ['withTag'=>$tag->id])}}">{{$tag->tag_name}}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="">
                        <h4>通用标签：</h4>
                        <?php $previous_tag_type = 0; ?>
                        @foreach ($tag_range['book_custom_Tags'] as $tag)
                        @if($previous_tag_type===0||$previous_tag_type!=$tag->tag_type)
                        <br><br><code>{{ $tag->tag_type }}:</code>
                        @endif
                            <a class="{{$tag->is_bianyuan&&$level<=3? 'hidden':''}} btn  btn-md btn-primary sosad-button-control" href="{{route('books.index', ['withTag'=>$tag->id])}}">{{$tag->tag_name}}</a>
                        </label>
                        <?php $previous_tag_type = $tag->tag_type ?>
                        @endforeach
                    </div>
                    <button type="button" name="button" onclick="tongren_checked()" class="btn btn-lg btn-primary sosad-button">同人原著标签</button>
                    <div class="tongren_block hidden">
                        <h4>同人原著标签：</h4>
                        @foreach ($tag_range['tongren_yuanzhu_tags'] as $tag)
                        <a class="btn  btn-md btn-primary sosad-button-control"  href="{{route('books.index', ['withTag'=>$tag->id])}}">{{$tag->tag_name}}</a>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@stop
