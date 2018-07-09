@extends('layouts.default')
@section('content')
<div class="container">
   <div class="col-sm-10 col-sm-offset-1  col-md-8 col-md-offset-2">
      <div class="jumbotron" >
         <h2 id= "daily-quote" class="display-1">{{ $quote->quote }}</h2>
         <div>
            <div class="text-right">
               @if ($quote->anonymous)
                  <small>——{{ $quote->majia }}</small>
               @else
                  <small>——<a href="#">{{ $quote->creator->name }}</a></small>
               @endif
               <br>
            </div>
         </div>
         @if (Auth::check())
         <div class="col-xs-6 text-left">
            <small><a href="{{ route('quote.create') }}">我要贡献</a></small>
         </div>
         <div class="col-xs-6 text-right">
            <small><a href="{{ route('quote.vote', $quote->id) }}">咸鱼{{ $quote->xianyu }}</a></small><br>
         </div>
         @else
            <div class="text-center">
               <a class="btn btn-lg btn-success" href="{{ route('register') }}" role="button">一起来丧</a>
            </div>
         @endif
      </div>
   </div>

   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      @foreach($channels as $channel)
         <div class="panel panel-default">
            <div class="panel-heading">
               <nav>
                  <a class="lead" href="{{ route('channel.show', $channel->id) }}">{{ $channel->channelname }}</a>
               </nav>
            </div>
            <div class="panel-body">
               @foreach ($channel->recent_threads() as $thread)
                  <article class="">
                     <h4><a href="{{ $thread->path() }}">{{ $thread->title}}</a></h4>
                     <div class="body">{{ $thread->brief }}</div>
                  </article>
               @endforeach
            </div>
         </div>
      @endforeach
   </div>
</div>
@stop
