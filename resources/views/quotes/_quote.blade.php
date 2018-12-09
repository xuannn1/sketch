<article>
    <div class="row">
        <div class="col-md-6">
            <h5>{{ $quote->body }}</h5>
        </div>
        <div class="text-center">
            <a href="#">{{ $quote->creator->name }}</a>提供于{{ $quote->created_at }}<br>
            @if( $quote->anonymous )
            <h6>马甲：{{ $quote->majia ?? '匿名咸鱼'}}</h6>
            @else
            <h6><a href="#">{{ $quote->creator->name }}</a></h6>
            @endif
            <a href="#">咸鱼{{ $quote->xianyu }}</a>
        </div>
    </div>
</article>
