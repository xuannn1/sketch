<div class="hidden-sm hidden-md hidden-lg overflow-hidden text-center">
    @foreach($posts as $post)
    <a href="{{ route('post.show', $post->id) }}" type="button" class = "btn btn-info sosad-button btn-sm btn-block">
        @if($post->is_bianyuan&&!$thread->is_bianyuan)
            <span class="glyphicon glyphicon-info-sign"></span>
        @endif
        {{ $post->title }}</a>

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
            @foreach($posts as $post)
            <tr>
                <th><a href="{{ route('post.show', $post->id) }}" class = "">
                    @if($post->is_bianyuan&&!$thread->is_bianyuan)
                        <span class="glyphicon glyphicon-info-sign"></span>
                    @endif
                    {{ $post->title }}</a></th>
                <th><a href="{{ route('post.show', $post->id) }}" class = "">{{ $post->brief }}</a></th>
                <th>{{ $post->char_count }}</th>
                <th>{{ $post->view_count }}</th>
                <th>{{ $post->upvote_count }}</th>
                <th>{{ $post->reply_count }}</th>
                <th>{{ Carbon::parse($post->created_at)->setTimezone('Asia/Shanghai') }}</th>
                <th>{{ Carbon::parse($post->edited_at)->setTimezone('Asia/Shanghai')}}</th>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
