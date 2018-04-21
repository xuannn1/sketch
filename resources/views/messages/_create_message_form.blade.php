@include('shared.errors')
<form method="POST" action="{{ route('messages.store', $user->id) }}">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="body">新消息正文：</label>
        <textarea name="body" data-provide="markdown" id="messagetouser" rows="8" class="form-control" placeholder="消息">{{ old('body') }}</textarea>
        <button type="button" onclick="retrievecache('messagetouser')" class="sosad-button-control addon-button">恢复数据</button>
    </div>
    <button type="submit" class="btn btn-primary">发布</button>
</form>
