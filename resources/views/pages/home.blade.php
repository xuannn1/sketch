@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="10000">

            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                @foreach($quotes as $int=>$quote)
                <div class="jumbotron item {{$int==0? 'active':''}}" >
                    @include('pages._quote')
                </div>
                @endforeach
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
            @foreach($recom_sr as $int => $recommendation)
            <div class="panel-body">
                <div class="container-fluid">
                    <div class="recommendation">
                        <div class="row">
                            <div class="col-xs-12">
                                <a href="{{ route('thread.show', ['thread' => $recommendation->thread_id, 'recommendation' => $recommendation->id]) }}" class="bigger-10">《{{ $recommendation->title }}》
                                <span class="grayout smaller-15">{{ $recommendation->recommendation }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @foreach($recom_lg as $int => $recommendation)
            <div class="panel-body">
                <div class="container-fluid">
                    <div class="recommendation">
                        <div class="row">
                            <div class="col-xs-12">
                                <a href="{{ route('thread.showpost', ['post' => $recommendation->thread_id, 'recommendation' => $recommendation->id]) }}" class="bigger-10">长评推荐《{{ $recommendation->title }}》：<span class="grayout smaller-15">{{ $recommendation->recommendation }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @foreach($channels as $channel)
        <!-- each channel of the forum -->
        <div class="panel panel-default">
            <div class="panel-heading h4">
                <a href="{{ route('channel.show', $channel->id) }}">{{ $channel->channelname }}</a>
            </div>
            @foreach($threads[$channel->id] as $thread)
            <div class="panel-body">
                @include('threads._thread_info')
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>
@stop
