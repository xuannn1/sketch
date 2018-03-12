@extends('layouts.default')
@section('title', $thread->title)

@section('content')
<div class="container-fluid">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      @include('shared.errors')
      <div class="panel panel-default">
         <div class="panel-heading">
            <a type="btn btn-primary" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span>&nbsp;<span>首页</span></a>&nbsp;/&nbsp;
            <a href="{{ route('channel.show', $thread->channel_id) }}">{{ $thread->channel->channelname }}</a>
            <h1><a class="btn btn-warning sosad-button" href="{{ route('label.show', $thread->label) }}">{{ $thread->label->labelname }}</a>&nbsp;<a href="{{ route('thread.show',$thread) }}">{{ $thread->title }}</a></h1>
            @if ($thread->anonymous)
               <p>{{ $thread->majia ?? '匿名咸鱼'}}</p>
               @if((Auth::check()&&(Auth::user()->admin)))
               <p class="admin-anonymous"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->creator->name }}</a></p>
               @endif
            @else
               <p>作者：<a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->creator->name }}</a></p>
            @endif
            <p>简介：{{ $thread->brief }}</p>
            <div class="grayout">
               发表于 {{ Carbon\Carbon::parse($thread->created_at)->diffForHumans() }}
               @if($thread->created_at < $thread->edited_at )
                 修改于 {{ Carbon\Carbon::parse($thread->edited_at)->diffForHumans() }}
               @endif
            </div>
            @if($thread->post_id == $post->id)
               <div class="">
                  @if($thread->mainpost->markdown)
                  {!! Helper::sosadMarkdown($thread->body) !!}
                  @else
                  {!! Helper::wrapParagraphs($thread->body) !!}
                  @endif
               </div>
               @if($thread->book_id!=0)
                  <div><a href="{{ route('book.show', $thread->book_id) }}">文库阅读模式</a></div>
               @endif
               @if(Auth::check())
                  @include('threads._thread_vote')
               @else
                  <h6 class="display-4">请 <a href="{{ route('login') }}">登录</a> 后参与讨论</h6>
               @endif
            @endif
         </div>
      </div>
      <div class="panel panel-default" id = "post{{ $post->id }}">
         @if($thread->post_id != $post->id)
            <div class="panel-heading">
               <div class="row">
                  <div class="col-xs-12">
                     <span>
                        @if ($post->maintext)
                           @if ($thread->anonymous)
                              <p>{{ $thread->majia ?? '匿名咸鱼'}}</p>
                              @if((Auth::check()&&(Auth::user()->admin)))
                              <p class="admin-anonymous"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->creator->name }}</a></p>
                              @endif
                           @else
                              <a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->creator->name }}</a>
                           @endif
                        @else
                           @if ($post->anonymous)
                              <p>{{ $thread->majia ?? '匿名咸鱼'}}</p>
                              @if((Auth::check()&&(Auth::user()->admin)))
                              <p class="admin-anonymous"><a href="{{ route('user.show', $post->user_id) }}">{{ $post->owner->name }}</a></p>
                              @endif
                           @else
                              <a href="{{ route('user.show', $post->user_id) }}">{{ $post->owner->name }}</a>
                           @endif
                        @endif
                        <span class="grayout">
                           发表于 {{ Carbon\Carbon::parse($post->created_at)->diffForHumans() }}
                           @if($post->created_at < $post->edited_at )
                             修改于 {{ Carbon\Carbon::parse($post->edited_at)->diffForHumans() }}
                           @endif
                        </span>
                     </span>
                  </div>
               </div>
            </div>
            <div class="panel-body">
               @if((Auth::check())&&(Auth::user()->admin))
               @include('admin._delete_post')
               @endif
               @if($post->reply_to_post_id>0)
                     <div class="panel-body grayout">
                        回复&nbsp;<a href="{{ route('user.show', $post->reply_to_post->owner->id) }}">{{ $post->reply_to_post->owner->name }}</a>{{ Carbon\Carbon::parse($post->reply_to_post->created_at)->diffForHumans() }}：<a href="{{ route('thread.showpost', $post->reply_to_post) }}">{{ $post->reply_to_post->trim($post->reply_to_post->body, 15) }}</a>
                     </div>
               @endif
               @if($post->maintext)
               <div class="main-text {{ $post->indentation? 'indentation':'' }}">
                  {!! Helper::wrapParagraphs($post->body) !!}
                  <br>
               </div>
               <h6 class = "grayout">
                  <br>
                  {!! Helper::sosadMarkdown($post->chapter->annotation) !!}
               </h6>
               @else
               <strong>{{ $post->title }}</strong>
               {!! Helper::sosadMarkdown($post->body) !!}
               @endif
            </div>
            @if(Auth::check())
               <div class="panel-body text-right">
                  @include('posts._post_vote')
               </div>
            @endif
         @endif
         <div class="panel-footer">
            @foreach($postcomments as $comment_no=>$postcomment)
                  @include('posts._post_comment')
            @endforeach
            {{ $postcomments->links() }}
         </div>
      </div>
      @if(auth()->check())
         @include('threads._reply')
      @endif
   </div>
</div>
@stop
