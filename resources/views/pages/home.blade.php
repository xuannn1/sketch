@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="10000">

            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                @include('pages._quote')
            </div>

            <!-- Left and right controls -->
            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#myCarousel" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
                <span class="sr-only">Next</span>
            </a>

        </div>
    </div>
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- insert editor's recommendation after the second channel -->
        <div class="panel panel-default">
            <div class="panel-heading h4">
                <a href="{{route('recommend_records')}}">编辑推荐</a>
            </div>
            @foreach($short_recom as $int => $post)
            <div class="panel-body">
                <div class="container-fluid">
                    <div class="recommendation">
                        <div class="row">
                            <div class="col-xs-12">
                                <a href="{{ route('thread.show', ['thread' => $post->review->thread_id, 'recommendation' => $post->review->id]) }}" class="bigger-10">《{{ $post->review->reviewee->title }}》
                                <span class="grayout smaller-15">{{ $post->body }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="panel-body">
                <div class="container-fluid">
                    <div class="recommendation">
                        <div class="row">
                            <div class="col-xs-12">
                                <a href="{{ route('thread.show', $thread_recom->id) }}" class="bigger-10">{{ $thread_recom->title }}：<span class="grayout smaller-15">{{ $thread_recom->brief }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @foreach($channel_threads as $channel_thread)
        <!-- each channel of the forum -->
        <div class="panel panel-default">
            <div class="panel-heading h4">
                <a href="{{ route('channel.show', $channel_thread['channel']->id) }}">{{ $channel_thread['channel']->channel_name }}</a>
            </div>
            @foreach($channel_thread['threads'] as $thread)
            <div class="panel-body">
                <article class="{{ 'thread'.$thread->id }}">
                    <div class="row">
                        <div class="col-xs-12 h5 brief">
                            <span>
                                <a href="{{ route('thread.show', $thread->id) }}" class="bigger-10">{{ $thread->title }}</a>
                            </span>
                            <span class = "pull-right">
                                @if($thread->author)
                                    @if($thread->anonymous)
                                    <span>{{ $thread->majia ?? '匿名咸鱼'}}</span>
                                        @if((Auth::check()&&(Auth::user()->admin)))
                                        <span class="admin-anonymous"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->author->name }}</a></span>
                                        @endif
                                    @else
                                        <a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->author->name }}</a>
                                    @endif
                                @endif
                            </span>
                        </div>
                        <div class="col-xs-12 h5 brief">
                            <span class="grayout smaller-15">{{ $thread->brief }}</span>
                            <span class="pull-right smaller-15">{{ $thread->created_at->diffForHumans() }}／{{ $thread->responded_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </article>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>
@stop
