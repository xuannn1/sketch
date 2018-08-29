
<div class="dropdown selector">
   <form method="POST" action="{{ route('books.filter') }}"  name="book_filter">
         {{ csrf_field() }}
         <div class="brief-selector smaller-10">
             <span class="button-group">
                <button type="button" id="dropdown-orig" class="dropdown-toggle dropdown-menu-narrow sosad-button-dropdown" data-toggle="dropdown">原创性 <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-filter" aria-labelledby="dropdown-orig">
                   <li class="dropdown-item">
                     <input type="checkbox" name='original[]' value='1' id='filter-orig' checked>
                     <label for='filter-orig' class="input-helper input-helper--checkbox">
                         原创
                     </label>
                   </li>
                   <li class="dropdown-item">
                     <input type="checkbox" name='original[]' value='2' id='filter-tongren' checked>
                     <label for='filter-tongren' class="input-helper input-helper--checkbox">
                         同人
                     </label>
                   </li>
                </ul>
             </span>
             <span class="button-group">
                <button type="button" id="dropdown-length" class="dropdown-toggle dropdown-menu-narrow sosad-button-dropdown" data-toggle="dropdown">篇幅 <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-filter" aria-labelledby="dropdown-length">
                    @foreach($book_info['book_lenth_info'] as $key=>$book_lenth)
                    <li class="dropdown-item">
                        <input type="checkbox" name='length[]' value={{$key}} id='filter-length-{{$key}}' checked>
                        <label for='filter-length-{{$key}}' class="input-helper input-helper--checkbox">
                            {{$book_lenth}}
                        </label>
                    </li>
                    @endforeach
                </ul>
             </span>
             <span class="button-group">
                <button type="button" class="dropdown-toggle dropdown-menu-narrow sosad-button-dropdown" data-toggle="dropdown">进度 <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-filter">
                    @foreach($book_info['book_status_info'] as $key=>$book_status)
                    <li class="dropdown-item">
                      <input type="checkbox" name='status[]' value={{$key}} id='filter-status-{{$key}}' checked>
                      <label for='filter-status-{{$key}}' class="input-helper input-helper--checkbox">
                          {{$book_status}}
                      </label>
                    </li>
                    @endforeach
                </ul>
             </span>
             <span class="button-group">
                <button type="button" class="dropdown-toggle dropdown-menu-narrow sosad-button-dropdown" data-toggle="dropdown">性向 <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-filter">
                    @foreach($book_info['sexual_orientation_info'] as $key=>$sexual_orientation)
                    <li class="dropdown-item">
                      <input type="checkbox" name='sexual_orientation[]' value={{$key}} id='filter-so-{{$key}}' checked>
                      <label for='filter-so-{{$key}}' class="input-helper input-helper--checkbox">
                          {{$sexual_orientation}}
                      </label>
                    </li>
                    @endforeach
                </ul>
             </span>
             <span class="button-group">
                <button type="button" class="dropdown-toggle dropdown-menu-narrow sosad-button-dropdown" data-toggle="dropdown">限制 <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-filter">
                    @foreach($book_info['rating_info'] as $key=>$rating)
                    <li class="dropdown-item">
                      <input type="checkbox" name='rating[]' value={{$key}} id='filter-rating-{{$key}}' checked>
                      <label for='filter-rating-{{$key}}' class="input-helper input-helper--checkbox">
                          {{$rating}}
                      </label>
                    </li>
                    @endforeach
                </ul>
             </span>
            <button type="submit" name="button" class="sosad-button-dropdown" style="width: auto; align: right;">
                <span class="glyphicon glyphicon-send"></span>
            </button>
        </div>
      </form>

      <form method="POST" action="{{ route('books.filter') }}" name="book_filter">
        {{ csrf_field() }}
        <button type="button" name="button" class="show-book sosad-button-more grayout smaller-10" onclick="show_book_selector()">
            <i class="fa fa-plus"></i>
            展开筛选
        </button>

        <div class="detailed-selector hidden">
            <button type="button" name="button" class="fold-book sosad-button-more grayout smaller-10" onclick="fold_book_selector()">
                <i class="fa fa-minus"></i>
                收起筛选
            </button>
            <div class="selector-panel">
                <div class="row">
                    <span class="filter-heading">类别筛选</span>
                </div>
                <div style="width: 100%;">
                    <span class="filter-label">原创性：</span>
                    <input type="checkbox" name='original[]' value='1' id='filterd-orig' checked>
                    <label for='filterd-orig' class="input-helper input-helper--checkbox">
                        原创
                    </label>
                    &nbsp;
                    <input type="checkbox" name='original[]' value='2' id='filterd-tongren' checked>
                    <label for='filterd-tongren' class="input-helper input-helper--checkbox">
                        同人
                    </label>
                    &nbsp;
                </div>

                <div class="">
                    <span class="filter-label">篇幅：</span>
                    @foreach($book_info['book_lenth_info'] as $key=>$book_lenth)
                      <input type="checkbox" name='length[]' value={{$key}} id='filterd-length-{{$key}}' checked>
                      <label for='filterd-length-{{$key}}' class="input-helper input-helper--checkbox">
                          {{$book_lenth}}
                      </label>
                      &nbsp;
                    @endforeach
                </div>

                <div class="">
                    <span class="filter-label">进度：</span>
                    @foreach($book_info['book_status_info'] as $key=>$book_status)
                        <input type="checkbox" name='status[]' value={{$key}} id='filterd-status-{{$key}}' checked>
                        <label for='filterd-status-{{$key}}' class="input-helper input-helper--checkbox">
                            {{$book_status}}
                        </label>
                        &nbsp;
                    @endforeach
                </div>

                <div class="">
                    <span class="filter-label">性向：</span>
                    @foreach($book_info['sexual_orientation_info'] as $key=>$sexual_orientation)
                        <input type="checkbox" name='sexual_orientation[]' value={{$key}} id='filterd-so-{{$key}}' checked>
                        <label for='filterd-so-{{$key}}' class="input-helper input-helper--checkbox">
                            {{$sexual_orientation}}
                        </label>
                        &nbsp;
                    @endforeach
                </div>

                <div class="">
                    <span class="filter-label">限制：</span>
                    @foreach($book_info['rating_info'] as $key=>$rating)
                        <input type="checkbox" name='rating[]' value={{$key}} id='filterd-rating-{{$key}}' checked>
                        <label for='filterd-rating-{{$key}}' class="input-helper input-helper--checkbox">
                            {{$rating}}
                        </label>
                        &nbsp;
                    @endforeach
                </div>
                <div class="">
                    <div class="">
                        <div class="row">
                            <span class="filter-heading">通用标签</span>
                        </div>
                        <?php $tag_info = 0; ?>
                        @foreach($all_book_tags['tags'] as $key=>$tag)
                            @if((Auth::check())||($tag->tag_group!==5))
                                @if(($tag_info<$tag->tag_info)&&($tag_info>0))
                                    <br>
                                @endif
                                <input type="checkbox" name='tag[]' value={{$tag->id}} id='filterd-tag-{{$tag->id}}'>
                                <label for='filterd-tag-{{$tag->id}}' class="input-helper input-helper--checkbox">
                                    {{$tag->tagname}}
                                </label>
                                <?php $tag_info = $tag->tag_info ?>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <button type="button" name="button" onclick="toggle_tags_tongren_yuanzhu()" class="sosad-button-more grayout smaller-10">
                <i class="fa fa-tags"></i>
                同人原著标签
            </button>
            <div class="tongren_yuanzhu hidden selector-panel">
                <span class="filter-label">同人原著标签：</span>
                @foreach($all_book_tags['tags_tongren_yuanzhu'] as $key=>$tag)
                    <input type="checkbox" name="tags_tongren_yuanzhu[]" value="$tag->id" id="filter-tr-{{$tag->id}}">
                    <label for="filter-tr-{{$tag->id}}" class="input-helper input-helper--checkbox">
                        {{$tag->tagname}}
                    </label>
                @endforeach
            </div>
            <button type="submit" name="button" class="sosad-button-dropdown" style="width: 100%;">
                <span class="glyphicon glyphicon-send"></span> &nbsp;
                提交
            </button>
        </div>
 </form>
</div>
