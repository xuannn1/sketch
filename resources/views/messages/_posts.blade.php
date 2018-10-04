@foreach($posts as $post)
<article class="margin5">
    <div class="row">
        <div class="col-xs-12 margin5">
            <span id="simple{{$post->id}}" class="grayout">
              <a href="{{ route('thread.showpost', $post->id) }}">
                {{ $post->anonymous ? ($post->majia ?? '匿名咸鱼'): $post->name }}&nbsp;{{ Carbon\Carbon::parse($post->created_at)->diffForHumans() }}回复了您的主题&nbsp;{{ $post->thread_title }}
              </a>
            </span>
        </div>
        <div class="col-xs-12">
            <span id="full{{$post->id}}" class="hidden">
                <h5 class="text-center"><strong>{{ $post->title }}</strong></h5>
                <!-- <div class="text-center">
                    <span>
                        @if ($post->anonymous)
                        <p>{{ $post->majia ?? '匿名咸鱼'}}</p>
                        @if((Auth::check()&&(Auth::user()->admin)))
                        <p class="admin-anonymous"><a href="{{ route('user.show', $post->user_id) }}">{{ $post->name }}</a></p>
                        @endif
                        @else
                        <a href="{{ route('user.show', $post->user_id) }}">{{ $post->name }}</a>
                        @endif
                    </span>
                    <span class="grayout">
                        发表于 {{ Carbon\Carbon::parse($post->created_at)->diffForHumans() }}
                        @if($post->created_at < $post->edited_at )
                        修改于 {{ Carbon\Carbon::parse($post->edited_at)->diffForHumans() }}
                        @endif
                    </span>
                </div> -->
                <div class="main-text">
                    @if($post->markdown)
                    {!! Helper::sosadMarkdown($post->body) !!}
                    @else
                    {!! Helper::wrapParagraphs($post->body) !!}
                    @endif
                </div>
                @include('posts._post_simplevote')

            </span>
            <span id="abbreviated{{$post->id}}" class="main-text"><strong>{{ $post->title }}</strong>{{ $post->title ? ' ': ''}} {!! Helper::trimtext($post->body,60) !!}</span>
            <a type="button" name="button" id="expand{{$post->id}}" onclick="expandpost('{{$post->id}}', true)" class="pull-right grayout">展开</a>
            </div>
        </div>
    </article>
    @endforeach
