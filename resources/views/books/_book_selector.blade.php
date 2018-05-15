
<div class="dropdown">
    <form method="POST" action="{{ route('books.filter') }}"  name="book_filter">
        {{ csrf_field() }}
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
                @foreach($book_info['book_lenth_info'] as $key=>$book_lenth)
                <li><input type="checkbox" name="length[]" value={{$key}} checked />&nbsp;{{$book_lenth}}</li>
                @endforeach
            </ul>
        </span>
        <span class="button-group">
            <button type="button" class="btn btn-default btn-xs dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">进度<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach($book_info['book_status_info'] as $key=>$book_status)
                <li><input type="checkbox" name="status[]" value={{$key}} checked />&nbsp;{{$book_lenth}}</li>
                @endforeach
            </ul>
        </span>
        <span class="button-group">
            <button type="button" class="btn btn-default btn-xs dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">性向<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach($book_info['sexual_orientation_info'] as $key=>$sexual_orientation)
                <li><input type="checkbox" name="sexual_orientation[]" value={{$key}} checked />&nbsp;{{$sexual_orientation}}</li>
                @endforeach
            </ul>
        </span>
        <span class="button-group">
            <button type="button" class="btn btn-default btn-xs dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">边缘<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach($book_info['rating_info'] as $key=>$rating)
                <li><input type="checkbox" name="rating[]" value={{$key}} checked />&nbsp;{{$rating}}</li>
                @endforeach
            </ul>
        </span>
        <button type="submit" name="button" class="btn btn-xs btn-primary sosad-button">提交</button>
    </form>
</div>
