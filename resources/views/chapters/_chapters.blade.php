<div class="hidden-sm hidden-md hidden-lg overflow-hidden text-center">
    @foreach($chapters as $chapter)
    <a href="{{ route('post.show', $chapter->id) }}" type="button" class = "btn btn-info sosad-button btn-sm btn-block">
        @if($chapter->is_bianyuan&&!$thread->is_bianyuan)
            <span class="glyphicon glyphicon-info-sign"></span>
        @endif
        {{ $chapter->title }}</a>

    @endforeach
</div>
<div class="hidden-xs table-hover">
    <table class="table">
        <thead>
            <tr>
                <th>章节名</th>
                <th>概要</th>
                <th><i class="fa fa-pencil" aria-hidden="true"></i></th>
                <th><i class="fa fa-eye" aria-hidden="true"></i></th>
                <th><i class="fa fa-heart-o" aria-hidden="true"></i></th>
                <th><i class="fa fa-commenting" aria-hidden="true"></i></th>
                <th>发布时间</th>
                <th>最后修改</th>
            </tr>
        </thead>
        <tbody>
            @foreach($chapters as $chapter)
            <tr>
                <th><a href="{{ route('post.show', $chapter->id) }}" class = "">
                    @if($chapter->is_bianyuan&&!$thread->is_bianyuan)
                        <span class="glyphicon glyphicon-info-sign"></span>
                    @endif
                    {{ $chapter->title }}</a></th>
                <th><a href="{{ route('post.show', $chapter->id) }}" class = "">
                    @if($chapter->is_bianyuan||$thread->is_bianyuan||iconv_strlen($chapter->brief) < 15)
                    {{ $chapter->brief }}
                    @else
                    StringProcess::simpletrim($chapter->brief,15);
                    @endif
                </a></th>
                <th>{{ $chapter->char_count }}</th>
                <th>{{ $chapter->view_count }}</th>
                <th>{{ $chapter->upvote_count }}</th>
                <th>{{ $chapter->reply_count }}</th>
                <th>{{ Carbon::parse($chapter->created_at)->setTimezone('Asia/Shanghai') }}</th>
                <th>{{ Carbon::parse($chapter->edited_at)->setTimezone('Asia/Shanghai')}}</th>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
