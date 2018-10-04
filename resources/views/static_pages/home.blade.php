@extends('layouts.default')
@section('content')
<div class="container">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="jumbotron" >
         <h2 id= "daily-quote" class="display-1">{{ $quote->quote }}</h2>
         <div>
            <div id= "quote-author" class="text-center">
               @if ($quote->anonymous)
                  —— {{ $quote->majia }}
               @else
                  —— <a href="#">{{ $quote->creator->name }}</a>
               @endif
               <br>
            </div>
         </div>
         @if (Auth::check())
         <!-- <div id="quote-button" class="col-xs-6 text-left h6">
            <u><a href="{{ route('quote.create') }}">贡献题头</a></u>
         </div>
         <div id="quote-button" class="col-xs-6 text-right">
            <a class="btn btn-xs btn-default" href="{{ route('quote.vote', $quote->id) }}">咸鱼{{ $quote->xianyu }}</a><br>
         </div> -->
             <div class="quote-button-wrapper">
                <a class="quote-button" href="{{ route('quote.create') }}">
                    <i class="fa fa-edit"></i>
                    贡献题头</a>
                <a class="quote-button" href="{{ route('quote.vote', $quote->id) }}">
                    咸鱼&nbsp;{{ $quote->xianyu }}</a><br>
             </div>

         @else
            <div class="text-center">
               <a class="quote-join button-dark" href="{{ route('register') }}" role="button">一起来丧</a>
            </div>
         @endif
      </div>
   </div>
      <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
         @if(Auth::check())
         <div class="panel panel-default">
         @include('statuses._status_form')
         </div>
         @endif
      </div>

   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      @foreach($channels as $channel)
         <div class="panel panel-default">
            <div class="panel-heading h4">
               <a id="channel-name" href="{{ route('channel.show', $channel->id) }}">{{ $channel->channelname }}</a>
            </div>
            <div class="panel-body">
               @foreach ($channel->recent_threads() as $thread)
                  @include('threads._thread_info')
               @endforeach
            </div>
         </div>
      @endforeach
   </div>
</div>
@stop
