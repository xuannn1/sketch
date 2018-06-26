@extends('layouts.default')
@section('title', $collection_list->title)

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><a href="{{ route('user.show', $collection_list->user_id) }}">{{ $collection_list->creator->name }}</a>&nbsp;的收藏单</h4>
                <h1><a href="{{ route('collections.collection_list_show', $collection_list->id) }}">{{ $collection_list->title }}</a></h1>
                <div class="text-center main-text">
                    {!! Helper::wrapParagraphs($collection_list->body) !!}
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
