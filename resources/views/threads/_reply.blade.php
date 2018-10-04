@if((!$thread->noreply)&&(!$thread->locked)&&(($thread->public)||($thread->user_id==Auth::user()->id)))
  @if(Auth::user()->no_posting > Carbon\Carbon::now() )
    <h6 class="text-center">您被禁言至{{ Carbon\Carbon::parse(Auth::user()->no_posting)->diffForHumans() }}，暂时不能回帖。</h6>
  @else
  <div class="panel panel-default">
    <div class="panel-body">
      <form id="replyToThread" action="{{ route('post.store', $thread) }}" method="POST">
          {{ csrf_field() }}
          <div class="hidden margin5" id="reply_to_post">
              <span class="" id="reply_to_post_info"></span>
              <button type="button" class="sosad-button-ghost"><span class="glyphicon glyphicon glyphicon-remove" onclick="cancelreplytopost()"></span></button>
          </div>
          <input type="hidden" name="reply_to_post_id" id="reply_to_post_id" class="form-control" value="0"></input>
          <input type="hidden" name="default_chapter_id" id="default_chapter_id" value="{{ $defaultchapter }}"></input>
          <div class="form-group">
              <textarea name="body" rows="7" class="form-control" id="markdowneditor" placeholder="评论十个字起哦～" value="{{ old('body') }}"></textarea>
              <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-ghost">恢复数据</button>
              <span class="pull-right sosad-button-ghost">
                字数统计：<span id="word-count">0</span>
              </span>
          </div>
          <!-- 复选框 -->
          <div class="checkbox">
            <input type="checkbox" name="anonymous" onclick="document.getElementById('majiareplythread{{$thread->id}}').style.display = 'block'" id="anonymous">
            <label for="anonymous" class="input-helper input-helper--checkbox">
                马甲
            </label>&nbsp;

            <input type="checkbox" name="editor" onclick="$('#markdowneditor').markdown({language:'zh'})" id="editor">
            <label for="editor" class="input-helper input-helper--checkbox">
                显示编辑器
            </label>&nbsp;

            <input type="checkbox" name="indentation" id="indentation" checked>
            <label for="indentation" class="input-helper input-helper--checkbox">
                段首缩进（自动空两格）
            </label>&nbsp;
          </div>
          <div class="form-group text-right" id="majiareplythread{{$thread->id}}" style="display:none">
            <input type="text" name="majia" class="form-control" value="{{Auth::user()->majia ?:'匿名咸鱼'}}" placeholder="请输入不超过10字的马甲">
            <label for="majia"><small>(马甲仅勾选“匿名”时有效)</small></label>
          </div>
          <div class="pull-right">
            <button type="submit" name="store_button" value="Store" class="sosad-button-post smaller-10" id="button-post" disabled>
              <i class="fa fa-comment"></i>
              回复
            </button>
            &nbsp;
            @if((Auth::id()==$thread->creator->id)&&($thread->book_id!=0))
            <a href="{{ route('book.createchapter', $thread->book_id) }}" class="sosad-button-post smaller-10">
              <i class="fa fa-external-link alt"></i>
              去新页面更新
            </a>
          </div>
          @endif
      </form>
    </div>
  </div>
  @endif

@else
<div class="text-center">
    本帖锁定或由于作者设置，不能跟帖
</div>
@endif
