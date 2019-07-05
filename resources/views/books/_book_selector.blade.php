<div class="selector">
    <div class="dropdown">
        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">原创<span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a class="" href="{{ route('books.index', array_merge(['channel'=>'1'], request()->only('showbianyuan', 'label', 'book_length', 'book_status', 'sexual_orientation', 'book_tag', 'orderby'))) }}">原创</a></li>
                <li><a href="{{ route('books.index', array_merge(['channel'=>'2'], request()->only('showbianyuan', 'label', 'book_length', 'book_status', 'sexual_orientation', 'book_tag', 'orderby'))) }}">同人</a></li>
            </ul>
        </span>
        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">篇幅<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach(config('constants.book_info.book_length_info') as $key=>$book_length)
                <li><a href="{{ route('books.index', array_merge(['book_length'=>$key], request()->only('showbianyuan', 'channel', 'label', 'book_length', 'book_status', 'sexual_orientation', 'book_tag', 'orderby'))) }}">{{$book_length}}</a></li>
                @endforeach
            </ul>
        </span>
        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">进度<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach(config('constants.book_info.book_status_info') as $key=>$book_status)
                <li><a href="{{ route('books.index', array_merge(['book_status'=>$key], request()->only('showbianyuan', 'channel', 'label', 'book_length', 'book_status', 'sexual_orientation', 'book_tag', 'orderby'))) }}">{{ $book_status }}</a></li>
                @endforeach
            </ul>
        </span>
        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">性向<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach(config('constants.book_info.sexual_orientation_info') as $key=>$sexual_orientation)
                <li><a href="{{ route('books.index', array_merge(['sexual_orientation'=>$key], request()->only('showbianyuan', 'channel', 'label', 'book_length', 'book_status', 'sexual_orientation', 'book_tag', 'orderby'))) }}">{{ $sexual_orientation }}</a></li>
                @endforeach
            </ul>
        </span>
        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">排序方式<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach(config('constants.book_info.orderby_info') as $key=>$orderby)
                <li><a class="dropdown-item" href="{{ route('books.index', array_merge(['orderby' => $key], request()->only('showbianyuan', 'channel', 'label', 'book_length', 'book_status', 'sexual_orientation', 'book_tag'))) }}">{{ $orderby }}</a></li>
                @endforeach
            </ul>
        </span>

        &nbsp;&nbsp;&nbsp;
        <span class="pull-right">
            <a type="button" name="button" class="btn btn-md btn-primary sosad-button-control" href="{{ route('book.selector') }}">复合筛选</a>&nbsp;
            <a type="button" name="button" class="btn btn-md btn-primary sosad-button-control" href="{{ route('book.tags') }}">标签列表</a>
        </span>
    </div>
</div>
