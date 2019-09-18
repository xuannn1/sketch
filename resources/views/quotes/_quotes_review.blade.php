@foreach ($quotes as $quote)
<div class="row text-center">
    <div class="col-xs-8">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <h4><a href="{{ route('quote.show', $quote->id) }}">{{ $quote->body }}</a></h4>
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-xs-4 text-right ">
                        <button class="btn btn-md btn-success cancel-button approvebutton{{$quote->id}} {{$quote->reviewed? 'hidden':''}} quotebutton-show{{$quote->id}}"  type="button" name="button" onClick="review_quote({{$quote->id}},'approve')">对外显示<i class="fa fa-check" aria-hidden="true"></i></button>
                    </div>
                    <div class="col-xs-4 text-center">
                        <button class="btn btn-md btn-info cancel-button togglebutton{{$quote->id}} {{$quote->reviewed? '':'hidden'}} quotebutton-review{{$quote->id}}"  type="button" name="button" onClick="reset_review_button({{$quote->id}})">重新审核</button>
                    </div>
                    <div class="col-xs-4 text-left">
                        <button class="btn btn-md  btn-danger cancel-button disapprovebutton{{$quote->id}} {{$quote->reviewed? 'hidden':''}} quotebutton-notshow{{$quote->id}}"  type="button" name="button" onClick="review_quote({{$quote->id}},'disapprove')">不显示<i class="fa fa-times" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <small>
        <div class="col-xs-4">
            <p><a href="#">{{ $quote->author->name }}</a></p>
            @if ($quote->is_anonymous)
            <p>马甲：{{ $quote->majia ?? '匿名咸鱼'}}</p>
            @endif
            @if ($quote->notsad)
            <p style = "color:#b73766">不丧</p>
            @endif
            @if(!$quote->reviewed)
            <p><span class="not_reviewed_{{ $quote->id }}"><code>未审核</code></span></p>
            @endif
            @if($quote->reviewer)
            <p><span>审核人「{{$quote->reviewer->name}}」</span></p>
            @endif
            <p><span class="quotereviewstatus{{ $quote->id }}">状态：{{$quote->approved? '显示':'不显示'}}</span></p>
        </div>
    </small>
</div>

<hr>
@endforeach
