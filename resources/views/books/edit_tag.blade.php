@extends('layouts.default')
@section('title', '编辑标签信息')
@section('content')

<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
    <!-- 首页／版块／导航 -->
    <div class="">
        <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
        /
        <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
        /
        <a href="{{ route('thread.show_profile',$thread->id) }}">{{ $thread->title }}</a>/
        <a href="{{ route('books.edit', $thread->id) }}">编辑书籍</a>
        /编辑标签
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>编辑标签信息</h1>
            <h3>{{$thread->title}}</h3>
            <h4>{{$thread->brief}}</h4>
        </div>
        <div class="panel-body">
            @include('shared.errors')

            <form method="POST" action="{{ route('books.update_tag', $thread->id) }}" name="edit_book_tag">
                {{ csrf_field() }}


                <input name="channel_id" value="{{$thread->channel_id}}" class="hidden">

                <div class="">
                    <h4>请选择连载进度</h4>
                    @foreach ($tag_range['book_status_tags'] as $tag)
                    <label class="radio-inline"><input type="radio" name="book_status_tag" value="{{ $tag->id }}" {{ $selected_tags->keyBy('id')->get($tag->id)? 'checked':''  }}>{{ $tag->tag_name }}</label>
                    @endforeach
                </div>

                <div class="">
                    <h4>请选择书籍篇幅</h4>
                    @foreach ($tag_range['book_length_tags'] as $tag)
                    <label class="radio-inline"><input type="radio" name="book_length_tag" value="{{ $tag->id }}" {{ $selected_tags->keyBy('id')->get($tag->id)? 'checked':''  }}>{{ $tag->tag_name }}</label>
                    @endforeach
                </div>

                <div class="">
                    <h4>请选择书籍性向</h4>
                    @foreach ($tag_range['sexual_orientation_tags'] as $tag)
                    <label class="radio-inline"><input type="radio" name="sexual_orientation_tag" value="{{ $tag->id }}" {{ $selected_tags->keyBy('id')->get($tag->id)? 'checked':''  }}>{{ $tag->tag_name }}</label>
                    @endforeach
                </div>

                <div class="checkbox">
                    <h4>请选择自选标签</h4>
                    <?php $previous_tag_type = 0; ?>
                    @foreach ($tag_range['book_custom_Tags'] as $tag)
                    @if($previous_tag_type===0||$previous_tag_type!=$tag->tag_type)
                    <br><code>{{ $tag->tag_type }}:</code>
                    @endif
                    <label class="{{!$thread->is_bianyuan&&$tag->is_bianyuan? 'hidden':''}} {{$thread->channel_id<>$tag->channel_id&&$tag->channel_id<>0? 'hidden':''}}">
                        <input type="checkbox" class="alltags" name="tags[]" value="{{ $tag->id }}" {{ $selected_tags->keyBy('id')->get($tag->id)? 'checked':''  }}>{{ $tag->tag_name }}&nbsp;&nbsp;
                    </label>
                    <?php $previous_tag_type = $tag->tag_type ?>
                    @endforeach
                </div>

                <div class="">
                    <h5>（请注意，所有选择中不合常理的tag，比如说同时是“短篇”和“长篇”，又是“清水”又是“高H”，这样不符合实际情况的tag会被从tag列表中去除。）</h5>
                </div>

                <button type="submit" class="btn btn-md btn-danger sosad-button">确认修改标签</button>
            </form>
        </div>
    </div>
</div>
@stop
