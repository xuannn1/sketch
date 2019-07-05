<div class="">
    @if(request()->channel)
    <a class="btn btn-info sosad-button-control" href="{{ route('books.index',
    request()->only('showbianyuan', 'label', 'book_length', 'book_status', 'sexual_orientation', 'book_tag', 'orderby')) }}" role="button">{{ Helper::allChannels()->keyBy('id')->get(request()->channel)->channelname }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif

    @if(request()->label)
    <a class="btn btn-info sosad-button-control" href="{{ route('books.index',
    request()->only('showbianyuan', 'channel', 'book_length', 'book_status', 'sexual_orientation', 'book_tag', 'orderby')) }}" role="button">{{ Helper::allLabels()->keyBy('id')->get(request()->label)->labelname }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif

    @if(request()->book_length)
    <a class="btn btn-info sosad-button-control" href="{{ route('books.index',
    request()->only('showbianyuan', 'channel', 'label', 'book_status', 'sexual_orientation', 'book_tag', 'orderby')) }}" role="button">{{ config('constants.book_info.book_length_info')[request()->book_length] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif

    @if(request()->book_status)
    <a class="btn btn-info sosad-button-control" href="{{ route('books.index',
    request()->only('showbianyuan', 'channel', 'label', 'book_length', 'sexual_orientation', 'book_tag', 'orderby')) }}" role="button">{{ config('constants.book_info.book_status_info')[request()->book_status] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif

    @if(request()->sexual_orientation)
    <a class="btn btn-info sosad-button-control" href="{{ route('books.index',
    request()->only('showbianyuan', 'channel', 'label', 'book_length', 'book_status', 'book_tag', 'orderby')) }}" role="button">{{ config('constants.book_info.sexual_orientation_info')[request()->sexual_orientation] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif

    @if(request()->book_tag)
    <a class="btn btn-info sosad-button-control" href="{{ route('books.index',
    request()->only('showbianyuan', 'channel', 'label', 'book_length', 'book_status', 'sexual_orientation', 'orderby')) }}" role="button">{{ Helper::allTags()->keyBy('id')->get(request()->book_tag)->tagname }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif

    @if(request()->orderby)
    <a class="btn btn-info sosad-button-control" href="{{ route('books.index',
    request()->only('showbianyuan', 'channel', 'label', 'book_length', 'book_status', 'sexual_orientation', 'book_tag')) }}" role="button">{{ config('constants.book_info.orderby_info')[request()->orderby] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif


    @if(Auth::check()&&(Auth::user()->user_level>2)&&$show_bianyuan_tab)
    <span class="pull-right">
        &nbsp;
        <a class="btn btn-primary sosad-button" href="{{ route('books.index',
        array_merge(['showbianyuan' => request()->showbianyuan?'':'1'], request()->only('channel', 'label', 'book_length', 'book_status', 'sexual_orientation', 'book_tag' ))) }}" role="button">显示边限<span class="{{ request()->showbianyuan?'glyphicon glyphicon-remove':''}}"></span></a>
        &nbsp;&nbsp;&nbsp;
    </span>
    @endif
</div>
