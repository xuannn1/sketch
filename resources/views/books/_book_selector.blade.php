
<div class="dropdown selector">
   <form method="POST" action="{{ route('books.filter') }}"  name="book_filter">
         {{ csrf_field() }}
         <div class="brief-selector">
             <span class="button-group">
                <button type="button" id="dropdown-orig" class="dropdown-toggle dropdown-menu-narrow sosad-button-dropdown" data-toggle="dropdown">原创性 <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-filter" aria-labelledby="dropdown-orig">
                   <li class="dropdown-item">
                       @include('shared._checkbox', ['name' => 'original[]', 'value' => '1', 'isChecked' => 'checked', 'label' => '原创', 'id' => 'filter-orig'])
                   </li>
                   <li class="dropdown-item">
                       @include('shared._checkbox', ['name' => 'original[]', 'value' => '2', 'isChecked' => 'checked', 'label' => '同人', 'id' => 'filter-tongren'])
                   </li>
                </ul>
             </span>
             <span class="button-group">
                <button type="button" id="dropdown-length" class="dropdown-toggle dropdown-menu-narrow sosad-button-dropdown" data-toggle="dropdown">篇幅 <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-filter" aria-labelledby="dropdown-length">
                    @foreach($book_info['book_lenth_info'] as $key=>$book_lenth)
                    <li class="dropdown-item">
                        @include('shared._checkbox', ['name' => 'length[]', 'value' => $key, 'isChecked' => 'checked', 'label' => $book_lenth, 'id' => 'filter-length-'.$key])
                    </li>
                    @endforeach
                </ul>
             </span>
             <span class="button-group">
                <button type="button" class="dropdown-toggle dropdown-menu-narrow sosad-button-dropdown" data-toggle="dropdown">进度 <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-filter">
                    @foreach($book_info['book_status_info'] as $key=>$book_status)
                    <li class="dropdown-item">
                        @include('shared._checkbox', ['name' => 'status[]', 'value' => $key, 'isChecked' => 'checked', 'label' => $book_status, 'id' => 'filter-status-'.$key])
                    </li>
                    @endforeach
                </ul>
             </span>
             <span class="button-group">
                <button type="button" class="dropdown-toggle dropdown-menu-narrow sosad-button-dropdown" data-toggle="dropdown">性向 <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-filter">
                    @foreach($book_info['sexual_orientation_info'] as $key=>$sexual_orientation)
                    <li class="dropdown-item">
                        @include('shared._checkbox', ['name' => '$sexual_orientation[]', 'value' => $key, 'isChecked' => 'checked', 'label' => $sexual_orientation, 'id' => 'filter-so-'.$key])
                    </li>
                    @endforeach
                </ul>
             </span>
             <span class="button-group">
                <button type="button" class="dropdown-toggle dropdown-menu-narrow sosad-button-dropdown" data-toggle="dropdown">限制 <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-filter">
                    @foreach($book_info['rating_info'] as $key=>$rating)
                    <li class="dropdown-item">
                        @include('shared._checkbox', ['name' => '$rating[]', 'value' => $key, 'isChecked' => 'checked', 'label' => $rating, 'id' => 'filter-rating-'.$key])
                    </li>
                    @endforeach
                </ul>
             </span>
            <button type="submit" name="button" class="sosad-button-dropdown" style="width: auto; align: right;">
                <span class="glyphicon glyphicon-send"></span>
            </button>
        </div>

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
                    @include('shared._checkbox', ['name' => 'original[]', 'value' => '1', 'isChecked' => 'checked', 'label' => '原创', 'id' => 'filterd-orig']) &nbsp;
                    @include('shared._checkbox', ['name' => 'original[]', 'value' => '2', 'isChecked' => 'checked', 'label' => '同人', 'id' => 'filterd-tongren']) &nbsp;
                </div>

                <div class="">
                    <span class="filter-label">篇幅：</span>
                    @foreach($book_info['book_lenth_info'] as $key=>$book_lenth)
                        @include('shared._checkbox', ['name' => 'length[]', 'value' => $key, 'isChecked' => 'checked', 'label' => $book_lenth, 'id' => 'filterd-length-'.$key]) &nbsp;
                    @endforeach
                </div>

                <div class="">
                    <span class="filter-label">进度：</span>
                    @foreach($book_info['book_status_info'] as $key=>$book_status)
                        @include('shared._checkbox', ['name' => 'status[]', 'value' => $key, 'isChecked' => 'checked', 'label' => $book_status, 'id' => 'filterd-status-'.$key]) &nbsp;
                    @endforeach
                </div>

                <div class="">
                    <span class="filter-label">性向：</span>
                    @foreach($book_info['sexual_orientation_info'] as $key=>$sexual_orientation)
                        @include('shared._checkbox', ['name' => '$sexual_orientation[]', 'value' => $key, 'isChecked' => 'checked', 'label' => $sexual_orientation, 'id' => 'filterd-so-'.$key])
                    @endforeach
                </div>

                <div class="">
                    <span class="filter-label">限制：</span>
                    @foreach($book_info['rating_info'] as $key=>$rating)
                        @include('shared._checkbox', ['name' => '$rating[]', 'value' => $key, 'isChecked' => 'checked', 'label' => $rating, 'id' => 'filterd-rating-'.$key])
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
                                @include('shared._checkbox', ['name' => '$tag[]', 'value' => $tag->id, 'isChecked' => '', 'label' => $tag->tagname, 'id' => 'filter-tag-'.$tag->id])
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
                    @include('shared._checkbox', ['name' => 'tags_tongren_yuanzhu[]', 'value' => $tag->id, 'isChecked' => '', 'label' => $tag->tagname, 'id' => 'filter-tr-'.$tag->id])
                @endforeach
            </div>
            <button type="submit" name="button" class="sosad-button-dropdown" style="width: 100%;">
                <span class="glyphicon glyphicon-send"></span> &nbsp;
                提交
            </button>
        </div>
 </form>
</div>
