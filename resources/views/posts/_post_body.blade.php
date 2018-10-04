
<!-- 下面检查是否为未登录查看边缘的情况 -->
@if((($post->bianyuan)||(($thread->bianyuan)))&&(!Auth::check()))
<div class="text-center">
    <h6 class="display-4 grayout"><a href="{{ route('login') }}">本部分为隐藏格式，只对注册用户开放，请登录后查看</a></h6>
</div>
@else
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
        @if($post->maintext)
            <!-- 章节-标题 -->
            <div class="text-center h5">
                <strong>{{ $post->chapter->title }}</strong>
                <p class="grayout smaller-10">{{ $post->title }}</p>
                <span class="smaller-20"><span class="glyphicon glyphicon-pencil"></span>&nbsp;{{ $post->chapter->characters }} / <span class="glyphicon glyphicon-eye-open"></span>&nbsp;{{ $post->chapter->viewed }} / <span class="glyphicon glyphicon glyphicon-comment"></span>&nbsp;{{ $post->chapter->responded }}</span>
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
            <div class="text-center">
                <a class="sosad-button-tag" href="{{ route('book.showchapter', $post->chapter_id) }}">
                  <i class="fa fa-book"></i>
                  前往文库阅读
                </a>
            </div>
        @else
            <!-- 普通回帖展开式 -->
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
@endif
