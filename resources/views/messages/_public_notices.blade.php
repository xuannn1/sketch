@foreach($public_notices as $public_notice)
<article class="">
    <hr>
    <div class="row">
        <div class="col-xs-12">
            <span id="full{{$public_notice->id}}" class="hidden">
                <div class="text-center">
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
            <span id="abbreviated{{$public_notice->id}}" class="">{!! Helper::trimtext($public_notice->notice_body,60) !!}</span>
            <small><a type="button" name="button" id="expand{{$public_notice->id}}" onclick="expandpost('{{$public_notice->id}}')" class="pull-right">展开</a></small>
        </div>
    </div>
</article>

@endforeach
