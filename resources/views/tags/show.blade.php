@extends('layouts.default')
@section('title', '标签展示')
@section('content')
<div class="container">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <a href="{{route('tag.index')}}">全站所有标签列表</a>
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>「{{$tag->tag_name}}」标签展示</h1>
                </div>
                <div class="panel-body">
                    <h4>标签名称：{{$tag->tag_name}}</h4>
                    <h4>标签解释：{{$tag->tag_explanation??'无'}}</h4>
                    <h4>标签类型：{{$tag->tag_type??'暂缺'}}</h4>
                    <h4>打有标记的书籍：{{$tag->thread_count}}本&nbsp;&nbsp;>><a href="{{route('books.index', ['withTag'=>$tag->id])}}">查看打有这个tag的书籍</a></h4>
                    <h4>边限与否：{{$tag->is_bianyuan?'是边限':'非边限'}}</h4>
                    <h4>大类与否：{{$tag->is_primary?'是大类标签':'非大类标签'}}</h4>
                    <h4>从属频道：<a href="{{route('channel.show', $tag->channel_id)}}">{{$tag->channel_id>0? $tag->channel()->channel_name:'无'}}</a></h4>
                    <h4>上级标签：</h4>
                    <div class="">
                        @if($tag->parent)
                        <a class="btn  btn-md btn-primary sosad-button-control" href="{{route('tag.show', $tag->parent_id)}}">{{$tag->parent->tag_name}}</a>
                        @else
                        无
                        @endif
                    </div>
                    <h4>下级标签：</h4>
                    <div class="">
                        @foreach($tag->children as $children)
                        <a class="btn  btn-md btn-primary sosad-button-control" href="{{route('tag.show', $children->id)}}">{{$children->tag_name}}</a>
                        @endforeach
                        {{$tag->children->count()==0?'无':''}}
                    </div>
                    <br>
                    <div class="">
                        @if(Auth::check()&&Auth::user()->isAdmin())
                            <a class="btn btn-lg btn-primary sosad-button" href="{{route('tag.edit', $tag->id)}}">修改标签</a>
                            @if($tag->tag_type==="同人原著")
                            <a class="pull-right btn btn-lg btn-primary admin-button" href="{{route('tag.create', ['tag_type'=>'同人CP', 'parent_id'=>$tag->id,'channel_id'=>2])}}">添加"{{$tag->tag_name}}"原著下的同人CP</a>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@stop
