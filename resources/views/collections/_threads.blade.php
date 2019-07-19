@foreach($collections as $collection)
<?php $thread = $collection->thread ?>
@if($thread)
<article class="{{ 'thread'.$thread->id }}">
    <div class="row">
        <div class="col-xs-12 h5">
            <!-- thread title -->
            <span>

                <span class="button-group">
                    <button type="button" class="dropdown-cog" data-toggle="dropdown"><i class="fa fa-cog " aria-hidden="true"></i></button>
                    <ul class="dropdown-menu">
                        <li><a type="button" name="button" onclick="cancel_collection({{$collection->id}})">取消收藏</a></li>
                        <li>
                            <a type="button" name="button" class="{{ $collection->keep_updated?'':'hidden' }}" id="nomoreupdate{{$collection->id}}" onclick="collection_toggle_keep_update({{$collection->id}}, 0)">取消收藏提醒</a>
                            <a type="button" name="button" class="{{ $collection->keep_updated? 'hidden':'' }}"  id="keepupdate{{$collection->id}}" onclick="collection_toggle_keep_update({{$collection->id}}, 1)">收藏提醒</a>
                        </li>
                        @foreach($groups as $group)
                        @if($show_collection_tab!=$group->id)
                        <li><a type="button" name="button" onclick="collection_change_group({{ $collection->id }},{{ $group->id }})">转移到《{{$group->name}}》</a></li>
                        @endif
                        @endforeach
                        @if($show_collection_tab!='default')
                        <li><a type="button" name="button" onclick="collection_change_group({{ $collection->id }},0)">转移到默认收藏</a></li>
                        @endif

                    </ul>
                </span>
                <span class="badge newchapter-badge badge-tag">{{ $thread->channel()->channel_name }}</span>
                <a href="{{ route('thread.show',$thread->id) }}" class="bigger-10">{{ $thread->title }}</a>
                <small>
                    @if( !$thread->is_public )
                    <span class="glyphicon glyphicon-eye-close"></span>
                    @endif
                    @if( $thread->is_locked )
                    <span class="glyphicon glyphicon-lock"></span>
                    @endif
                    @if( $thread->no_reply )
                    <span class="glyphicon glyphicon-warning-sign"></span>
                    @endif
                </small>
                @if( $thread->is_bianyuan == 1)
                <span class="badge bianyuan-tag badge-tag">限</span>
                @endif
                @if( $thread->tags->contains('tag_type', '编推') )
                <span class="recommend-label">
                    <span class="glyphicon glyphicon-grain recommend-icon"></span>
                    <span class="recommend-text">推</span>
                </span>
                @endif
                @if( $thread->tags->contains('tag_type', '管理') )
                <span class="jinghua-label">
                    <span class="glyphicon glyphicon-thumbs-up jinghua-icon"></span>
                </span>
                @endif
                @if($collection->updated)
                <span class="badge newchapter-badge badge-tag">有更新</span>
                @endif

            </span>
            <!-- thread title end   -->
            <!-- author  -->
            <span class = "pull-right">
                @if($thread->author)
                    @if($thread->is_anonymous)
                        <span>{{ $thread->majia ?? '匿名咸鱼'}}</span>
                        @if((Auth::check()&&(Auth::user()->isAdmin())))
                            <span class="admin-anonymous"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->author->name }}</a></span>
                        @endif
                    @else
                        <a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->author->name }}</a>
                    @endif
                @endif
            </span>
            <!-- author end -->
        </div>
        <div class="col-xs-12 h5 ">
            <span>{{ $thread->brief }}</span>
            <span class="pull-right smaller-10"><em><span class="glyphicon glyphicon-eye-open"></span>{{ $thread->view_count }}/<span class="glyphicon glyphicon glyphicon-comment"></span>{{ $thread->reply_count }}</em></span>
        </div>
        <div class="col-xs-12 h5 grayout brief smaller-10">
            @if($thread->channel()->type==='book')
            <span class="grayout smaller-10"><a href="#">{{ $thread->last_component? $thread->last_component->title.' '.StringProcess::simpletrim($thread->last_component->brief, 10):''}}</a></span>
            <span class="pull-right smaller-10">
                @foreach($thread->tags as $tag)
                @if($tag->tag_type!='编推')
                <a href="{{ route('books.index', array_merge(['withTag' => StringProcess::mergeWithTag($tag->id, request()->withTag)],request()->only('excludeTag','inChannel','withBianyuan','ordered'))) }}">{{ $tag->tag_name }}</a>
                @endif
                @endforeach
            </span>
            @else
            <span class="smaller-10"><a href="{{ route('thread.showpost', $thread->last_post_id) }}">{{ $thread->last_post? StringProcess::simpletrim($thread->last_post->brief, 25):' ' }}</a></span>
            <span class="pull-right smaller-10">{{ $thread->created_at->diffForHumans() }}/{{ $thread->responded_at->diffForHumans() }}</span>
            @endif

        </div>
    </div>
<hr>
</article>
@endif
@endforeach
