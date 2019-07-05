@foreach($threads as $thread)
<article class="{{ 'item2id'.$thread->thread_id }}">
    <div class="row">
        <div class="col-xs-12 h5">
            @if($show_as_collections==1)
            <button class="btn btn-xs btn-danger sosad-button hidden cancel-button" type="button" name="button" onClick="cancelCollectionItem({{$thread->thread_id}},2,0)">取消收藏</button>
            <button class="btn btn-xs btn-warning sosad-button hidden cancel-button" type="button" name="button" onClick="ToggleKeepUpdateThread({{$thread->thread_id}})" Id="togglekeepupdatethread{{$thread->thread_id}}">{{$thread->keep_updated?'不再提醒':'接收提醒'}}</button>
            <span class="button-group">
                <button class="btn btn-xs btn-warning sosad-button hidden cancel-button" type="button" data-toggle="dropdown">添加到收藏单
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    @foreach($own_collection_thread_lists as $list)
                    <li><a type="button" name="button" onClick="item_add_to_collection({{$thread->thread_id}},2,{{$list->id}})">{{$list->title}}</a></li>
                    @endforeach
                </ul>
            </span>
            @elseif($show_as_collections==2)
            <button class="btn btn-xs btn-danger sosad-button hidden cancel-button" type="button" name="button" onClick="cancelCollectionItem({{$thread->thread_id}},2,{{$collection_list->id}})">取消收藏</button>
            <a class="btn btn-xs btn-danger sosad-button hidden cancel-button" href="#" data-toggle="modal" data-target="#TriggerCollectionComment{{ $thread->collection_id }}">添加/修改心得</a>
            <div class="modal fade" id="TriggerCollectionComment{{ $thread->collection_id }}" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('collection.store_comment', $thread->collection_id)}}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <textarea name="body" rows="6" class="form-control" placeholder="留下对这个收藏的评论心得：" data-provide="markdown"  id="collectioncomment{{$thread->collection_id}}">{{ $thread->collection_body }}</textarea>
                                <button type="button" onclick="retrievecache('collectioncomment{{$thread->collection_id}}')" class="sosad-button-control addon-button">恢复数据</button>
                            </div>
                            <div class="">
                                <button type="submit" class="btn btn-primary sosad-button btn-sm">提交收藏心得</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            <!-- thread title -->
            <span>
                @if($show_channel)
                <a class="btn btn-xs btn-success sosad-button tag-button-left tag-red" href="{{route('channel.show', $thread->channel_id)}}">{{$thread->channelname}}</a>
                @endif
                <a class="btn btn-xs btn-warning sosad-button tag-button-right tag-green" href="{{route('channel.show',['channel'=>$thread->channel_id,'label'=>$thread->label_id])}}">{{$thread->labelname}}</a>
                <span class="bigger-20"><strong><a href="{{ route('thread.show', $thread->thread_id) }}">
                    {{ $thread->title }}
                </a></strong></span>
                @if($thread->channel_id==2)
                @if($thread->tongren_yuanzhu_tagname)
                <a class="btn btn-xs btn-success tag-button-left tag-blue" href="{{ route('books.index', ['book_tag' => $thread->tongren_yuanzhu_tag_id]) }}">{{$thread->tongren_yuanzhu_tagname}}</a>
                @endif
                @if($thread->tongren_cp_tagname)
                <a class="btn btn-xs btn-warning tag-button-right tag-yellow" href="{{ route('books.index', ['book_tag'=>$thread->tongren_cp_tag_id]) }}">{{$thread->tongren_cp_tagname}}</a>
                @endif
                @endif
                @if( $thread->bianyuan == 1)
                <span class="badge bianyuan-tag badge-tag">限</span>
                @endif
                <small>
                    @if(!$thread->public)
                    <span class="glyphicon glyphicon-eye-close"></span>
                    @endif
                    @if($thread->locked)
                    <span class="glyphicon glyphicon-lock"></span>
                    @endif
                    @if($thread->noreply)
                    <span class="glyphicon glyphicon-warning-sign"></span>
                    @endif
                </small>
                @if( $thread->recommended == 1)
                <span class="recommend-label">
                    <span class="glyphicon glyphicon-grain recommend-icon"></span>
                    <span class="recommend-text">推</span>
                </span>
                @endif
                @if( $thread->jinghua > Carbon\Carbon::now())
                <span class="jinghua-label">
                    <span class="glyphicon glyphicon-thumbs-up jinghua-icon"></span>
                </span>
                @endif
                @if(($show_as_collections)&&($thread->updated))
                <span class="badge newchapter-badge badge-tag">有更新</span>
                @endif

            </span>
            <!-- thread title end   -->
            <!-- author  -->
            <span class = "pull-right">
                @if($thread->anonymous)
                <span>{{ $thread->majia ?? '匿名咸鱼'}}</span>
                @if((Auth::check()&&(Auth::user()->admin)))
                <span class="admin-anonymous"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->name }}</a></span>
                @endif
                @else
                <a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->name }}</a>
                @endif
            </span>
            <!-- author end -->
        </div>
        <div class="col-xs-12 h5 ">
            <span>{{ $thread->brief }}</span>
            <span class="pull-right smaller-10"><em><span class="glyphicon glyphicon-eye-open"></span>{{ $thread->viewed }}/<span class="glyphicon glyphicon glyphicon-comment"></span>{{ $thread->responded }}</em></span>
        </div>
        <div class="col-xs-12 h5 grayout brief">
            <span class="smaller-10"><a href="{{ route('thread.showpost', $thread->last_post_id) }}"> {!! Helper::trimtext($thread->last_post_body,20) !!}</a></span>
            <span class="pull-right smaller-10">{{ Carbon\Carbon::parse($thread->created_at)->diffForHumans() }}/{{ Carbon\Carbon::parse($thread->lastresponded_at)->diffForHumans() }}</span>
        </div>
        @if(($show_as_collections==2)&&($thread->collection_body))
        <div class="col-xs-12 h5">
            <div id="full{{$thread->collection_id}}" class="hidden main-text indentation">
                收藏心得：{!! Helper::wrapParagraphs($thread->collection_body) !!}
            </div>
            <span id="abbreviated{{$thread->collection_id}}">
                收藏心得：
                <span class="grayout">
                    {!! Helper::trimtext($thread->collection_body,70) !!}
                </span>
            </span>
            <a type="button" name="button" id="expand{{$thread->collection_id}}" onclick="expandpost('{{$thread->collection_id}}')" >展开</a>
        </div>
        @endif
    </div>
    <hr>
</article>
@endforeach
