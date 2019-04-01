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
                                <h1>标签列表</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="">
                        <h4>通用标签：</h4>
                        <?php $tag_info = 0; ?>
                        @foreach(Helper::tags_general() as $key=>$tag)
                        @if((Auth::check())||($tag->tag_group!==5))
                        @if(($tag_info<$tag->tag_info)&&($tag_info>0))
                        <br>
                        @endif
                        <a href="{{ route('books.booktag', $tag->id) }}" class="btn btn-xs btn-primary sosad-button-control">{{ $tag->tagname }}</a>
                        &nbsp;&nbsp;&nbsp;
                        <?php $tag_info = $tag->tag_info ?>
                        @endif
                        @endforeach
                    </div>
                    <button type="button" name="button" onclick="toggle_tags_tongren_yuanzhu()" class="btn btn-sm btn-primary sosad-button">同人原著标签</button>
                    <div class="tongren_yuanzhu hidden">
                        <h4>同人原著标签：</h4>
                        @foreach(Helper::tags_tongren_yuanzhu() as $key=>$tag)
                        <a href="{{ route('books.booktag', $tag->id) }}"  class="btn btn-xs btn-primary sosad-button-control">{{ $tag->tagname }}</a>
                        &nbsp;&nbsp;&nbsp;
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@stop
