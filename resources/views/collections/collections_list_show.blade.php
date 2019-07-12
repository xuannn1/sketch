@extends('layouts.default')
@section('title', $collection_list->title)

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>
                    @if ($collection_list->anonymous)
                    <span>{{ $collection_list->majia ?? '匿名咸鱼'}}</span>
                    @if((Auth::check()&&(Auth::user()->admin)))
                    <span class="admin-anonymous"><a href="{{ route('user.show', $collection_list->user_id) }}">{{ $collection_list->creator->name }}</a></span>
                    @endif
                    @else
                    <a href="{{ route('user.show', $collection_list->user_id) }}">{{ $collection_list->creator->name }}</a>
                    @endif
                    的收藏单
                </h4>
                <h1><a href="{{ route('collections.collection_list_show', $collection_list->id) }}">{{ $collection_list->title }}</a></h1>
                <div class="h4">{{ $collection_list->brief }}</div>
                <p class="grayout smaller-20">
                    发表于{{ Carbon\Carbon::parse($collection_list->created_at)->diffForHumans() }}
                    @if($collection_list->created_at < $collection_list->lastupdated_at )
                    修改于{{ Carbon\Carbon::parse($collection_list->lastupdated_at)->diffForHumans() }}
                    @endif
                </p>
                <div class="text-center main-text">
                    {!! StringProcess::wrapParagraphs($collection_list->body) !!}
                </div>
                <!-- 原作者可以修改这个收藏单的描述 -->
                @if(Auth::id()==$collection_list->user_id)
                <div class="">
                    <a type="button" class="btn btn-danger sosad-button btn-sm" id="cancelCollections" onClick="toggleCancelButtons()">整理收藏单</a>
                    <a href="{{ route('collections.collection_list_edit', $collection_list->id) }}" class="pull-right btn btn-warning sosad-button btn-sm">修改收藏单描述</a>
                </div>
                @else
                <button class="btn btn-sm btn-success sosad-button" id="itemcollection{{$collection_list->id}}" onclick="item_add_to_collection({{$collection_list->id}},4,0)">收藏{{ $collection_list->collected }}</button>
                @endif
            </div>
            <div class="panel-body">
                @if($collection_list->type == '1')
                <?php $books = $collected_items; ?>
                @include('books._books')
                @elseif($collection_list->type == '2')
                <?php $threads = $collected_items; ?>
                @include('threads._threads')
                @endif
            </div>
        </div>
    </div>
</div>
@stop
