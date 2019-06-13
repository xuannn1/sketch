@if((Auth::user()->admin)||((!$thread->noreply)&&(!$thread->locked)&&(($thread->public)||($thread->user_id==Auth::user()->id))))
@if(Auth::user()->no_posting > Carbon\Carbon::now() )
<h6 class="text-center">您被禁言至{{ Carbon\Carbon::parse(Auth::user()->no_posting)->diffForHumans() }}，暂时不能回帖。</h6>
@elseif(Auth::user()->user_level < 2)
<h6 class="text-center">您的等级不足2级，不能回帖</h6>
@else
<div class="panel-group">
    <form id="replyToThread" action="{{ route('post.store', $thread) }}" method="POST">
        {{ csrf_field() }}
        <div class="hidden" id="reply_to_post">
            <span class="" id="reply_to_post_info"></span>
            <button type="button" class="label"><span class="glyphicon glyphicon glyphicon-remove" onclick="cancelreplytopost()"></span></button>
        </div>
        <h6 class="text-center greyout">请勿无意义水贴意图升级，一经发现积分等级清零处理。</h6>
        <input type="hidden" name="reply_to_post_id" id="reply_to_post_id" class="form-control" value="0"></input>
        <input type="hidden" name="default_chapter_id" id="default_chapter_id" value="{{ $defaultchapter }}"></input>
        <div class="form-group">
            <textarea name="body" rows="7" class="form-control" id="markdowneditor" placeholder="评论十个字起哦～" value="{{ old('body') }}"></textarea>
            <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-control addon-button">恢复数据</button>
            <button type="button" onclick="removespace('markdowneditor')" class="sosad-button-control addon-button">清理段首空格</button>
            <button href="#" type="button" onclick="wordscount('markdowneditor');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>

        </div>
        <div class="checkbox">
            <label><input type="checkbox" name="anonymous" onclick="document.getElementById('majiareplythread{{$thread->id}}').style.display = 'block'">马甲？</label>&nbsp;
            <label><input type="checkbox" name="editor" onclick="$('#markdowneditor').markdown({language:'zh'})">显示编辑器？</label>
            <label><input type="checkbox" name="indentation"  {{ Auth::user()->indentation? 'checked':'' }}>段首缩进（自动空两格）？</label>
            <div class="form-group text-right" id="majiareplythread{{$thread->id}}" style="display:none">
                <input type="text" name="majia" class="form-control" value="{{Auth::user()->majia ?:'匿名咸鱼'}}" placeholder="请输入不超过10字的马甲">
                <label for="majia"><small>(马甲仅勾选“匿名”时有效)</small></label>
            </div>
        </div>
        <button type="submit" name="store_button" value="Store" class="btn btn-danger sosad-button">回复</button>
        @if((Auth::id()==$thread->user_id)&&($thread->book_id!=0))
        <a href="{{ route('book.createchapter', $thread->book_id) }}" class="btn btn-warning sosad-button">去新页面更新</a>
        @endif
    </form>
</div>
@endif

@else
<div class="text-center">
    本帖锁定或由于作者设置，不能跟帖
</div>
@endif
