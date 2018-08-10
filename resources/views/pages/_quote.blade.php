<div class="container-fluid">
    <h2 id= "daily-quote" class="display-1">
        <!-- <i class="fa fa-quote-left"></i> &nbsp; -->
        {{ $quote->quote }}
        <!-- &nbsp; <i class="fa fa-quote-right"></i> -->
    </h2>
    <div>
       <div id= "quote-author" class="text-center">
          @if ($quote->anonymous)
             —— {{ $quote->majia }}
          @else
             —— <a href="#">{{ $quote->name }}</a>
          @endif
          <br>
       </div>
    </div>
    @if (Auth::check())
        <div class="quote-button-wrapper">
           <a class="quote-button" href="{{ route('quote.create') }}">
               <i class="fa fa-edit"></i>
               贡献题头</a>
           <a class="quote-button" href="{{ route('quote.vote', $quote->id) }}">
               咸鱼&nbsp;{{ $quote->xianyu }}</a><br>
        </div>

    @else
       <div class="text-center">
          <a class="quote-join" href="{{ route('register') }}" role="button">一起来丧</a>
       </div>
    @endif
</div>
