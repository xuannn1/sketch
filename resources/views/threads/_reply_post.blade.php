<div class="panel panel-default" id = "post{{ $post->id }}">
    <div class="panel-body post-body">
        @if((($post->is_bianyuan)||($thread->is_bianyuan))&&(!Auth::check()||(Auth::check()&&(Auth::user()->level < 1))))
        <div class="text-center">
            <h6 class="display-4 grayout"><a href="{{ route('login') }}">本内容为隐藏格式，只对1级以上注册用户开放，请登录或升级后查看</a></h6>
        </div>
        @else
        <div class="panel-heading">
            <div class="row">
                <!-- post的基本信息：作者，时间，post_id -->
                <div class="col-xs-12">
                    <span class="font-6 bianyuan-tag badge-tag">
                        被回复贴
                    </span>
                    <!-- post编号 -->
                    <span class="pull-right smaller-30">
                        <a href="{{ route('post.show', $post->id) }}">
                            {{ $post->type==='question'?'Q.':'' }}{{ $post->type==='anwer'?'A.':'' }}{{ $post->type==='review'?'R.':'' }}{{ $post->type==='post'?'P.':'' }}{{ $post->type==='comment'?'C.':'' }}{{ $post->id }}
                        </a>
                    </span>
                </div>
            </div>
        </div>
        <!-- 回帖本体 -->
        <div class="main-text {{ $post->use_indentation? 'indentation':'' }}">
            @if($post->title)
            <div class="text-center">
                <strong>{{ $post->title }}</strong>
            </div>
            @endif

            <span id="full{{$post->id}}" class="hidden">
                @if( (($thread->is_bianyuan)||($post->is_bianyuan))&&(!Auth::check()) )
                <div class="text-center">
                    <h6 class="display-4 grayout"><a href="route('login')">本内容只对注册用户开放，请登陆后查看</a></h6>
                </div>
                @elseif( (!$thread->recommended)&&($thread->channel()->type==='book')&&($thread->is_bianyuan)&&($post->type==='chapter')&&(Auth::check())&&(Auth::user()->level < 3)&&(Auth::id()!=$post->user_id) )
                <div class="text-center">
                    <h6 class="display-4 grayout">本内容为非编推的边限文的正文章节，只对3级以上注册用户开放，请升级后查看</a></h6>
                </div>
                @elseif( (!$thread->recommended)&&($thread->channel()->type==='book')&&(!$thread->is_bianyuan)&&($post->is_bianyuan)&&($post->type==='chapter')&&(Auth::check())&&(Auth::user()->level < 2)&&(Auth::id()!=$post->user_id) )
                <div class="text-center">
                    <h6 class="display-4 grayout">本内容为非编推的非边限文的单章限制章节，只对2级以上注册用户开放，请升级后查看</a></h6>
                </div>
                @elseif( (!$thread->recommended)&&($thread->channel()->type!='book')&&($thread->is_bianyuan||$post->is_bianyuan)&&(Auth::check())&&(Auth::user()->level < 1)&&(Auth::id()!=$post->user_id) )
                <div class="text-center">
                    <h6 class="display-4 grayout">本内容为限制讨论，只对1级以上注册用户开放，请升级后查看</a></h6>
                </div>
                @else
                    @if($post->use_markdown)
                    {!! StringProcess::sosadMarkdown($post->body) !!}
                    @else
                    {!! StringProcess::wrapParagraphs($post->body) !!}
                    @endif
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
