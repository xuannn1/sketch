@extends('layouts.default')
@section('title', '修改书籍')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @include('shared.errors')
        <!-- 首页／版块／导航 -->
        <div class="">
            <a type="btn btn-lg btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            /
            <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
            /
            <a href="{{ route('thread.show_profile',$thread->id) }}">{{ $thread->title }}</a>/修改书籍
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>{{$thread->title}}</h1>
                <h4>{{$thread->brief}}</h4>
            </div>
            <div class="panel-body text-center">
                <form method="POST" action="{{ route('books.update_chapter_index', $thread->id) }}" name="update_chapter_index">
                    {{ csrf_field() }}
                    @method('PATCH')
                    <table class="table">
                        <thead>
                            <tr>
                                <th>章节名</th>
                                <th>概要</th>
                                <th>序号</th>
                                <th>第一章</th>
                                <th>最新章</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                            <tr>
                                <th>
                                    <a href="{{ route('post.show', $post->id) }}" class = "">
                                        @if($post->is_bianyuan&&!$thread->is_bianyuan)
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                        @endif
                                        {{ $post->title }}</a>
                                </th>
                                <th>
                                    <a href="{{ route('post.show', $post->id) }}" class = "">{{ $post->brief }}</a>
                                </th>

                                <th>
                                    <label><input type="text" style="width: 80px" name="order_by[{{$post->id}}]" value="{{$post->order_by}}"></label>
                                </th>
                                <th>
                                    <label class="radio-inline"><input type="radio" name="first_component_id" value="{{$post->id}}"  {{ $thread->first_component_id==$post->id?'checked':''}}></label>
                                </th>
                                <th>
                                    <label class="radio-inline"><input type="radio" name="last_component_id" value="{{$post->id}}"  {{ $thread->last_component_id==$post->id?'checked':''}}></label>
                                </th>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="h6 text-left">
                        使用说明：请在每一章的右边填写这个章节的“序号”，章节会按照序号从小到大排列。<br>
                        可以手工选择其中一章作为“第一章”，读者点击“开始阅读”时会直接进入那一章开始读。
                        可以手工选择其中一章作为“最新章”，读者在文库和在收藏夹里会看到这一章的快捷入口。
                    </div>
                    <button type="submit" class="btn btn-lg btn-danger sosad-button">确认修改</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
