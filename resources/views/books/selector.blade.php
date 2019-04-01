@extends('layouts.default')
@section('title', '文库筛选')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div>
                <form method="POST" action="{{ route('books.filter') }}"  name="book_filter">
                    {{ csrf_field() }}
                    <div class="">
                        <a type="button" name="button" class="btn btn-xs btn-primary sosad-button-control pull-right" href="{{ route('book.tags') }}">标签列表</a>
                    </div>


                    <div class="selector detailed-selector">
                        <h4>类别筛选：</h4>
                        <div class="">
                            <span class="lead">原创性：</span>
                            <input type="checkbox" name="original[]" value="1" checked />&nbsp;原创&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="original[]" value="2" checked />&nbsp;同人&nbsp;&nbsp;&nbsp;
                        </div>

                        <div class="">
                            <span class="lead">篇幅：</span>
                            @foreach(config('constants.book_info.book_lenth_info') as $key=>$book_lenth)
                            <input type="checkbox" name="length[]" value={{$key}} checked />&nbsp;{{$book_lenth}}&nbsp;&nbsp;&nbsp;
                            @endforeach
                        </div>

                        <div class="">
                            <span class="lead">进度：</span>
                            @foreach(config('constants.book_info.book_status_info') as $key=>$book_status)
                            <input type="checkbox" name="status[]" value={{$key}} checked/>&nbsp;{{$book_status}}&nbsp;&nbsp;&nbsp;
                            @endforeach
                        </div>

                        <div class="">
                            <span class="lead">性向：</span>
                            @foreach(config('constants.book_info.sexual_orientation_info') as $key=>$sexual_orientation)
                            <input type="checkbox" name="sexual_orientation[]" value={{$key}} checked/>&nbsp;{{$sexual_orientation}}&nbsp;&nbsp;&nbsp;
                            @endforeach
                        </div>

                        <div class="">
                            <span class="lead">限制：</span>
                            @foreach(config('constants.book_info.rating_info') as $key=>$rating)
                            <input type="checkbox" name="rating[]" value={{$key}} checked />&nbsp;{{$rating}}&nbsp;&nbsp;&nbsp;
                            @endforeach
                        </div>

                        <div class="">
                            <span class="lead">排序：</span>
                            @foreach(config('constants.book_info.orderby_info') as $key=>$orderby)
                            <input type="radio" name="orderby" value={{$key}} checked />&nbsp;{{$orderby}}&nbsp;&nbsp;&nbsp;
                            @endforeach
                        </div>

                        <div class="">
                            <div class="">
                                <h4>通用标签：</h4>
                                    <?php $tag_info = 0; ?>
                                    @foreach(Helper::tags_general() as $key=>$tag)
                                        @if((Auth::check())||($tag->tag_group!==5))
                                            @if(($tag_info<$tag->tag_info)&&($tag_info>0))
                                                <br>
                                            @endif
                                            <input type="checkbox" name="tag[]" value={{$tag->id}} />&nbsp;{{ $tag->tagname }}&nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php $tag_info = $tag->tag_info ?>
                                        @endif
                                    @endforeach
                            </div>
                        </div>
                        <button type="submit" name="button" class="btn btn-sm btn-primary sosad-button">提交</button>
                    </div>
                </form>
            </div>

            <hr>
        </div>
    </div>
</div>
@stop
