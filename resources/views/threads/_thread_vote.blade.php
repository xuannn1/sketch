<div class="container-fluid thread-vote">
    <span>
        @if((!$thread->noreply)&&(!$thread->locked)&&(($thread->public)||($thread->user_id==Auth::id())))
        <a class="btn btn-sm btn-primary sosad-button" href="#replyToThread">回复</a>
        @endif
        <button class="btn btn-sm btn-success sosad-button" id="itemcollection{{$thread->id}}" onclick="item_add_to_collection({{$thread->id}},1,0)">收藏{{ $thread->collection }}</button>
    </span>
    <span>
        @if ((Auth::id() == $thread->user_id)&&((!$thread->locked)||(Auth::user()->admin)))
        @if($thread->book_id != 0)
        <a class="btn btn-sm btn-warning sosad-button" href="{{ route('book.createchapter', $thread->book_id) }}">新建章节</a>
        <a class="btn btn-sm btn-danger sosad-button" href="{{ route('book.edit', $thread->book_id) }}">修改文案</a>
        @else
        <a class="btn btn-sm btn-danger sosad-button" href="{{ route('thread.edit', $thread->id) }}">编辑主楼</a>
        @endif

        @endif
    </span>
    <span class="pull-right">
        <a class="btn btn-sm btn-default" href="#" data-toggle="modal" id="postshengfan{{$thread->post_id}}" data-target="#TriggerVoteForShengfan{{ $thread->mainpost->id }}">剩饭{{ $thread->shengfan }}</a>

        <button class="btn btn-sm btn-default" id="threadxianyu{{$thread->id}}" onclick="thread_xianyu({{$thread->id}})">咸鱼{{ $thread->xianyu }}</button>
    </span>
</div>

<div class="modal fade shengfan-modal" id="TriggerVoteForShengfan{{ $thread->post_id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="form-group">
                <label for="shengfan">请输入您想投喂的剩饭数（-1~10）</label>
                <small>提示：您的剩饭总数是{{ Auth::user()->shengfan }}</small>
                <input name="shengfan_num" class="number" value="1" id="post{{$thread->post_id}}shengfan"></input>
            </div>
            <div class="">
                <button type="button" onclick="post_shengfan({{$thread->post_id}})" class="btn btn-primary sosad-button btn-sm">投喂剩饭</button>
            </div>
        </div>
    </div>
</div>
@if(count($xianyus)>0)
<div class="grayout h6">
    新鲜咸鱼：
    @foreach($xianyus as $xianyu)
        <a href="{{ route('user.show', $xianyu->user_id) }}">{{ $xianyu->creator->name }}，</a>
    @endforeach
</div>
@endif
