<div class="">
    <div class="">
        @if(Auth::check()&&(Auth::user()->level>2))
        <a class="btn btn-primary btn-md sosad-button" href="{{ route('books.index', StringProcess::add_to_thread_filter(['withBianyuan'=>request()->withBianyuan?'':'include_bianyuan'], request()->all()))
         }}" role="button">显示边限<span class="{{ request()->withBianyuan?'glyphicon glyphicon-remove':''}}"></span></a>
        @endif

        @if(request()->ordered)
        <a class="btn btn-info btn-md sosad-button-control" href="{{ route('books.index', StringProcess::remove_from_thread_filter('ordered',request()->all())) }}" role="button">{{ config('selectors.thread_index_filter.ordered')[request()->ordered] }}<span class="glyphicon glyphicon-remove"></span></a>
        @endif
    </div>

    <div class="select-tag">
        @foreach($selected_tags as $tag)
        <a class="btn btn-info btn-md sosad-button-control" href="{{ route('books.index', StringProcess::removeWithTag($tag->id,request()->all())) }}" role="button">{{ $tag->tag_name }}<span class="glyphicon glyphicon-remove"></span></a>
        @endforeach
    </div>

    <div class="exclude-tag">
        @foreach($excluded_tags as $tag)
        <a class="btn btn-info btn-md sosad-button-control admin-anonymous" href="{{ route('books.index', StringProcess::removeExcludeTag($tag->id,request()->all())) }}" role="button">{{ $tag->tag_name }}<span class="glyphicon glyphicon-remove"></span></a>
        @endforeach
    </div>

<i></i>
</div>
