<div class="col-xs-12">
    <span id="full{{$message->id}}" class="hidden">
        <div class="main-text">
            <p>
              {!! Helper::wrapParagraphs($message->content) !!}
            </p>
        </div>
    </span>
    <span id="abbreviated{{$message->id}}" class="main-text">
        {!! Helper::trimtext($message->content, 60) !!}
    </span>
    <a type="button" name="button" id="expand{{$message->id}}" onclick="expandpost('{{$message->id}}', true)" class="pull-right grayout">展开</a>
</div>
