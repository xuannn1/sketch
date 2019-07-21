@extends('layouts.default')
@section('title', '搜索结果')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading lead">
                <h1>关键词:「{{ $pattern }}」</h1>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading lead">
                <h4>帮助搜索</h4>
            </div>
            <div class="panel-body">
                @include('search._faqs')
                <div class="text-center">
                    <a href="{{ route('help') }}">查看更多使用帮助</a>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading lead">
                <h4>标签搜索</h4>
            </div>
            <div class="panel-body">
                @include('search._tags')
                @if($tags->hasMorePages())
                <div class="text-center">
                    <a href="{{ route('search.search_tag', ['search' => $pattern]) }}">查看更多标签搜索结果</a>
                </div>
                @endif
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading lead">
                <h4>书籍搜索</h4>
            </div>
            <div class="panel-body">
                @include('threads._simple_threads')
                @if($simplethreads->hasMorePages())
                <div class="text-center">
                    <a href="{{ route('search.search_thread', ['search' => $pattern]) }}">查看更多书籍搜索结果</a>
                </div>
                @endif
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading lead">
                <h4>用户搜索</h4>
            </div>
            <div class="panel-body">
                @include('search._users')
                @if($users->hasMorePages())
                <div class="text-center">
                    <a href="{{ route('search.search_user', ['search' => $pattern]) }}">查看更多用户搜索结果</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
