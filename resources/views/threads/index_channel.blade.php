@extends('layouts.default')
@section('title', $channel->channelname)
@section('content')
<div class="container-fluid">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
       <!-- 首页／版块／类型 -->
       <div class="site-map">
           <a href="{{ route('home') }}">
               <span><i class="fa fa-home"></i>&nbsp;首页</span>
           </a>
          &nbsp;/&nbsp;
          <a href="{{ route('channel.show', $channel->id) }}">{{ $channel->channelname }}</a>
       </div>
      <div class="panel panel-default">
         <div class="panel-heading">
            <ul class="nav nav-pills nav-fill nav-justified">
               <li role="presentation">
                   <a href="{{ route('channel.show', $channel) }}" class="{{ request('label') ? '': 'active' }} thread-title smaller-10">
                       全部<span class="badge"></span>
                   </a>
               </li>
               @foreach($labels as $label)
               <li role="presentation" id="{{ $label->id }}">
                   <a href="{{ route('channel.show',['channel'=>$channel->id,'label'=>$label->id]) }}#label-{{$label->id}}"  class="{{ request('label')==$label->id ? 'active':'' }} thread-title smaller-10" id="label-{{$label->id}}">
                       {{ $label->labelname }}<span class="badge">{{ $label->threads_count }}</span>
                   </a>
               </li>
               @endforeach
            </ul>
            @if($channel->channel_state==1)
            <ul class="nav nav-pills nav-fill">
                 @foreach($sexual_orientation_info as $key=>$value)
                     @if(!$key==0)
                     <li role="presentation" id="sexual_orientation-{{ $key }}">
                         <a href="{{ route('channel.show',['channel'=>$channel->id, 'sexual_orientation' => $key ]) }}" class="{{ request('sexual_orientation')== $key ? 'active':'' }} thread-title smaller-10" id="sexual_orientation-{{ $key }}">
                             {{ $value }}<span class="badge">{{ array_key_exists($key, $s_count)? $s_count[$key]:'0' }}</span>
                         </a>
                     </li>
                     @endif
                 @endforeach
            <ul>
             @endif
         </div>
         <div class="panel-body">
               {{ $threads->appends(request()->query())->links() }}
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
                     <i class="fas fa-feather-alt"></i>
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
