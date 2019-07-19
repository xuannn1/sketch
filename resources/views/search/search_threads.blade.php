@extends('layouts.default')
@section('title', '搜索结果')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading lead">搜索主题</div>
            <div class="panel-body">
                @if($threads->count()>0)
                    @include('search._threads')
                    {{ $threads->links() }}
                @else
                    <h2>抱歉，未能搜索到对应的条目</h2>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
