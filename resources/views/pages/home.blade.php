@extends('layouts.default')
@section('content')
<div class="container">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @if(Auth::check())
        <div class="search-container">
            <form method="GET" action="{{ route('search') }}" id="search_form">
                <select name="search_options" form="search_form" onchange="if
                (this.options[this.selectedIndex].value=='tongren_yuanzhu')  document.getElementById('tongren_cp_name').style.display = 'inline';">
                <option value ="threads">标题</option>
                <option value ="users">用户</option>
                <option value ="tongren_yuanzhu" >同人原著</option>
            </select>
            <input type="textarea" placeholder="搜索..." name="search">
            <input type="textarea" placeholder="人名，也可不填" name="tongren_cp" id="tongren_cp_name" style="display:none">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>
    @endif
    <div class="jumbotron" >
        <h2 id= "daily-quote" class="display-1">{{ $quote->quote }}</h2>
        <div>
            <div class="text-right">
                @if ($quote->anonymous)
                ——{{ $quote->majia }}
                @else
                ——<a href="#">{{ $quote->creator->name }}</a>
                @endif
                <br>
            </div>
        </div>
        @if (Auth::check())
        <div class="col-xs-6 text-left h6">
            <u><a href="{{ route('quote.create') }}">贡献题头</a></u>
        </div>
        <div class="col-xs-6 text-right">
            <a class="btn btn-xs btn-default" href="{{ route('quote.vote', $quote->id) }}">咸鱼{{ $quote->xianyu }}</a><br>
        </div>
        @else
        <div class="text-center">
            <a class="btn btn-lg btn-success sosad-button" href="{{ route('register') }}" role="button">一起来丧</a>
        </div>
        @endif
    </div>
</div>
<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
    @foreach($channels as $channel)
    <div class="panel panel-default">
        <div class="panel-heading h4">
            <a href="{{ route('channel.show', $channel->id) }}">{{ $channel->channelname }}</a>
        </div>
        <div class="panel-body">
            <?php $thread = $channel->recent_thread_1; ?>
            @if(($thread->title)&&($thread->public)&&(!$thread->bianyuan))
            @include('threads._thread_info')
            @endif
        </div>
        <div class="panel-body">
            <?php $thread = $channel->recent_thread_2; ?>
            @if(($thread->title)&&($thread->public)&&(!$thread->bianyuan))
            @include('threads._thread_info')
            @endif
        </div>
    </div>
    @endforeach
</div>
</div>
@stop
