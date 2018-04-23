@extends('layouts.default')
@section('title', $channel->channelname)
@section('content')
<div class="container-fluid">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
       <!-- 首页／版块／类型 -->
       <div class="site-map">
          <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
          &nbsp;/&nbsp;
          <a href="{{ route('channel.show', $channel->id) }}">{{ $channel->channelname }}</a>
       </div>
      <div class="panel panel-default">
         <div class="panel-heading">
            <ul class="nav nav-pills nav-fill nav-justified">
               <li role="presentation" class="{{ request('label') ? '': 'active' }}"><a href="{{ route('channel.show', $channel) }}">全部<span class="badge"></span></a></li>
               @foreach($labels as $label)
               <li role="presentation" id="{{ $label->id }}" class="{{ request('label')===$label->id ? 'active':'' }}">
                   <a href="{{ route('channel.show',['channel'=>$channel->id,'label'=>$label->id]) }}" >
                       {{ $label->labelname }}<span class="badge">{{ $label->threads_count }}</span>
                   </a>
               </li>
               @endforeach
            </ul>
         </div>
         <div class="panel-body">
               @include('threads._threads')
               {{ $threads->appends(request()->query())->links() }}
         </div>
         @if (Auth::check())
         <div class="panel-footer">
           @if(Auth::user()->no_posting > Carbon\Carbon::now())
            <h6 class="text-center">您被禁言至{{ Carbon\Carbon::parse(Auth::user()->no_posting)->diffForHumans() }}，暂时不能发帖。</h6>
           @else
              @if ($channel->channel_state == 1)
                 <a class="btn btn-primary sosad-button-post" href="{{ route('book.create') }}" role="button">
                     <i class="fa fa-book"></i>
                     发布文章
                 </a>
              @else
                 <a class="btn btn-primary sosad-button-post" href="{{ route('thread.create', $channel) }}" role="button">
                     <i class="fa fa-plus"></i>
                     发布主题
                 </a>
              @endif
           @endif
         </div>
         @else
         <div class="panel-footer text-center">
            <h4 class="display-1">请 <a href="{{ route('login') }}">登录</a> 后发布主题</h4>
         </div>
         @endif
      </div>
   </div>
</div>
@stop
