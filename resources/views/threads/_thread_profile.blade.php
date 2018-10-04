<!-- 标题部分 -->
<div class="article-title text-center">
    <h2>
        @include('threads._thread_title')
        @if((Auth::check())&&(Auth::user()->admin))
        @include('admin._modify_thread')
        @endif
    </h2>
</div>
<!-- 一句话简介 -->

<div class="article-body">
    <div class="text-center">{{ $thread->brief }}</div>
    <div class="text-center">
        @include('threads._thread_author_time')
    </div>
   <!-- 首楼正文 -->
   <div class="main-text {{ $thread->mainpost->indentation ? 'indentation':'' }}">
      @if($thread->mainpost->markdown)
      {!! Helper::sosadMarkdown($thread->mainpost->body) !!}
      @else
      {!! Helper::wrapParagraphs($thread->mainpost->body) !!}
      @endif
   </div>
   <!-- 是否附加作业信息 -->
   @if($thread->homework_id>0)
      @include('homeworks._registered_students')
      @if($thread->show_homework_profile)
        @include('homeworks._registered_homeworks')
      @else
        @include('homeworks._register_button')
        @endif
    @endif
    <!-- 这个地方，是整楼的信息汇总：总字数，阅读数，回应数，下载数 -->
    <div class="">
        <span class = "pull-right smaller-10"><em><span class="glyphicon glyphicon-eye-open"></span>{{ $thread->viewed }}/<span class="glyphicon glyphicon-comment"></span>{{ $thread->responded }}/<span class="glyphicon glyphicon-save"></span>{{ $thread->downloaded }}</em>&nbsp;&nbsp;<span><a href="{{ route('download.index', $thread) }}">下载</a>
        </span></span>

    </div>
</div>
