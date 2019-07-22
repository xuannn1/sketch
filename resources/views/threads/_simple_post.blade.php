<div class="panel panel-default" id = "post{{ $post->id }}">
    <div class="panel-body post-body">
        @if((($post->is_bianyuan)||($thread->is_bianyuan))&&(!Auth::check()||(Auth::check()&&(Auth::user()->level < 1))))
        <div class="text-center">
            <h6 class="display-4 grayout"><a href="{{ route('login') }}">本内容为隐藏格式，只对1级以上注册用户开放，请登录或升级后查看</a></h6>
        </div>
        @else
        <!-- 回帖本体 -->
        <div class="main-text {{ $post->use_indentation? 'indentation':'' }}">
            @if($post->title)
            <div class="text-center">
                <strong>{{ $post->title }}</strong>
            </div>
            @endif

            <span id="full{{$post->id}}" class="hidden">
                @if($post->use_markdown)
                {!! StringProcess::sosadMarkdown($post->body) !!}
                @else
                {!! StringProcess::wrapParagraphs($post->body) !!}
                @endif
            </span>
            <span id="abbreviated{{$post->id}}">
                {!! StringProcess::trimtext($post->body,70) !!}
            </span>
            <a type="button" name="button" id="expand{{$post->id}}" onclick="expanditem('{{$post->id}}')">展开</a>
        </div>
        @endif
    </div>
</div>
