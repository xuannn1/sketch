<div class="">

    @if(Auth::check()&&(Auth::user()->level>2))
    <a class="btn btn-primary btn-md sosad-button" href="{{ route('threads.index', StringProcess::add_to_thread_filter(['withBianyuan'=>request()->withBianyuan?'':'include_bianyuan'], request()->all()))
     }}" role="button">显示边限<span class="{{ request()->withBianyuan?'glyphicon glyphicon-remove':''}}"></span></a>
    @endif

    @if(request()->ordered)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('threads.index', StringProcess::remove_from_thread_filter('ordered',request()->all())) }}" role="button">{{ config('selectors.thread_index_filter.ordered')[request()->ordered] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif

    @if(request()->isPublic)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('threads.index', StringProcess::remove_from_thread_filter('isPublic',request()->all())) }}" role="button">{{ config('selectors.thread_index_filter.isPublic')[request()->isPublic] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif

</div>
