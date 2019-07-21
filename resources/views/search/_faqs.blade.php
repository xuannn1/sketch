@foreach($faqs as $QnA)
<div class="main-text post-reply">
    <a type="button" data-toggle="collapse" data-target="#helpQnA{{$QnA->id}}" style="cursor: pointer;" class="font-5">Q：{{$QnA->question}}</a>
</div>
<div class="collapse main-text post-reply font-5 grayout" id="helpQnA{{$QnA->id}}">
    A：{!! StringProcess::wrapSpan($QnA->answer) !!}
</div>
@endforeach
