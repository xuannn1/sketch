<!-- 标题部分 -->
<div class="article-title">
    <h2>
        @include('threads._thread_title')
        @if((Auth::check())&&(Auth::user()->admin))
        @include('admin._modify_thread')
        @endif
    </h2>
</div>
<!-- 一句话简介 -->

<div class="article-body">
    <div>{{ $thread->brief }}</div>
    <div class="text-center">
        @include('threads._thread_author_time')
    </div>
    <!-- 首楼正文 -->
    <div class="main-text {{ $thread->mainpost->indentation ? 'indentation':'' }}">
        @if($thread->mainpost->markdown)
        {!! Helper::sosadMarkdown($thread->body) !!}
        @else
        {!! Helper::wrapParagraphs($thread->body) !!}
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
</div>
