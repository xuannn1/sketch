<!-- 回复他人帖子的相关信息 -->
@if($post->reply_to_post_id!=0)
<div class="post-reply grayout">
    回复&nbsp;<a href="{{ route('thread.showpost', $post->reply_to_post_id) }}">{{ $post->reply_to_post->anonymous ? ($post->reply_to_post->majia ?? '匿名咸鱼') : $post->reply_to_post->owner->name }}&nbsp;{{ Helper::trimtext($post->reply_to_post->title . $post->reply_to_post->body, 20) }}</a>
</div>
@elseif(($post->chapter_id!=0)&&(!$post->maintext)&&($chapter_replied)&&($post->chapter->mainpost->id>0))
<div class="post-reply grayout">
    评论&nbsp;<a href="{{ route('book.showchapter', $post->chapter_id) }}">{{ Helper::trimtext( $post->chapter->title . $post->chapter->mainpost->title . $post->chapter->mainpost->body , 20) }}</a>
</div>
@endif

<div class="main-text {{ $post->indentation? 'indentation':'' }}">
    @if(($post->maintext)&&($thread->channel->channel_state==1))
    <!-- 章节-标题 -->
    <div class="text-center h5">
        <strong>{{ $post->chapter->title }}</strong>
        <p class="grayout smaller-10">{{ $post->title }}</p>
    </div>
    <!-- 章节-正文 -->
    <div>
        @if($post->markdown)
        {!! Helper::sosadMarkdown($post->body) !!}
        @else
        {!! Helper::wrapParagraphs($post->body) !!}
        @endif
        <br>
    </div>
    <!-- 章节-注释 -->
    @if($post->chapter->annotation)
    <div class="grayout">
        <br>
        {!! Helper::wrapParagraphs($post->chapter->annotation) !!}
        <br>
    </div>
    @endif
    <!-- 章节-数据统计 -->
    <div class="container-fluid">
        <u><a class="smaller-10" href="{{ route('book.showchapter', $post->chapter_id) }}">前往文库阅读</a></u>
        <span class="pull-right smaller-20"><em><span class="glyphicon glyphicon-pencil"></span>{{ $post->chapter->characters }}/<span class="glyphicon glyphicon-eye-open"></span>{{ $post->chapter->viewed }}/<span class="glyphicon glyphicon glyphicon-comment"></span>{{ $post->chapter->responded }}</em></span>
    </div>
    @else
    <!-- 普通章节展开式 -->
    @if($post->title)
    <strong>{{ $post->title }}</strong>
    @endif
    @if($post->markdown)
    {!! Helper::sosadMarkdown($post->body) !!}
    @else
    {!! Helper::wrapParagraphs($post->body) !!}
    @endif
    @endif
</div>
