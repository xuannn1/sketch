<div class="container-fluid">
    <h2 id= "daily-quote" class="display-1">{{ $quote->quote }}</h2>
    <div class="row">
        <div class="col-xs-8 col-xs-offset-2 text-right">
            @if ($quote->anonymous)
            ——{{ $quote->majia }}
            @else
            ——<a href="#">{{ $quote->name }}</a>
            @endif
            <br>
        </div>
    </div>

    <div class="row">
    @if (Auth::check())
        <div class="col-xs-4 col-xs-offset-2 text-left h6">
            <u><a href="{{ route('quote.create') }}">贡献题头</a></u>
        </div>
        <div class="col-xs-4 text-right">
            <a class="btn btn-xs btn-default" href="{{ route('quote.vote', $quote->id) }}">咸鱼{{ $quote->xianyu }}</a><br>
        </div>
    @else
        <div class="text-center">
            <a class="btn btn-lg btn-success sosad-button" href="{{ route('register') }}" role="button">一起来丧</a>
        </div>
    @endif
    </div>
</div>
