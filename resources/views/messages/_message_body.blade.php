<div class="col-xs-12">
   <span id="full{{$message->id}}" class="hidden">
      <div class="main-text">
         {!! Markdown::convertToHtml($message->content) !!}
      </div>
   </span>
   <span id="abbreviated{{$message->id}}" class="">
      {!! Helper::trimtext($message->content, 60) !!}
   </span>
   <small><a type="button" name="button" id="expand{{$message->id}}" onclick="expandpost('{{$message->id}}')" class="pull-right">展开</a></small>
</div>
