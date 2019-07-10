@extends('layouts.default')
@section('title', '论坛')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="site-map">
            <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            &nbsp;/&nbsp;
            <a href="{{ route('channel.show', $channel->id) }}">{{ $channel->channel_name }}</a>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="{{ request('withTag') ? '': 'active' }}"><a href="{{ route('channel.show', $channel->id) }}">全部</a></li>
                    @foreach($primary_tags as $tag)
                    <li role="presentation" id="tag-{{ $tag->id }}" class="{{ request('withTag')==$tag->id ? 'active':'' }}">
                        <a href="{{ route('channel.show', array_merge(['channel'=>$channel->id, 'withTag'=>$tag->id ], request()->only('showbianyuan','orderby')) ) }}" >
                            {{ $tag->tag_name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="panel-body">
                @include('threads._simple_threads')
                {{ $threads->links() }}
                @include('threads._threads')
                {{ $threads->links() }}
            </div>
        </div>
    </div>
</div>
@stop
