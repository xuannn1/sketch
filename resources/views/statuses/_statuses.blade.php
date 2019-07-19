@foreach($statuses as $status)
<article class="{{ 'status'.$status->id }} {{ 'followuser'.$status->user_id}}">
    <div class="row">
        <div class="col-xs-12 h5">
            <span>
                <a href="{{ route('user.show', $status->user_id) }}">{{ $status->user_name }}</a>&nbsp;
                {{ Carbon::parse($status->created_at)->diffForHumans() }}
            </span>
            <span class="pull-right">
                <a href="{{ route('status.show', $status->id) }}">>>详情</a>
            </span>

        </div>
        <div class="col-xs-12 h5 brief">
            <span class="smaller-10">
                {!! StringProcess::wrapParagraphs($status->body) !!}
            </span>
        </div>
    </div>
    <hr class="narrow">
</article>
@endforeach
