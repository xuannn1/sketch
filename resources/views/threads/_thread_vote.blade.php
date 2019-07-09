<div class="container-fluid thread-vote">
    <span>
        @if((!$thread->noreply)&&(!$thread->locked)&&(($thread->public)||($thread->user_id===Auth::id())))
        <a class="btn btn-sm btn-primary sosad-button" href="#replyToThread">回复</a>
        @endif
        <button class="btn btn-sm btn-success sosad-button" id="itemcollection{{$thread->id}}" onclick="item_add_to_collection({{$thread->id}},1,0)">收藏{{ $thread->collection_count }}</button>
    </span>
    <span>
        @if(Auth::id() === $thread->user_id)
            <a class="btn btn-sm btn-danger sosad-button" href="{{ route('thread.edit', $thread->id) }}">修改</a>
        @endif
    </span>
    <span class="pull-right">
        <a class="btn btn-sm btn-danger sosad-button" href="{{ route('reward.create', ['rewardable_type'=>'thread','rewardable_id'=>$thread->id]) }}">打赏</a>
    </span>
</div>

@if($thread->temp_rewards)
<div class="grayout h6">
    新鲜打赏：
    @foreach($thread->temp_rewards as $reward)
        @if($reward->author)
            <a href="{{ route('user.show', $reward->user_id) }}">{{ $reward->author->name }},&nbsp;</a>
        @endif
    @endforeach
    &nbsp;&nbsp;总计:咸鱼{{ $thread->xianyu }},&nbsp;剩饭{{ $thread->shengfan }}
</div>
@endif
