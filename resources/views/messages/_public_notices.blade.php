<!-- 简单展示所有公共通知 -->
@foreach($public_notices as $key=>$public_notice)
<article id="public_notice{{ $public_notice->id }}">
    <div class="h5">
        <span class="badge">公共通知</span>
        <span id="full{{$public_notice->id}}" class="hidden main-text">
            <div class="text-center">
                @if($public_notice->author)
                <a href="{{route('user.show', $public_notice->author->id)}}">{{$public_notice->author->name}}</a>
                @endif
                <span class="grayout">{{$public_notice->created_at}}</span>
            </div>
            <div class="main-text">
                {!! StringProcess::wrapParagraphs($public_notice->body) !!}
            </div>
        </span>
        <span id="abbreviated{{$public_notice->id}}">
            {!! StringProcess::trimtext($public_notice->body,70) !!}
        </span>
        <a type="button" name="button" id="expand{{$public_notice->id}}" onclick="expanditem('{{$public_notice->id}}')">展开</a>
    </div>
    <hr>
</article>
@endforeach
