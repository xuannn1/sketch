<div class="article-title">
   <span>
      <h2 class="inline-title"><a href="{{ route('thread.show',$thread->id) }}">{{ $thread->title }}</a><small>
      @if(!$thread->public)
      <span class="glyphicon glyphicon-eye-close"></span>
      @endif
      @if($thread->locked)
      <span class="glyphicon glyphicon-lock"></span>
      @endif
      @if($thread->noreply)
      <span class="glyphicon glyphicon-warning-sign"></span>
      @endif
      </small></h2>
   </span>
   @if((Auth::check())&&(Auth::user()->admin))
   @include('admin._modify_thread')
   @endif
   <div class="">
      <span class="">{{ $thread->brief }}</span>
   </div>
</div>
<!-- 作者信息 -->
<div class="article-body">
   <div class="text-center">
      <span>
         @if ($thread->anonymous)
            <span>{{ $thread->majia ?? '匿名咸鱼'}}</span>
            @if((Auth::check()&&(Auth::user()->admin)))
            <span class="admin-anonymous"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->creator->name }}</a></span>
            @endif
         @else
         <a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->creator->name }}</a>
         @endif
      </span>&nbsp;
      <span class="grayout">
         发表于{{ Carbon\Carbon::parse($thread->created_at)->diffForHumans() }}
         @if($thread->created_at < $thread->edited_at )
           修改于{{ Carbon\Carbon::parse($thread->edited_at)->diffForHumans() }}
         @endif
      </span>
   </div>
   <!-- 主题正文 -->
   <div class="main-text {{ $thread->mainpost->indentation ? 'indentation':'' }}">
      @if($thread->mainpost->markdown)
      {!! Markdown::convertToHtml($thread->body) !!}
      @else
      {!! Helper::wrapParagraphs($thread->body) !!}
      @endif
   </div>
   @if($thread->homework_id>0)
      @include('homeworks._registered_students')
      @if($thread->show_homework_profile)
      @include('homeworks._registered_homeworks')
      @else
      @include('homeworks._register_button')
      @endif
   @endif
</div>
