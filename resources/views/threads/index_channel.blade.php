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
            <ul class="nav nav-tabs">
               <li role="presentation" class="{{ request('label') ? '': 'active' }}"><a href="{{ route('channel.show', $channel) }}">全部<span class="badge"></span></a></li>
               @foreach($labels as $label)
               <li role="presentation" id="label-{{ $label->id }}" class="{{ request('label')===$label->id ? 'active':'' }}">
                   <a href="{{ route('channel.show',['channel'=>$channel->id,'label'=>$label->id]) }}" >
                       {{ $label->labelname }}<span class="badge">{{ $label->threads_count }}</span>
                   </a>
               </li>
               @endforeach
               @foreach($sexual_orientation_info as $key=>$value)
               <li role="presentation" id="sexual_orientation-{{ $key }}" class="{{ request('sexual_orientation')=== $key ? 'active':'' }}">
                   <a href="{{ route('channel.show',['channel'=>$channel->id, 'sexual_orientation' => $key ]) }}" >
                       {{ $value }}<span class="badge">{{ array_key_exists($key, $s_count)? $s_count[$key]:'0' }}</span>
                   </a>
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
         <div class="panel-heading">
           @if(Auth::user()->no_posting > Carbon\Carbon::now())
            <h6 class="text-center">您被禁言至{{ Carbon\Carbon::parse(Auth::user()->no_posting)->diffForHumans() }}，暂时不能发帖。</h6>
           @else
              @if ($channel->channel_state == 1)
                 <a class="btn btn-lg btn-primary sosad-button" href="{{ route('book.create') }}" role="button">发布文章</a>
              @else
                 <a class="btn btn-lg btn-primary sosad-button" href="{{ route('thread.create', $channel) }}" role="button">发布主题</a>
              @endif
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
