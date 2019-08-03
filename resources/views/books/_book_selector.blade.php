<!-- 进入筛选模式 -->
<div class="selector">
    <div class="dropdown">
        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">原创性<span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a class="" href="{{ route('books.index', StringProcess::add_to_thread_filter(['inChannel'=>1], request()->all())) }}">原创小说</a></li>
                <li><a class="" href="{{ route('books.index', StringProcess::add_to_thread_filter(['inChannel'=>2], request()->all())) }}">同人小说</a></li>
            </ul>
        </span>

        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">篇幅<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach($tags['book_length_tags'] as $tag)
                <li><a class="" href="{{ route('books.index', StringProcess::mergeWithTag($tag->id, request()->all())) }}">{{ $tag->tag_name }}</a></li>
                @endforeach
            </ul>
        </span>

        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">进度<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach($tags['book_status_tags'] as $tag)
                <li><a class="" href="{{ route('books.index', StringProcess::mergeWithTag($tag->id, request()->all())) }}">{{ $tag->tag_name }}</a></li>
                @endforeach
            </ul>
        </span>

        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">性向<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach($tags['sexual_orientation_tags'] as $tag)
                <li><a class="" href="{{ route('books.index', StringProcess::mergeWithTag($tag->id, request()->all())) }}">{{ $tag->tag_name }}</a></li>
                @endforeach
            </ul>
        </span>

        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">编推<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach($tags['editor_tags'] as $tag)
                <li><a class="" href="{{  route('books.index', StringProcess::mergeWithTag($tag->id, request()->all())) }}">{{ $tag->tag_name }}</a></li>
                @endforeach
            </ul>
        </span>

        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">其他标签<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach($tags['book_custom_Tags'] as $tag)
                @if($tag->is_bianyuan===0||Auth::check()&&Auth::user()->level>=3)
                <li><a class="" href="{{ route('books.index', StringProcess::mergeWithTag($tag->id, request()->all())) }}">{{ $tag->tag_name }}</a></li>
                @endif
                @endforeach
            </ul>
        </span>

        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">排序<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach(config('selectors.book_index_filter.ordered') as $ordered => $explanation)
                    <li><a class="" href="{{ route('books.index', StringProcess::add_to_thread_filter(['ordered'=>$ordered], request()->all()))}}">{{$explanation}}</a></li>
                @endforeach
            </ul>
        </span>

        <span class="pull-right">
            <a href="{{ route('books.selector') }}" class="btn btn-default btn-md sosad-button-control">复合筛选</a>
            <a href="{{ route('tag.index') }}" class="btn btn-default btn-md sosad-button-control">全站标签</a>
        </span>

    </div>
</div>
