@foreach($books as $book)
<article class="{{ 'item1id'.$book->thread_id }}">
    <div class="row">
        <div class="col-xs-12 h5">
            @if($show_as_collections==1)
            <button class="btn btn-xs btn-danger sosad-button hidden cancel-button" type="button" name="button" onClick="cancelCollectionItem({{ $book->thread_id }},1,0)">取消收藏</button>
            <button class="btn btn-xs btn-warning sosad-button hidden cancel-button" type="button" name="button" onClick="ToggleKeepUpdateThread({{$book->thread_id}})" Id="togglekeepupdatethread{{$book->thread_id}}">{{$book->keep_updated?'不再提醒':'接收提醒'}}</button>
            <span class="button-group">
                <button class="btn btn-xs btn-warning sosad-button hidden cancel-button" type="button" data-toggle="dropdown">添加到收藏单
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    @foreach($own_collection_book_lists as $list)
                    <li><a type="button" name="button" onClick="item_add_to_collection({{$book->thread_id}},1,{{$list->id}})">{{$list->title}}</a></li>
                    @endforeach
                </ul>
            </span>
            @elseif($show_as_collections==2)
            <button class="btn btn-xs btn-danger sosad-button hidden cancel-button" type="button" name="button" onClick="cancelCollectionItem({{ $book->thread_id }},1,{{ $collection_list->id }})">取消收藏</button>
            <a class="btn btn-xs btn-danger sosad-button hidden cancel-button" href="#" data-toggle="modal" data-target="#TriggerCollectionComment{{ $book->collection_id }}">添加/修改心得</a>
            <div class="modal fade" id="TriggerCollectionComment{{ $book->collection_id }}" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('collection.store_comment', $book->collection_id)}}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <textarea name="body" rows="6" class="form-control" placeholder="留下对这个收藏的评论心得：" data-provide="markdown"  id="collectioncomment{{$book->collection_id}}">{{ $book->collection_body }}</textarea>
                                <button type="button" onclick="retrievecache('collectioncomment{{$book->collection_id}}')" class="sosad-button-control addon-button">恢复数据</button>
                            </div>
                            <div class="">
                                <button type="submit" class="btn btn-primary sosad-button btn-sm">提交收藏心得</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            <span class="bigger-20">
                <strong>
                    <span>
                        <a href="{{ route('book.show', $book->book_id) }}">
                            {{ $book->title }}
                        </a>
                    </span>
                </strong>
            </span>
            <small>
                @if($book->tongren_yuanzhu_tagname)
                <a class="btn btn-xs btn-success tag-button-left tag-blue" href="{{ route('books.booktag', $book->tongren_yuanzhu_tag_id) }}">{{$book->tongren_yuanzhu_tagname}}</a>
                @endif
                @if($book->tongren_cp_tagname)
                <a class="btn btn-xs btn-warning tag-button-right tag-yellow" href="{{ route('books.booktag', $book->tongren_cp_tag_id) }}">{{$book->tongren_cp_tagname}}</a>
                @endif
                @if( $book->bianyuan == 1)
                <span class="badge bianyuan-tag badge-tag">边</span>
                @endif
                @if(($book->last_chapter_title)&&($book->last_chapter_post_id == $book->last_post_id)&&($book->lastaddedchapter_at > Carbon\Carbon::now()->subHours(12)->toDateTimeString()))
                <span class="badge newchapter-badge badge-tag">新</span>
                @endif
                @if(!$book->public)
                <span class="glyphicon glyphicon-eye-close"></span>
                @endif
                @if($book->locked)
                <span class="glyphicon glyphicon-lock"></span>
                @endif
                @if($book->noreply)
                <span class="glyphicon glyphicon-warning-sign"></span>
                @endif
            </small>
            @if(($show_as_collections)&&($book->updated))
            <span class="badge newchapter-badge">有更新</span>
            @endif
            <span class = "pull-right">
                @if($book->anonymous)
                <span>{{ $book->majia ?? '匿名咸鱼'}}</span>
                @if((Auth::check()&&(Auth::user()->admin)))
                <span class="admin-anonymous"><a href="{{ route('user.show', $book->user_id) }}">{{ $book->name }}</a></span>
                @endif
                @else
                <a href="{{ route('user.show', $book->user_id) }}">{{ $book->name }}</a>
                @endif
            </span>
        </div>
        <div class="col-xs-12 h5 brief">
            <span>{{ $book->brief }}</span>
            <span class = "pull-right smaller-10"><em><span class="glyphicon glyphicon-pencil"></span>{{ $book->total_char }}/<span class="glyphicon glyphicon-eye-open"></span>{{ $book->viewed }}/<span class="glyphicon glyphicon glyphicon-comment"></span>{{ $book->responded }}</em></span>
        </div>
        <div class="col-xs-12 h5 brief">
            <span class="grayout smaller-10"><a href="{{ route('book.showchapter', $book->last_chapter_id) }}">{{ $book->last_chapter_title }}</a></span>
            <span class="pull-right smaller-10"><em>
                <a href="{{ route('books.index',['channel'=>(int)($book->channel_id)]) }}">{{ config('constants.book_info')['channel_info'][$book->channel_id] }}</a>-<a href="{{ route('books.index',['book_length'=>$book->book_length]) }}">{{ config('constants.book_info')['book_lenth_info'][$book->book_length] }}</a>-<a href="{{ route('books.index',['book_status'=>$book->book_status]) }}">{{ config('constants.book_info')['book_status_info'][$book->book_status] }}</a>-<a href="{{ route('books.index',['label'=>$book->label_id]) }}">{{ $book->labelname }}</a>-<a href="{{ route('books.index',['sexual_orientation'=>$book->sexual_orientation]) }}">{{ config('constants.book_info')['sexual_orientation_info'][$book->sexual_orientation] }}</a>
            </em></span>
        </div>
        @if(($show_as_collections==2)&&($book->collection_body))
        <div class="col-xs-12 h5">
            <div id="full{{$book->collection_id}}" class="hidden main-text indentation">
                收藏心得：{!! Helper::wrapParagraphs($book->collection_body) !!}
            </div>
            <span id="abbreviated{{$book->collection_id}}">
                收藏心得：
                <span class="grayout">
                    {!! Helper::trimtext($book->collection_body,70) !!}
                </span>
            </span>
            <a type="button" name="button" id="expand{{$book->collection_id}}" onclick="expandpost('{{$book->collection_id}}')" >展开</a>
        </div>
        @endif
    </div>
    <hr class="narrow">
</article>
@endforeach
