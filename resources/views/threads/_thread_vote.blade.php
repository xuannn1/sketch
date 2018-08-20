<div class="container-fluid thread-vote">
    <span>
      <button class="btn-sm sosad-button-thread" id="itemcollection{{$thread->id}}" onclick="item_add_to_collection({{$thread->id}},1,0)">
        <i class="fa fa-star"></i>
        收藏 {{ $thread->collection }}
      </button>
        @if((!$thread->noreply)&&(!$thread->locked)&&(($thread->public)||($thread->user_id==Auth::id())))
        <a class="btn-sm sosad-button-thread" href="#replyToThread">
            <i class="fa fa-comments"></i>
            回复
        </a>
        @endif
    </span>
    <span class="pull-right">
        <a class="btn-sm sosad-button-tag" href="#" data-toggle="modal" id="postshengfan{{$thread->post_id}}" data-target="#TriggerVoteForShengfan{{ $thread->mainpost->id }}">剩饭 {{ $thread->shengfan }}</a>

        <button class="btn-sm sosad-button-tag" id="threadxianyu{{$thread->id}}" onclick="thread_xianyu({{$thread->id}})">咸鱼 {{ $thread->xianyu }}</button>

        @if((Auth::user()->admin)||((!$thread->locked)&&(!$thread->noreply)&&(($thread->public)||($thread->user_id==Auth::id()))&&(Auth::user()->no_posting < Carbon\Carbon::now())))
        <a class="btn-sm sosad-button-tag" href="#" data-toggle="modal" data-target="#TriggerPostComment{{ $thread->mainpost->id }}">点评</a>
        @endif
        <a class="btn-sm sosad-button-tag" href="{{ route('download.index', $thread) }}">下载</a>
      </span>
</div>
    <!-- 已有的咸鱼和剩饭投掷 -->
<div class="smaller-10 thread-voters">
    @if(count($xianyus)>0)
        @foreach($xianyus as $xianyu)
        <a href="{{ route('user.show', $xianyu->user_id) }}" class="grayout">{{ $xianyu->creator->name }}</a>，
        @endforeach
        投掷了咸鱼<br>
    @endif
    @if(count($shengfans)>0)
        @foreach($shengfans as $shengfan)
        <a href="{{ route('user.show', $shengfan->user_id) }}" class="grayout">{{ $shengfan->creator->name }}</a>，
        @endforeach
        投掷了剩饭
    @endif
</div>


<div class="thread-edit">
    @if ((Auth::id() == $thread->user_id)&&(!$thread->locked))
      @if($thread->book_id != 0)
      <a class="btn-sm sosad-button-edit" href="{{ route('book.createchapter', $thread->book_id) }}">
        <i class="fa fa-plus"></i>
        新建章节
      </a>
      <a class="btn-sm sosad-button-edit" href="{{ route('book.edit', $thread->book_id) }}">
        <i class="fa fa-edit"></i>
        修改文案
      </a>
      @else
      <a class="btn-sm sosad-button-edit" href="{{ route('thread.edit', $thread->id) }}">编辑主楼</a>
      @endif

    @endif
</div>

<div class="modal fade shengfan-modal" id="TriggerVoteForShengfan{{ $thread->post_id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="form-group">
                <label for="shengfan">请输入您想投喂的剩饭数（-1~10）</label>
                <input name="shengfan_num" class=" form-control number" value="1" id="post{{$thread->post_id}}shengfan"></input>
                <small>提示：您的剩饭总数是{{ Auth::user()->shengfan }}</small>
            </div>
            <div class="pull-right">
                <button type="button" onclick="post_shengfan({{$thread->post_id}})" class="sosad-button-post btn-xs">投喂剩饭</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="TriggerPostComment{{ $thread->mainpost->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('postcomment.store', $thread->mainpost->id)}}" method="POST">
                {{ csrf_field() }}

                <div class="form-group">
                    <textarea name="body" rows="1" class="form-control comment-editor" placeholder="点评：" id="commentpost{{$thread->mainpost->id}}">
                    </textarea>
                    <button type="button" onclick="retrievecache('commentpost{{$thread->mainpost->id}}')" class="sosad-button-ghost text-left">
                      恢复数据
                    </button>
                    <span class="pull-right sosad-button-ghost">
                      字数统计：<span id="word-count-commentpost{{$thread->mainpost->id}}">0</span>
                    </span>
                </div>
                <div class="checkbox">
                    <input type="checkbox" name="anonymous" id="comment-anonymous" onclick="document.getElementById('majiacommentthread{{$thread->id}}').style.display = 'block'">
                    <label for="comment-anonymous" class="input-helper input-helper--checkbox">
                        马甲？
                    </label>
                    <div class="form-group text-right" id="majiacommentthread{{$thread->id}}" style="display:none">
                        <input type="text" name="majia" class="form-control" value="{{Auth::user()->majia ?:'匿名咸鱼'}}" placeholder="请输入不超过10字的马甲">
                        <label for="majia"><small>(马甲仅勾选“匿名”时有效)</small></label>
                    </div>
                </div>
                <div class="pull-right">
                    <button type="submit" class="sosad-button-post btn-sm" id="button-post-commentpost{{$thread->mainpost->id}}">点评</button>
                </div>
            </form>
        </div>
    </div>
</div>
