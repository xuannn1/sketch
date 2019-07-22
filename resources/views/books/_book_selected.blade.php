<div class="">

    @if(Auth::check()&&(Auth::user()->level>2))
    <a class="btn btn-primary btn-md sosad-button" href="{{ route('books.index',
    array_merge(['withBianyuan' => request()->withBianyuan?'':'include_bianyuan'], request()->only('inChannel', 'withTag','excludeTag','ordered'))) }}" role="button">显示边限<span class="{{ request()->withBianyuan?'glyphicon glyphicon-remove':''}}"></span></a>
    @endif


    @if(request()->inChannel)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('books.index',
    request()->only('withTag','excludeTag','withBianyuan','ordered')) }}" role="button">{{ config('selectors.book_index_filter.inChannel')[request()->inChannel] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif

    @if(request()->withTag)
    @foreach(explode('-',request()->withTag) as $tag_id)
    @if(is_numeric($tag_id)&&$tag_id>0)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('books.index',
    array_merge(['withTag'=>StringProcess::splitWithTag($tag_id, request()->withTag)], request()->only('inChannel', 'excludeTag','withBianyuan')))
     }}" role="button">{{ ConstantObjects::find_tag_by_id($tag_id)->tag_name }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif
    @endforeach
    @endif

    @if(request()->ordered)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('books.index',
    request()->only('inChannel', 'withTag','excludeTag','withBianyuan')) }}" role="button">{{ config('selectors.book_index_filter.ordered')[request()->ordered] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif

</div>
