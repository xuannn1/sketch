<div>
    <form method="POST" action="{{ route('books.filter') }}"  name="book_filter">
        {{ csrf_field() }}
        <div class="selector brief-selector">
            <div class="dropdown">
                <span class="button-group">
                    <button type="button" class="btn btn-default btn-xs dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">原创性<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><input type="checkbox" name="original[]" value="1"checked />&nbsp;原创</li>
                        <li><input type="checkbox" name="original[]" value="2"checked />&nbsp;同人</li>
                    </ul>
                </span>
                <span class="button-group">
                    <button type="button" class="btn btn-default btn-xs dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">篇幅<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        @foreach(config('constants.book_info.book_lenth_info') as $key=>$book_lenth)
                        <li><input type="checkbox" name="length[]" value={{$key}} checked />&nbsp;{{$book_lenth}}</li>
                        @endforeach
                    </ul>
                </span>
                <span class="button-group">
                    <button type="button" class="btn btn-default btn-xs dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">进度<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        @foreach(config('constants.book_info.book_status_info') as $key=>$book_status)
                        <li><input type="checkbox" name="status[]" value={{$key}} checked />&nbsp;{{$book_status}}</li>
                        @endforeach
                    </ul>
                </span>
                <span class="button-group">
                    <button type="button" class="btn btn-default btn-xs dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">性向<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        @foreach(config('constants.book_info.sexual_orientation_info') as $key=>$sexual_orientation)
                        <li><input type="checkbox" name="sexual_orientation[]" value={{$key}} checked />&nbsp;{{$sexual_orientation}}</li>
                        @endforeach
                    </ul>
                </span>
                <span class="button-group">
                    <button type="button" class="btn btn-default btn-xs dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">限制<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        @foreach(config('constants.book_info.rating_info') as $key=>$rating)
                        <li><input type="checkbox" name="rating[]" value={{$key}} checked />&nbsp;{{$rating}}</li>
                        @endforeach
                    </ul>
                </span>
                <span class="button-group">
                    <button type="button" class="btn btn-default btn-xs dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">排序<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        @foreach(config('constants.book_info.orderby_info') as $key=>$orderby)
                        <li><input type="radio" name="orderby" value={{$key}} checked/>{{ $orderby }}</li>
                        @endforeach
                    </ul>
                </span>
                <button type="submit" name="button" class="btn btn-xs btn-primary sosad-button">提交</button>
                <button type="button" name="button" class="btn btn-xs btn-primary sosad-button-control pull-right" onclick="show_book_selector()">展开筛选</button>
            </div>
        </div>

        <div class="selector detailed-selector hidden">
            <h4>类别筛选：</h4>
            <div class="">
                <span class="lead">原创性：</span>
                <input type="checkbox" name="original[]" value="1" checked disabled/>&nbsp;原创&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="original[]" value="2" checked disabled/>&nbsp;同人&nbsp;&nbsp;&nbsp;
            </div>

            <div class="">
                <span class="lead">篇幅：</span>
                @foreach(config('constants.book_info.book_lenth_info') as $key=>$book_lenth)
                <input type="checkbox" name="length[]" value={{$key}} checked disabled/>&nbsp;{{$book_lenth}}&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>

            <div class="">
                <span class="lead">进度：</span>
                @foreach(config('constants.book_info.book_status_info') as $key=>$book_status)
                <input type="checkbox" name="status[]" value={{$key}} checked disabled/>&nbsp;{{$book_status}}&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>

            <div class="">
                <span class="lead">性向：</span>
                @foreach(config('constants.book_info.sexual_orientation_info') as $key=>$sexual_orientation)
                <input type="checkbox" name="sexual_orientation[]" value={{$key}} checked disabled/>&nbsp;{{$sexual_orientation}}&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>

            <div class="">
                <span class="lead">限制：</span>
                @foreach(config('constants.book_info.rating_info') as $key=>$rating)
                <input type="checkbox" name="rating[]" value={{$key}} checked disabled/>&nbsp;{{$rating}}&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>

            <div class="">
                <span class="lead">排序：</span>
                @foreach(config('constants.book_info.orderby_info') as $key=>$orderby)
                <input type="radio" name="orderby" value={{$key}} checked disabled/>&nbsp;{{$orderby}}&nbsp;&nbsp;&nbsp;
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
                <button type="button" name="button" onclick="toggle_tags_tongren_yuanzhu()" class="btn btn-sm btn-primary sosad-button-control">同人原著标签</button>
                <div class="tongren_yuanzhu hidden">
                    <h4>同人原著标签：</h4>
                        @foreach(Helper::tags_tongren_yuanzhu() as $key=>$tag)
                            <input type="checkbox" name="tags[]" value={{$tag->id}} />&nbsp;{{ $tag->tagname }}&nbsp;&nbsp;&nbsp;
                        @endforeach
                </div>
            </div>
            <button type="submit" name="button" class="btn btn-sm btn-primary sosad-button">提交</button>
            <button type="button" name="button" class="btn btn-sm btn-primary sosad-button-control pull-right" onclick="fold_book_selector()">收起筛选</button>
        </div>
    </form>
</div>
