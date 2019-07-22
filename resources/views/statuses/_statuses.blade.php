@foreach($statuses as $status)
<article class="{{ 'status'.$status->id }} {{ 'followuser'.$status->user_id}}">
    <div class="row">
        <div class="col-xs-12 h5">
            <span>
                @if($status->author)
                @if($status->author->title&&$status->author->title->name)
                <span class="maintitle title-{{$status->author->title->style_id}}">{{ $status->author->title->name }}</span>
                @endif
                <a href="{{ route('user.show', $status->user_id) }}">{{ $status->author->name }}</a>
                @endif
                &nbsp;
                {{ Carbon::parse($status->created_at)->diffForHumans() }}
            </span>
            @if(!$status->is_public)
                <span class="glyphicon glyphicon-eye-close"></span>
            @endif
            @if((Auth::check())&&(Auth::user()->isAdmin()))
            <span>
                <span><a href="#" data-id="{{$status->id}}" data-toggle="modal" data-target="#TriggerStatusAdministration{{ $status->id }}" class="btn btn-default btn-xs admin-button">管理动态</a></span>
                @include('admin._status_management_form')
            </span>
            @endif
            <span class="pull-right">
                <a href="{{ route('status.show', $status->id) }}">>>详情</a>
            </span>


        </div>
        <div class="col-xs-12 h5 brief-0">
            <span class="smaller-10">
                {!! StringProcess::wrapParagraphs($status->body) !!}
            </span>
        </div>
    </div>
    <hr class="brief-2">
</article>
@endforeach
