<!-- 对帖子进行打赏，投票，阅读等各种操作 -->
<div class="container-fluid thread-vote">
    @if($thread->user_id===Auth::id()&&!$thread->is_locked)
    <!-- 作者专区，编辑首楼和文案-->
    <div class="row text-left">
        @switch($thread->channel()->type)
        @case('book')
        <div class="col-xs-6">
            <a href="#" class="btn btn-lg btn-danger btn-block sosad-button-control">编辑书籍</a>
        </div>
        <div class="col-xs-6">
            <a href="#" class="btn btn-lg btn-danger btn-block sosad-button-control pull-right">写新章节</a>
        </div>
        @break
        @case('list')
        <div class="col-xs-6">
            <a href="#" class="btn btn-lg btn-danger btn-block sosad-button-control">编辑首楼</a>
        </div>
        <div class="col-xs-6">
            <a href="#" class="btn btn-lg btn-danger btn-block sosad-button-control pull-right">写新书评</a>
        </div>
        @break
        @default
        <div class="col-xs-6">
            <a href="#" class="btn btn-lg btn-danger btn-block sosad-button-control">编辑首楼</a>
        </div>
        @endswitch
    </div>
    <hr>
    @endif
    @if($thread->channel()->type !='thread')
    <!-- 点击阅读  书籍/收藏单/问题箱   -->
    <div class="row">
        @if($thread->channel()->type==='book')
        <div class="col-xs-6">
            <a href="{{ route('post.show', $thread->first_component_id) }}" class="btn btn-lg btn-danger btn-block sosad-button-control">开始阅读</a>
        </div>
        <div class="col-xs-6">
            <a href="{{ route('thread.chapter_index', $thread->id) }}" class="btn btn-lg btn-danger btn-block sosad-button-control pull-right">章节目录</a>
        </div>
        @elseif($thread->channel()->type==='list')
        <div class="col-xs-12 pull-right">
            <a href="{{ route('thread.review_index', $thread->id) }}" class="btn btn-lg btn-danger btn-block sosad-button-control pull-right">评论列表</a>
        </div>
        @endif
    </div>
    @endif
    @if(Auth::check())
    <hr>
    <!-- 操作按钮：收藏，回复，写独立评论，打赏 -->
    <div class="row">
        <div class="col-xs-3">
            <button class="btn btn-lg btn-success btn-block sosad-button" id="itemcollection{{$thread->id}}" onclick="add_to_collection({{$thread->id}})">收藏{{ $thread->collection_count }}</button>
        </div>
        <div class="col-xs-3">
            @if((!$thread->noreply)&&(!$thread->is_locked)&&(($thread->is_public)||($thread->user_id===Auth::id())))
            <a class="btn btn-lg btn-primary btn-block sosad-button" href="#replyToThread">回复</a>
            @endif
        </div>

        <div class="col-xs-3">
            <a class="btn btn-lg btn-danger btn-block sosad-button" href="#">写评</a>
        </div>

        <div class="col-xs-3">
            <a class="btn btn-lg btn-danger btn-block sosad-button" href="{{ route('reward.create', ['rewardable_type'=>'thread','rewardable_id'=>$thread->id]) }}">打赏</a>
        </div>
    </div>
    <br>
    @endif
    @if(!empty($thread->temp_rewards)&&count($thread->temp_rewards)>0)
    <!-- 打赏列表  -->
    <div class="grayout h5 text-left">
        新鲜打赏：
        @foreach($thread->temp_rewards as $reward)
        @if($reward->author)
        <a href="{{ route('user.show', $reward->user_id) }}">{{ $reward->author->name }},&nbsp;</a>
        @endif
        @endforeach
        &nbsp;&nbsp;总计:咸鱼{{ $thread->xianyu }},&nbsp;剩饭{{ $thread->shengfan }}
    </div>
    @endif
</div>
