<!-- 对帖子进行打赏，投票，阅读等各种操作 -->
<div class="container-fluid thread-vote">
    @if($thread->user_id===Auth::id()&&!$thread->is_locked)
    <!-- 作者专区，编辑首楼和文案-->
    <div class="row text-left">
        @switch($thread->channel()->type)
        @case('book')
        <div class="col-xs-6">
            <a href="{{ route('books.edit', $thread->id) }}" class="btn btn-md btn-danger btn-block sosad-button-control">编辑书籍</a>
        </div>
        <div class="col-xs-6">
            <a href="{{ route('chapter.create', $thread->id) }}" class="btn btn-md btn-danger btn-block sosad-button-control pull-right">写新章节</a>
        </div>
        @break
        @case('list')
        <div class="col-xs-6">
            <a href="{{ route('threads.edit', $thread->id) }}" class="btn btn-md btn-danger btn-block sosad-button-control">编辑首楼</a>
        </div>
        <div class="col-xs-6">
            <a href="{{ route('review.create', $thread->id) }}" class="btn btn-md btn-danger btn-block sosad-button-control pull-right">写新书评</a>
        </div>
        @break
        @default
        <div class="col-xs-6">
            <a href="{{ route('threads.edit', $thread->id) }}" class="btn btn-md btn-danger btn-block sosad-button-control">编辑首楼</a>
        </div>
        @endswitch
    </div>
    <hr class="brief-0">
    @endif
    @if($thread->channel()->type !='thread')
    <!-- 点击阅读  书籍/收藏单/问题箱   -->
    <div class="row">
        @if($thread->channel()->type==='book')
        <div class="col-xs-6">
            @if($thread->first_component_id===0)
                <a href="#" class="btn btn-md btn-danger btn-block sosad-button-control disabled">尚无正文</a>
            @else
                <a href="{{ route('post.show', $thread->first_component_id) }}" class="btn btn-md btn-danger btn-block sosad-button-control">开始阅读</a>
            @endif

        </div>
        <div class="col-xs-6">
            <a href="{{ route('thread.chapter_index', $thread->id) }}" class="btn btn-md btn-danger btn-block sosad-button-control pull-right">章节目录</a>
        </div>
        @elseif($thread->channel()->type==='list')
        <div class="col-xs-12 pull-right">
            <a href="{{ route('thread.review_index', $thread->id) }}" class="btn btn-md btn-danger btn-block sosad-button-control pull-right">评论列表</a>
        </div>
        @endif
    </div>
    @endif
    @if(Auth::check())
    <hr>
    <!-- 操作按钮：收藏，回复，写独立评论，打赏 -->
    <div class="row">
        <div class="col-xs-3">
            <button class="btn btn-md btn-success btn-block sosad-button" id="itemcollection{{$thread->id}}" onclick="add_to_collection({{$thread->id}})">收藏<span class="smaller-10">{{$thread->collection_count}}</span></button>
        </div>
        <div class="col-xs-3">
            @if((!$thread->noreply)&&(!$thread->is_locked)&&(($thread->is_public)||($thread->user_id===Auth::id())))
            <a class="btn btn-md btn-primary btn-block sosad-button" href="#replyToThread">回复<span class="smaller-10">{{$thread->reply_count}}</span></a>
            @endif
        </div>

        <div class="col-xs-3">
            <a class="btn btn-md btn-danger btn-block sosad-button" href="#">写评</a>
        </div>

        <div class="col-xs-3">
            <a href="#" data-id="{{$thread->id}}" data-toggle="modal" data-target="#TriggerThreadReward{{ $thread->id }}" class="btn btn-md btn-primary btn-block sosad-button">打赏</a>
        </div>
        <div class="modal fade" id="TriggerThreadReward{{ $thread->id }}" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('reward.store')}}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h3>打赏专区</h3>
                            <h6>(对同一帖一天内只能打赏一次哦！)</h6>
                            <div class="">
                                <label><input type="radio" name="reward_type" value="salt">盐粒(余额{{$info->salt}})</label>
                            </div>
                            <div class="">
                                <label><input type="radio" name="reward_type" value="fish" checked>咸鱼(余额{{$info->fish}})</label>
                            </div>
                            <div class="">
                                <label><input type="radio" name="reward_type" value="ham">火腿(余额{{$info->ham}})</label>
                            </div>
                            <hr>
                            <div class="">
                                <label><input type="text" style="width: 40px" name="reward_value" value="1">数额（1～100）</label>
                            </div>
                            <hr>
                            <label><input name="rewardable_type" value="thread" class="hidden"></label>
                            <label><input name="rewardable_id" value="{{$thread->id}}" class="hidden"></label>
                            <div class="text-right">
                                <button type="submit" class="btn btn-md btn-primary sosad-button">打赏</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if(!empty($thread->recent_rewards)&&count($thread->recent_rewards)>0)
    <!-- 打赏列表  -->
    <div class="grayout h5 text-left smaller-20">
        新鲜打赏：
        @foreach($thread->recent_rewards as $reward)
        @if($reward->author)
        <a href="{{ route('user.show', $reward->user_id) }}">{{ $reward->author->name }},&nbsp;</a>
        @endif
        @endforeach
        &nbsp;&nbsp;总计：盐粒{{ $thread->salt }}，咸鱼{{ $thread->fish }}，火腿{{ $thread->ham }} <a href="{{route('reward.index', ['rewardable_type'=>'thread', 'rewardable_id'=>$thread->id])}}">&nbsp;&nbsp;>>全部打赏列表</a>
    </div>
    @endif
</div>
