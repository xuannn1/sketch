<div class="container-fluid thread-vote">
   <span>
      @if((!$thread->noreply)&&(!$thread->locked)&&(($thread->public)||($thread->user_id==Auth::id())))
      <a class="btn btn-sm btn-primary sosad-button" href="#replyToThread">回复</a>
      @endif
      <a href="{{ route('collection.store', $thread->id) }}" class="btn btn-sm btn-success sosad-button">收藏{{$thread->collection}}</a>
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
      <a class="btn btn-sm btn-default" href="#" data-toggle="modal" data-target="#TriggerVoteForShengfan{{ $thread->mainpost->id }}">剩饭{{ $thread->shengfan }}</a>
      <a class="btn btn-sm btn-default" href="{{ route('xianyu.vote', $thread->id)}}" >咸鱼{{ $thread->xianyu }}</a>
      @if((!$thread->locked)&&(!$thread->noreply)&&(($thread->public)||($thread->user_id==Auth::id()))&&(Auth::user()->no_posting < Carbon\Carbon::now()))
      <a class="btn btn-sm btn-default" href="#" data-toggle="modal" data-target="#TriggerPostComment{{ $thread->mainpost->id }}">点评</a>
      @endif
   </span>
</div>

<div class="modal fade" id="TriggerVoteForShengfan{{ $thread->mainpost->id }}" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
         <form action="{{ route('shengfan.vote', $thread->mainpost->id) }}" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
               <label for="shengfan">请输入您想投喂的剩饭数（-1~10）</label>
               <small>提示：您的剩饭总数是{{ Auth::user()->shengfan }}</small>
               <input name="shengfan_num" class="number" value="1"></input>
            </div>
            <div class="">
               <button type="submit" class="btn btn-primary sosad-button btn-sm">投喂剩饭</button>
            </div>
         </form>
      </div>
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
