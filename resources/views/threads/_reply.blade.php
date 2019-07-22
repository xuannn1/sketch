<!-- 回复输入框 -->
@if(!Auth::user()->isAdmin()&&(($thread->is_locked)||($thread->no_reply&&$thread->user_id<>Auth::id())))
    <div class="text-center">
        本帖锁定或由于作者设置，不能跟帖
    </div>
@else
    @if(Auth::user()->no_posting)
    <h6 class="text-center">您被禁言，暂时不能回帖。</h6>
    @elseif(Auth::user()->level < 2)
    <h6 class="text-center">您的等级不足2级，不能回帖</h6>
    @else
    <div class="panel-group h4">
        <form id="replyToThread" action="{{ route('post.store', $thread) }}" method="POST">
            {{ csrf_field() }}
            <div class="hidden" id="reply_to_post" class="form-control">
                <span class="smaller-10" id="reply_to_post_info"></span>
                <i class="fa fa-times bigger-10 grayout" aria-hidden="true" type="button" onclick="cancelreplytopost()"></i>
            </div>
            <input type="hidden" name="reply_to_id" id="reply_to_id" class="form-control" value="0"></input>
            <div class="form-group">
                <textarea name="body" rows="7" class="form-control" id="markdowneditor" placeholder="评论十个字起哦～请勿发布类似“如何升级”的无意义水贴。站内严禁污言秽语、人身攻击。社区气氛有赖每一条咸鱼的爱惜～">{{ old('body') }}</textarea>
                <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-control addon-button">恢复数据</button>
                <button href="#" type="button" onclick="wordscount('markdowneditor');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
            </div>
            @if(!$thread->is_bianyuan)
            <h6 style="color:#d66666">(本帖非边限对外公开，请不要引入“边限”讨论。)</h6>
            @endif
            @if($thread->channel()->type==='box')
            <h6 style="color:#d66666">(严禁提无关问题打扰作者，如“等级”、“签到”相关的日经问题。)</h6>
            @endif
            <div class="checkbox">
                <label class="hidden" id="is_comment"><input type="checkbox"  name="is_comment">是点评？</label>
                <label><input type="checkbox" name="is_anonymous" onclick="document.getElementById('majiareplythread{{$thread->id}}').style.display = 'block'">马甲？</label>&nbsp;
                <label><input type="checkbox" name="editor" onclick="$('#markdowneditor').markdown({language:'zh'})">显示编辑器？</label>
                <label><input type="checkbox" name="use_indentation"  {{ Auth::user()->use_indentation? 'checked':'' }}>段首缩进（自动空两格）？</label>
                <div class="form-group text-right" id="majiareplythread{{$thread->id}}" style="display:none">
                    <input type="text" name="majia" class="form-control" value="{{Auth::user()->majia ?:'匿名咸鱼'}}" placeholder="请输入不超过10字的马甲">
                    <label for="majia"><small>(马甲仅勾选“匿名”时有效)</small></label>
                </div>
            </div>

            <button type="submit" name="store_button" value="Store" class="btn btn-md btn-primary sosad-button">回复</button>
        </form>
    </div>
    @endif
@endif
