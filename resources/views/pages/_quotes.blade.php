@foreach($quotes as $int=>$quote)
<div class="jumbotron item {{$int==0? 'active':''}}" >
    <article>
        <div class="container-fluid">
            <h2 class="display-1">
                <a href="{{ route('quote.show', $quote->id) }}" class="daily-quote">
                {{ $quote->body }}
                </a>
            </h2>
            <div class="row">
                <div class="col-xs-8 col-xs-offset-2 text-right">
                    @if($quote->author)
                        @if ($quote->is_anonymous)
                        ——{{ $quote->majia }}
                        @else
                        ——<a href="#">{{ $quote->author->name }}</a>
                        @endif
                    @endif
                </div>
            </div>
            <br>
            <div class="row">
            @if (Auth::check())
                <div class="col-xs-6 text-center">
                    <span class="voteposts"><a href="{{ route('quote.create') }}" class="btn btn-default btn-md"><i class="fa fa-plus" aria-hidden="true"></i>贡献题头</a></span>
                </div>
                <div class="col-xs-6 text-center">
                    <span class="voteposts"><button href="{{ route('reward.store') }}" class="btn btn-default btn-md" onclick="event.preventDefault(); document.getElementById('vote_quote_form{{$quote->id}}').submit();">{{ $quote->fish }}咸鱼</span></button></span>
                </div>
                <form id="vote_quote_form{{$quote->id}}" action="{{ route('reward.store') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                    <input type="radio" name="reward_type" value="fish" checked>
                    <input type="text" style="width: 80px" name="reward_value" value="1">
                    <input name="rewardable_type" value="quote" class="hidden">
                    <input name="rewardable_id" value="{{$quote->id}}" class="hidden">
                </form>
            @else
                <div class="text-center">
                    <a class="btn btn-md btn-success sosad-button" href="{{ route('register') }}" role="button">一起来丧</a>
                </div>
            @endif
            </div>
        </div>
    </article>
</div>
@endforeach
