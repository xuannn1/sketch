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
             <div class="quote-button-wrapper">
                <a class="quote-button" href="{{ route('quote.create') }}">
                    <i class="fa fa-edit"></i>
                    贡献题头</a>
                <a class="quote-button" href="{{ route('quote.vote', $quote->id) }}">
                    咸鱼&nbsp;{{ $quote->xianyu }}</a><br>
             </div>

         @else
            <div class="text-center">
               <a class="quote-join" href="{{ route('register') }}" role="button">一起来丧</a>
            </div>
         @endif
      </div>
   </div>
   <!-- 曾经的状态栏 -->
      <!-- <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
         @if(Auth::check())
         <div class="panel panel-default">
         @include('statuses._status_form')
         </div>
         @endif
      </div> -->

   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      @foreach($channels as $channel)
         <div class="panel panel-default">
            <div class="panel-heading h4">
               <a id="channel-name" href="{{ route('channel.show', $channel->id) }}">{{ $channel->channelname }}</a>
            </div>
            <div class="panel-body">
                <?php $thread = $channel->recent_thread_1; ?>
                @if($thread->title)
                @include('threads._thread_info')
                @endif
                <?php $thread = $channel->recent_thread_2; ?>
                @if($thread->title)
                @include('threads._thread_info')
                @endif
            </div>
         </div>
      @endforeach
   </div>
</div>
@stop
