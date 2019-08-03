@extends('layouts.default')
@section('title', '全站标签列表')
@section('content')
<div class="container">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>类别/标签列表</h1>
                </div>
                <div class="panel-body">
                    <div class="">
                        <div class="">
                            <span class="lead">篇幅：</span>
                            @foreach ($tag_range['book_length_tags'] as $tag)
                            <a class="btn  btn-md btn-primary sosad-button-control" href="{{route('tag.show', $tag->id)}}">{{$tag->tag_name}}</a>
                            @endforeach
                        </div>
                        <div class="">
                            <span class="lead">进度：</span>
                            @foreach ($tag_range['book_status_tags'] as $tag)
                            <a class="btn  btn-md btn-primary sosad-button-control" href="{{route('tag.show', $tag->id)}}">{{$tag->tag_name}}</a>
                            @endforeach
                        </div>
                        <div class="">
                            <span class="lead">性向：</span>
                            @foreach ($tag_range['sexual_orientation_tags'] as $tag)
                            <a class="btn  btn-md btn-primary sosad-button-control" href="{{route('tag.show', $tag->id)}}">{{$tag->tag_name}}</a>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>通用标签</h1>
                </div>
                <div class="panel-body">
                    <div class="">
                        <?php $previous_tag_type = ''; ?>
                        @foreach ($tag_range['book_custom_Tags'] as $tag)
                            @if($previous_tag_type&&$previous_tag_type!=$tag->tag_type)
                                <br>
                            @endif
                            @if(!$previous_tag_type||$previous_tag_type!=$tag->tag_type)
                                <code>{{ $tag->tag_type }}:</code>
                                @if(Auth::check()&&Auth::user()->isAdmin())
                                    <a class="btn btn-md btn-primary admin-button" href="{{route('tag.create', ['tag_type'=>$tag->tag_type, '   parent_id'=>0,'channel_id'=>0])}}">添加"{{$tag->tag_type}}"标签</a>&nbsp;&nbsp;
                                @endif
                            @endif
                                <a class="btn  btn-md btn-primary sosad-button-control" href="{{route('tag.show', $tag->id)}}">{{$tag->tag_name}}</a>
                            <?php $previous_tag_type = $tag->tag_type ?>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>同人标签</h1>
                </div>
                <div class="panel-body">
                    <div class="">
                        @foreach ($tag_range['tongren_primary_tags'] as $tongren_primary_tag)
                        <label class="radio-inline"><input class="tongren"  type="radio" name="primary_tag" value="{{ $tongren_primary_tag->id }}" onClick="show_only_children_yuanzhu('{{$tongren_primary_tag->id}}');">{{$tongren_primary_tag->tag_name}}</label>
                        @endforeach
                    </div>
                    <div class="tongren_block">
                        <h4>同人原著标签：</h4>
                        @foreach ($tag_range['tongren_yuanzhu_tags'] as $tag)
                        <a class="btn  btn-md btn-primary sosad-button-control hidden tongren tongren_yuanzhu {{$tag->parent_id>0?'parent'.$tag->parent_id:''}}" href="{{route('tag.show', $tag->id)}}">{{$tag->tag_name}}</a>
                        @endforeach
                        @if(Auth::check()&&Auth::user()->isAdmin())
                            @foreach ($tag_range['tongren_primary_tags'] as $tongren_primary_tag)
                            <a class="hidden tongren tongren_yuanzhu parent{{$tongren_primary_tag->id}} btn btn-md btn-primary admin-button" href="{{route('tag.create', ['tag_type'=>'同人原著', 'parent_id'=>$tongren_primary_tag->id,'channel_id'=>2])}}">添加"{{$tongren_primary_tag->tag_name}}"类的同人原著</a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
