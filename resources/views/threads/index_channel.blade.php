@extends('layouts.default')
@section('title', $channel->channelname)
@section('content')
<div class="container-fluid">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="panel panel-default">
         <div class="panel-heading">
            <a type="btn btn-primary" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span>&nbsp;<span>首页</span></a>&nbsp;/&nbsp;
            <span><a href="{{ route('channel.show', $channel->id) }}">{{ $channel->channelname }}</a></span>
            <br>
            <br>
            <ul class="nav nav-tabs">
               <li role="presentation" class="{{ $label->id ? '': 'active' }}"><a href="{{ route('channel.show', $channel) }}">全部<span class="badge">{{ $total }}</span></a></li>
               @foreach($labelsinfo as $singlelabel)
                  <li role="presentation" id="{{ $singlelabel[0] }}" class="{{ $label->id==$singlelabel[0]?'active':''}}"><a href="{{ route('label.show', $singlelabel[0]) }}" >{{ $singlelabel[2] }}<span class="badge">{{ $singlelabel[1] }}</span></a></li>
               @endforeach
            </ul>
         </div>
         <div class="panel-body">
               @include('threads._threads')
               {{ $threads->links() }}
         </div>
         @if (Auth::check())
         <div class="panel-heading">
            @if ($channel->channel_state == 1)
               <a class="btn btn-lg btn-primary sosad-button" href="{{ route('book.create') }}" role="button">发布文章</a>
            @else
               <a class="btn btn-lg btn-primary sosad-button" href="{{ route('thread.create', $channel) }}" role="button">发布主题</a>
            @endif
         </div>
         @else
         <div class="panel-heading text-center">
            <h4 class="display-1">请 <a href="{{ route('login') }}">登录</a> 后发布主题</h4>
         </div>
         @endif
      </div>
   </div>
</div>
@stop
