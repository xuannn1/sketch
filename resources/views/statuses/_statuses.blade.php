@foreach($statuses as $status)
<article class="{{ 'status'.$status->id }} {{ 'followuser'.$status->user_id}}">
    <div class="row">
        <div class="col-xs-12 h5">
            <span>
                <a href="{{ route('user.show', $status->user_id) }}">{{ $status->user_name }}</a>&nbsp;
                {{ Carbon::parse($status->created_at)->diffForHumans() }}
            </span>
            <span class="pull-right">
                @if((Auth::check())&&(Auth::id()==$status->user_id))
                <button type="button" name="button" class="sosad-button btn btn-md btn-danger" onclick="destroystatus({{$status->id}})">删除动态</button>
                @elseif((Auth::check())&&(Auth::user()->isAdmin()))
                <a href="{{ route('admin.statusform', $status->id) }}" class="btn btn-md btn-danger admin-button">管理动态</a>
                @endif
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
