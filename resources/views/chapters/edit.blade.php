@extends('layouts.default')
@section('title', $post->title.'-修改章节')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 首页／版块／导航 -->
        <div class="">
            <a type="btn btn-lg btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            /
            <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
            /
            <a href="{{ route('thread.show_profile',$thread->id) }}">{{ $thread->title }}</a>/
            <a href="{{ route('post.show',$post->id) }}">{{ $post->title }}</a>
            /
            修改章节
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>修改章节</h1>
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('chapter.update', $post->id) }}" name="update_chapter">
                    {{ csrf_field() }}
                    @method('PATCH')
                    <div class="form-group">
                        <label for="title"><h4>章节名称(25字内)：</h4></label>

                        <input type="text" name="title" class="form-control" value="{{ $post->title }}" placeholder="章节名称">
                    </div>
                    <div class="form-group">
                        <label for="brief"><h4>章节概要（40字内）：</h4></label>
                        <input type="text" name="brief" class="form-control" value="{{ $post->brief }}">

                    </div>
                    <div id="biaotiguiding" class="h6 text-center grayout">
                        <span>（章节名、章节概要中不得具有性描写、性暗示，不得使用直白的脏话、黄暴词和明显涉及边缘的词汇，不应出现和文章无关的内容，如qq号、邀请码、注册指路等。）</span>
                    </div>

                    <div class="form-group">
                        <label for="body"><h4>正文：</h4></label>
                        <textarea id="mainbody" name="body" rows="14" class="form-control" data-provide="markdown" placeholder="章节正文">{{ $post->body }}</textarea>
                        <button type="button" onclick="retrievecache('mainbody')" class="sosad-button-control addon-button">切换恢复数据</button>
                        <button type="button" onclick="removespace('mainbody')" class="sosad-button-control addon-button">清理段首空格</button>
                        <button href="#" type="button" onclick="wordscount('mainbody');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
                        <br>
                        <br>
                        <div class="">
                            <label><input type="checkbox" name="use_indentation" {{ $post->use_indentation? 'checked':'' }}>段首缩进(每段前两个空格)？</label>
                            <br>
                            <label><input type="checkbox" name="use_markdown">使用Markdown语法（不推荐）?</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="warning"><h4>章前预警(可省略)：</h4></label>
                        <textarea id="mainannotation" name="warning" data-provide="markdown" rows="2" class="form-control" placeholder="阅读提示或警告…">{{ $chapter->warning }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="annotation"><h4>备注：</h4></label>
                        <textarea id="mainannotation" name="annotation" data-provide="markdown" rows="3" class="form-control" placeholder="作者有话说…">{{ $chapter->annotation }}</textarea>
                    </div>
                    @if(!$thread->is_bianyuan)
                    <div class="checkbox">
                        <label><input type="checkbox" name="is_bianyuan" {{ $post->is_bianyuan?'checked':'' }}>是否单章限制阅读？（非边缘限制文，但本章节含有<code>任意篇幅的性描写</code>或其他敏感内容的，请自觉勾选此项，本章将只对注册用户开放，避免搜索引擎抓取。应打边限而不打的删文封禁处理）</label>
                    </div>
                    @endif
                    <button type="submit" class="btn btn-md btn-primary sosad-button">确认修改章节</button>
                </form>
                <br>
                <div class="font-6 text-center">
                    （想删除章节？为免误删，请先把章节转化成普通回帖——在章节正文右下角找到齿轮符号）
                </div>
            </div>
        </div>
    </div>
</div>
@stop
