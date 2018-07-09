<div class="panel-heading">
    @include('shared.errors')
    <form action="{{ route('statuses.store') }}" method="POST">
        {{ csrf_field() }}
        <div class="container-fluid">
            <div class="row">
                <textarea class="form-control" id="markdowneditor" rows="4" placeholder="今天你丧了吗…"  name="content">{{ old('content') }}</textarea>
            </div>
            <div >
                <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-control addon-button">恢复数据</button>
                <button href="#" type="button" onclick="wordscount('markdowneditor');return false;" class="sosad-button-control addon-button">字数统计</button>
                <button type="submit" class="pull-right sosad-button btn btn-md btn-primary">发布</button>
                <span class="pull-right grayout"><small>状态字数限制180&nbsp;</small></span>
            </div>
        </div>
    </form>
</div>
