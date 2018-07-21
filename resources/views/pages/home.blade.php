@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">

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
        @foreach($channels as $channel)
        <div class="panel panel-default">
            <div class="panel-heading h4">
                <a href="{{ route('channel.show', $channel->id) }}">{{ $channel->channelname }}</a>
                <span>{{ $channel->description }}</span>
            </div>
            @foreach($threads[$channel->id] as $thread)
                <div class="panel-body">
                    @include('threads._thread_info')
                </div>
            @endforeach
        </div>
        @if($channel->id === 2)
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="h5 text-center">
                    <span>每周推荐</span>
                </div>
                <div class="container-fluid text-center">
                    <div class="row">
                        @foreach($recommends as $int => $thread)
                        <div class="col-xs-4">
                            <a href="{{ route('thread.show', $thread->id) }}" class="bigger-10">{{ $thread->title }}<br>
                            <span class="grayout smaller-15">{{ $thread->brief }}</span>
                            </a>
                        </div>
                        @if ($int ===2 )
                        </div>
                        <div class="row">
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>
@stop
