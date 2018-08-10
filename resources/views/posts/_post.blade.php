@if($post->fold_state)
<div class="text-center">
    <a data-toggle="collapse" data-target="#post{{ $post->id }}" class="h6">该回帖被折叠，点击展开</a>
</div>
@endif

    <div class="panel panel-default {{ $post->fold_state ? 'collapse':'' }} " id = "post{{ $post->id }}">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-12">
                    @include('posts._post_profile')
                    <span class="pull-right">
                        <a href="{{ route('thread.showpost', $post) }}">No.{{ ($posts->currentPage()-1)*$posts->perPage()+$key+1 }}</a>
                    </span>
                </div>
            </div>
        </div>
        <div class="panel-body post-body">
            @include('posts._post_body')
        </div>

        @if(Auth::check())
        <div class="text-right post-vote">
            @include('posts._post_vote')
        </div>
        @endif

        @if ($post->comments->count() > 0)
        <div class="panel-footer">
            <?php $postcomments = $post->comments; ?>
            @include('posts._post_comments')
        </div>
        @endif
    </div>
