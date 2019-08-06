@foreach($statuses as $status)
<article class="{{ 'status'.$status->id }} {{ 'followuser'.$status->user_id}}">

    <div class="row">
        <div class="col-xs-12 h5">
            @if(!$status->is_public)
            <span class="glyphicon glyphicon-eye-close"></span>
            @endif
            <span>
                @if($status->author)
                @if($status->author->title&&$status->author->title->name)
                <span class="maintitle title-{{$status->author->title->style_id}}">{{ $status->author->title->name }}</span>
                @endif
                <a href="{{ route('user.show', $status->user_id) }}">{{ $status->author->name }}</a>
                @endif
                &nbsp;
                {{ $status->created_at? Carbon::parse($status->created_at)->diffForHumans():'' }}
            </span>
            @if((Auth::check())&&(Auth::user()->isAdmin()))
            <span>
                <span><a href="#" data-id="{{$status->id}}" data-toggle="modal" data-target="#TriggerStatusAdministration{{ $status->id }}" class="btn btn-default btn-xs admin-button">管理动态</a></span>
                @include('admin._status_management_form')
            </span>
            @endif
            <span class="pull-right smaller-20">
                <a href="{{ route('status.show', $status->id) }}">S.{{$status->id}}</a>
            </span>
        </div>
        <div class="col-xs-12 h5 brief-0 {{$status_expand? '':'fixed-height-90'}}">
            <span class="smaller-10">
                <a href="{{ route('status.show', $status->id) }}" class="font-weight-400">{!! StringProcess::wrapSpan($status->body) !!}</a>
            </span>
            @if(Auth::check()&&Auth::user()->level >= 1)
            <span class="pull-right"><button class="btn btn-default btn-xs" data-id="{{$status->id}}" onclick="voteItem('status', {{$status->id}}, 'upvote')" ><span class="glyphicon glyphicon-heart"></span><span id="status{{$status->id}}upvote">{{ $status->upvote_count }}</span></button></span>
            @endif
        </div>
    </div>
    <hr class="narrow">
</article>
@endforeach
