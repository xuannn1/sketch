<div class="">
    <form id="replyToDialogue" action="{{ route('message.store') }}" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <input type="text" name="send_to" class="hidden" value="{{ $speaker->id }}">
            <label for="body">新消息正文：</label>
            <textarea name="body" data-provide="markdown" id="messagetouser" rows="8" class="form-control" placeholder="请输入想发送的私信消息，10字起">{{old('body')}}</textarea>
            <button type="button" onclick="retrievecache('messagetouser')" class="sosad-button-control addon-button">切换恢复数据</button>
        </div>
        <button type="submit" class="btn btn-primary">发布</button>
    </form>
</div>
