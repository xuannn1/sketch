<div class="col-xs-12">
    <span id="full{{$message->id}}" class="hidden">
        <div class="main-text">
            {!! Helper::wrapParagraphs($message->content) !!}
        </div>
    </span>
    <span id="abbreviated{{$message->id}}" class="">
        {!! Helper::trimtext($message->content, 20) !!}
    </span>
    <small><a type="button" name="button" id="expand{{$message->id}}" onclick="expandpost('{{$message->id}}')">展开</a></small>
</div>
