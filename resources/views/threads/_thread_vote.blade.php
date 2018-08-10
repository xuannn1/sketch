<div class="container-fluid thread-vote">
    <span>
        @if((!$thread->noreply)&&(!$thread->locked)&&(($thread->public)||($thread->user_id==Auth::id())))
        <a class="btn btn-sm btn-primary sosad-button-thread" href="#replyToThread">
            <i class="fa fa-comments"></i>
            回复
        </a>
        @endif
        <button class="btn btn-sm btn-success sosad-button-thread" id="itemcollection{{$thread->id}}" onclick="item_add_to_collection({{$thread->id}},1,0)">
            <i class="fa fa-star"></i>
            收藏 {{ $thread->collection }}
        </button>
    </span>
    <span>
        @if ((Auth::id() == $thread->user_id)&&(!$thread->locked))
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

        @if((Auth::user()->admin)||((!$thread->locked)&&(!$thread->noreply)&&(($thread->public)||($thread->user_id==Auth::id()))&&(Auth::user()->no_posting < Carbon\Carbon::now())))
        <a class="btn btn-sm btn-default" href="#" data-toggle="modal" data-target="#TriggerPostComment{{ $thread->mainpost->id }}">点评</a>
        @endif
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
<div class="container-fluid">
    <!-- 已有的咸鱼和剩饭投掷 -->
    <div class="h6 grayout">
        @if(count($xianyus)>0)
            @foreach($xianyus as $xianyu)
            <a href="{{ route('user.show', $xianyu->user_id) }}">{{ $xianyu->creator->name }}</a>，
            @endforeach
            投掷了咸鱼<br>
        @endif
        @if(count($shengfans)>0)
            @foreach($shengfans as $shengfan)
            <a href="{{ route('user.show', $shengfan->user_id) }}">{{ $shengfan->creator->name }}</a>，
            @endforeach
            投掷了剩饭
        @endif
    </div>
</div>

<div class="modal fade" id="TriggerPostComment{{ $thread->mainpost->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('postcomment.store', $thread->mainpost->id)}}" method="POST">
                {{ csrf_field() }}

                <div class="form-group">
                    <textarea name="body" rows="1" class="form-control" placeholder="点评：" id="commentpost{{$thread->mainpost->id}}"></textarea>
                    <button type="button" onclick="retrievecache('commentpost{{$thread->mainpost->id}}')" class="sosad-button-control addon-button">恢复数据</button>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name="anonymous" onclick="document.getElementById('majiacommentthread{{$thread->id}}').style.display = 'block'">马甲？</label>
                    <div class="form-group text-right" id="majiacommentthread{{$thread->id}}" style="display:none">
                        <input type="text" name="majia" class="form-control" value="{{Auth::user()->majia ?:'匿名咸鱼'}}" placeholder="请输入不超过10字的马甲">
                        <label for="majia"><small>(马甲仅勾选“匿名”时有效)</small></label>
                    </div>
                </div>
                <div class="">
                    <button type="submit" class="btn btn-primary sosad-button btn-sm">点评</button>
                </div>
            </form>
        </div>
    </div>
</div>
