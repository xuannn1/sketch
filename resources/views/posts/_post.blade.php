<div class="" id = "post{{ $post->id }}">
    @if($post->fold_state==1)
    <div class="text-center">
        <a type="button" data-toggle="collapse" data-target="#postbody{{ $post->id }}" style="cursor: pointer;" class="h6">该回帖被管理员折叠，点击展开</a>
    </div>
    @elseif($post->fold_state==2)
        <a type="button" data-toggle="collapse" data-target="#postbody{{ $post->id }}" style="cursor: pointer;" class="h6">该回帖被作者/楼主折叠，点击展开</a>
    @endif
    <div class="panel panel-default {{ $post->fold_state>0? 'collapse':'' }} " id = "postbody{{ $post->id }}">
        <div class="panel-heading">
            <div class="row">
                <!-- post的基本信息：作者，时间，post_id -->
                <div class="col-xs-12">
                    <span class="font-5">
                        <!-- 显示作者名称 -->
                        @if($post->author)
                            @if ($post->type==="chapter")
                                <span class="font-6 bianyuan-tag badge-tag">作者</span>
                            @elseif ($post->type==="review")
                                <span class="font-6  bianyuan-tag badge-tag">单主</span>
                            @elseif ($post->type==="answer")
                                <span class="font-6  bianyuan-tag badge-tag">答主</span>
                            @else
                                @if ($post->is_anonymous)
                                    <span>{{ $post->majia ?? '匿名咸鱼'}}</span>
                                    @if((Auth::check()&&(Auth::user()->isAdmin())))
                                    <span class="admin-anonymous"><a href="{{ route('user.show', $post->user_id) }}">{{ $post->author->name }}</a></span>
                                    @endif
                                @else
                                    <a href="{{ route('user.show', $post->user_id) }}">
                                        @if($post->author->title&&$post->author->title->name)
                                        <span class="maintitle title-{{$post->author->title->style_id}}">{{ $post->author->title->name }}</span>
                                        @endif
                                        {{ $post->author->name }}
                                    </a>
                                @endif
                            @endif
                        @endif
                        <!-- 只看该用户 -->
                        @if(($post->user_id>0)&&(!$post->is_anonymous)&&((!$thread->is_anonymous)||(($post->type==='post')||($post->type==='comment'))))
                            <span class="grayout smaller-30"><a href="{{ route('thread.show', ['thread'=>$thread->id, 'userOnly'=>$post->user_id]) }}">只看该用户</a></span>
                        @endif
                        <!-- 发表时间 -->
                        <span class="grayout smaller-30">
                            {{ $post->created_at? $post->created_at->diffForHumans():'' }}
                            @if($post->created_at < $post->edited_at )
                            /{{ $post->edited_at? $post->edited_at->diffForHumans():'' }}
                            @endif
                        </span>&nbsp;

                        @if((Auth::check())&&(Auth::user()->isAdmin()))
                        <!-- 管理员标志 -->
                        <span>
                            <span><a href="#" data-id="{{$post->id}}" data-toggle="modal" data-target="#TriggerPostAdministration{{ $post->id }}" class="btn btn-default btn-sm admin-button">管理本帖</a></span>
                            @include('admin._post_management_form')
                        </span>
                        @endif

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
        <?php $show_post_mode='thread'; ?>
        @include('posts._post_body')
        @if(Auth::check())
        @include('posts._post_action')
        @endif

        @if ($post->last_reply)
        <div class="panel-footer">
            <div class="smaller-20" id="postcomment{{$post->last_reply_id}}">
                <a href="{{ route('post.show', $post->last_reply_id) }}" class="grayout">最新回复：{{ $post->last_reply->brief }}</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="{{ route('thread.show', ['thread' => $post->thread_id, 'withReplyTo' => $post->id, 'withComponent'=>'include_comment']) }}" class="grayout">>>本层全部回帖</a>&nbsp;&nbsp;
                <a href="{{ route('thread.show', ['thread' => $thread->id, 'inComponent' => $post->in_component_id>0?$post->in_component_id:$post->id, 'withComponent'=>'include_comment']) }}" class="grayout">>>所有相关讨论</a>
            </div>
        </div>
        @endif
    </div>
</div>
