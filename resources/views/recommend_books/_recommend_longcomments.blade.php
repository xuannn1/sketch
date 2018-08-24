@foreach($recommend_longcomments as $recommend_longcomment)
<article class="">
    <div class="row">
        <div class="col-xs-12 h5">
            <span>
                <a href="{{ route('thread.showpost', $recommend_longcomment->id) }}">
                <em>回复：{{ $recommend_longcomment->thread_title }}</a></em>
            </span>
            <span class="pull-right smaller-15">
                <span>
                    @if($recommend_longcomment->anonymous)
                    {{ $recommend_longcomment->majia ?? '匿名咸鱼'}}
                    @if((Auth::check())&&(Auth::user()->admin))
                    <span class="admin-anonymous"><a href="{{ route('user.show', $recommend_longcomment->user_id) }}">{{ $recommend_longcomment->name }}</a></span>
                    @endif
                    @else
                    <a href="{{ route('user.show', $recommend_longcomment->user_id) }}">{{ $recommend_longcomment->name }}</a>
                    @endif
                </span>&nbsp;
                <span class="grayout">发表于{{ Carbon\Carbon::parse($recommend_longcomment->created_at)->diffForHumans() }}
                    @if($recommend_longcomment->created_at < $recommend_longcomment->edited_at)
                    /修改于{{ Carbon\Carbon::parse($recommend_longcomment->edited_at)->diffForHumans() }}
                    @endif
                </span>
            </span>
        </div>
        <div class="col-xs-12 h5">
            <span>{{ $recommend_longcomment->recommendation }}</span>
            <a class="btn btn-xs btn-success pull-right sosad-button" href="{{ route('recommend_books.edit', $recommend_longcomment->recommend_id)}}">修改推荐语</a>
        </div>
    </div>
    <hr>
</article>
@endforeach
