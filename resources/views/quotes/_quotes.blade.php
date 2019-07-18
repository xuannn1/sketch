@foreach($quotes as $int=>$quote)
<article class="quote{{$quote->id}}">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6 font-4">
                <a href="{{ route('quote.show', $quote->id) }}">{{ $quote->body }}</a>
            </div>
            <div class="col-sm-6 text-right">
                <div class="">
                    @if($quote->is_anonymous)
                    {{ $quote->majia ?? '匿名咸鱼'}}
                    @else
                    <a href="{{route('user.show', $quote->user_id)}}">{{ $quote->author->name }}</a>
                    @endif
                </div>
                <div class="">
                    {{ $quote->created_at }}
                </div>
            </div>
        </div>
    </div>
    <hr>
</article>
@endforeach
