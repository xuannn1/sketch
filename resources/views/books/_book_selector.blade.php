
<div>
    <form method="POST" action="{{ route('books.filter') }}"  name="book_filter">
        {{ csrf_field() }}
        <div class="">
            <h4>原创性：</h4>
            <div class="">
                <input type="checkbox" name="original[]" value="1"checked />&nbsp;原创&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="original[]" value="2"checked />&nbsp;同人&nbsp;&nbsp;&nbsp;
            </div>
        </div>

        <div class="">
            <h4>篇幅：</h4>
            <div class="">
                @foreach($book_info['book_lenth_info'] as $key=>$book_lenth)
                <input type="checkbox" name="length[]" value={{$key}} checked />&nbsp;{{$book_lenth}}&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>
        </div>

        <div class="">
            <h4>进度：</h4>
            <div class="">
                @foreach($book_info['book_status_info'] as $key=>$book_status)
                <input type="checkbox" name="status[]" value={{$key}} checked />&nbsp;{{$book_status}}&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>
        </div>

        <div class="">
            <h4>性向：</h4>
            <div class="">
                @foreach($book_info['sexual_orientation_info'] as $key=>$sexual_orientation)
                <input type="checkbox" name="sexual_orientation[]" value={{$key}} checked />&nbsp;{{$sexual_orientation}}&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>
        </div>

        <div class="">
            <h4>限制：</h4>
            <div class="">
                @foreach($book_info['rating_info'] as $key=>$rating)
                <input type="checkbox" name="rating[]" value={{$key}} checked />&nbsp;{{$rating}}&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>
        </div>

        <div class="">
            <h4>各色标签：</h4>
            <div class="">
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

        <button type="submit" name="button" class="btn btn-xs btn-primary sosad-button">提交</button>
    </form>
</div>
