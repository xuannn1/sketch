@foreach($public_notices as $public_notice)
<article class="margin5">
    <div class="row">
        <div class="col-xs-12">
            <span id="full{{$public_notice->id}}" class="hidden">
                <div class="text-center margin5">
                    <span>
                        <a href="{{ route('user.show', $public_notice->user_id) }}">{{ $public_notice->name }}</a>
                    </span>
                    <span class="grayout">
                        发表于 {{ Carbon\Carbon::parse($public_notice->created_at)->diffForHumans() }}
                    </span>
                </div>
                <div class="main-text">
                    {!! Helper::wrapParagraphs($public_notice->notice_body) !!}
                </div>
            </span>
            <span id="abbreviated{{$public_notice->id}}" class="main-text">{!! Helper::trimtext($public_notice->notice_body,60) !!}</span>
            <a type="button" name="button" id="expand{{$public_notice->id}}" onclick="expandpost('{{$public_notice->id}}')" class="pull-right grayout">展开</a>
        </div>
    </div>
</article>

@endforeach
