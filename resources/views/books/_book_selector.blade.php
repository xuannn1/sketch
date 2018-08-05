<div>
    <form method="POST" action="{{ route('books.filter') }}"  name="book_filter">
        {{ csrf_field() }}
        <div class="brief-selector">

        </div>
        <div class="detailed-selector">
            <div class="">
                <span class="lead">原创性：</span>
                <input type="checkbox" name="original[]" value="1"checked />&nbsp;原创&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="original[]" value="2"checked />&nbsp;同人&nbsp;&nbsp;&nbsp;
            </div>

            <div class="">
                <span class="lead">篇幅：</span>
                @foreach($book_info['book_lenth_info'] as $key=>$book_lenth)
                <input type="checkbox" name="length[]" value={{$key}} checked />&nbsp;{{$book_lenth}}&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>

            <div class="">
                <span class="lead">进度：</span>
                @foreach($book_info['book_status_info'] as $key=>$book_status)
                <input type="checkbox" name="status[]" value={{$key}} checked />&nbsp;{{$book_status}}&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>

            <div class="">
                <span class="lead">性向：</span>
                @foreach($book_info['sexual_orientation_info'] as $key=>$sexual_orientation)
                <input type="checkbox" name="sexual_orientation[]" value={{$key}} checked />&nbsp;{{$sexual_orientation}}&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>

            <div class="">
                <span class="lead">限制：</span>
                @foreach($book_info['rating_info'] as $key=>$rating)
                <input type="checkbox" name="rating[]" value={{$key}} checked />&nbsp;{{$rating}}&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>
            <div class="">
                <h4>通用标签：</h4>
                    <?php $tag_info = 0; ?>
                    @foreach($all_book_tags['tags'] as $key=>$tag)
                        @if((Auth::check())||($tag->tag_group!==5))
                            @if(($tag_info<$tag->tag_info)&&($tag_info>0))
                                <br>
                            @endif
                            <input type="checkbox" name="tag[]" value={{$tag->id}} />&nbsp;{{ $tag->tagname }}&nbsp;&nbsp;&nbsp;
                            <?php $tag_info = $tag->tag_info ?>
                        @endif
                    @endforeach
                </div>
                <h4>同人原著标签：</h4>
                    <?php $tag_info = 0; ?>
                    @foreach($all_book_tags['tags'] as $key=>$tag)
                        @if((Auth::check())||($tag->tag_group!==5))
                            @if(($tag_info<$tag->tag_info)&&($tag_info>0))
                                <br>
                            @endif
                            <input type="checkbox" name="tag[]" value={{$tag->id}} />&nbsp;{{ $tag->tagname }}&nbsp;&nbsp;&nbsp;
                            <?php $tag_info = $tag->tag_info ?>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>



        <button type="submit" name="button" class="btn btn-xs btn-primary sosad-button">提交</button>
    </form>


</div>
