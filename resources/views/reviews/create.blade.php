@extends('layouts.default')
@section('title', $thread->title.'-写新书评')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 首页／版块／导航 -->
        <div class="">
            <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            /
            <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
            /
            <a href="{{ route('thread.show_profile',$thread->id) }}">{{ $thread->title }}</a>/新增书评
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>新增书评</h1>
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('review.store', $thread->id) }}" name="create_review">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="title"><h4>标题(25字内)：</h4></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="标题">
                    </div>
                    <div class="form-group">
                        <label for="brief"><h4>概要(40字内)：</h4></label>
                        <input type="text" name="brief" class="form-control" value="{{ old('brief') }}">
                    </div>
                    <div id="biaotiguiding" class="h6">
                        <span style="color:#d66666">（标题概要中不得具有性描写、性暗示，不得使用直白的脏话、黄暴词和明显涉及边缘的词汇。）<span>
                    </div>

                    <div class="">
                        <label for="thread_id"><h4>书籍ID：</h4></label>
                        <h6>（提示：书籍ID从书籍首页的网页地址里寻找，在网址"https://sosad.fun/thread/12345"中，12345就是书籍ID。）</h6>
                        <label><input type="text" style="width: 200px" name="thread_id" value="{{ old('thread_id')?? 0 }}"></label>
                    </div>

                    <div class="">
                        <label for="rating"><h4>评分（0-10）：</h4></label>
                        <label><input type="text" style="width: 40px" name="rating" value="{{ old('rating')?? 0 }}">分</label>
                    </div>

                    <div class="checkbox">
                        <label><input type="checkbox" name="recommend" >是否向他人推荐？</label>
                    </div>

                    <div class="form-group">
                        <label for="body"><h4>正文：</h4></label>
                        <textarea id="mainbody" name="body" rows="14" class="form-control" data-provide="markdown" placeholder="书评正文">{{ old('body') }}</textarea>
                        <button type="button" onclick="retrievecache('mainbody')" class="sosad-button-control addon-button">恢复数据</button>
                        <button href="#" type="button" onclick="wordscount('mainbody');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
                        <br>
                        <br>
                        <div class="">
                            <label><input type="checkbox" name="use_indentation" {{ $thread->use_indentation? 'checked':'' }}>段首缩进(每段前两个空格)？</label>
                            <br>
                            <label><input type="checkbox" name="use_markdown">使用Markdown语法（不推荐）?</label>
                        </div>
                    </div>

                    @if(!$thread->is_bianyuan)
                    <div class="checkbox">
                        <label><input type="checkbox" name="is_bianyuan" >是否边限书评？</label>
                    </div>
                    @endif
                    <button type="submit" class="btn btn-md btn-primary sosad-button">发布新书评</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
