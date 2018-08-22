<div class="panel-heading">
    @include('shared.errors')
    <form action="{{ route('statuses.store') }}" method="POST">
        {{ csrf_field() }}
        <div class="container-fluid">
            <div class="row">
                <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-ghost smaller-10 grayout">恢复数据</button>
                <span class="sosad-button-ghost smaller-10 grayout">
                    字数统计：<span id="word-count">0</span>/180
                </span>
            </div>
            <div class="row">
                <textarea class="form-control" id="markdowneditor" rows="4" placeholder="今天你丧了吗…"  name="content">{{ old('content') }}</textarea>
            </div>
            <div class="row">
                <button type="submit" class="pull-right sosad-button-post" id="button-post" disabled="disabled">
                    <i class="fas fa-feather"></i>
                    发布
                </button>
                <!-- <span class="pull-right smaller-10 grayout">状态字数限制180&nbsp;</span> -->
            </div>
        </div>
    </form>
</div>
