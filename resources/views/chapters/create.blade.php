@extends('layouts.default')
@section('title', $thread->title.'-更新章节')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 首页／版块／导航 -->
        <div class="">
            <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            /
            <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
            /
            <a href="{{ route('thread.show_profile',$thread->id) }}">{{ $thread->title }}</a>/新增章节
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>新增章节</h1>
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('chapter.store', $thread->id) }}" name="create_chapter">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="title"><h4>章节名称(25字内)：</h4></label>

                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="章节名称">
                    </div>
                    <div class="form-group">
                        <label for="brief"><h4>章节概要（40字内）：</h4></label>
                        <input type="text" name="brief" class="form-control" value="{{ old('brief') }}">
                    </div>
                    <div id="biaotiguiding" class="h6">
                        <span style="color:#d66666">（章节名、章节概要中不得具有性描写、性暗示，不得使用直白的脏话、黄暴词和明显涉及边缘的词汇。）<span>
                    </div>

                    <div class="form-group">
                        <label for="body"><h4>正文：</h4></label>
                        <textarea id="mainbody" name="body" rows="14" class="form-control" data-provide="markdown" placeholder="章节正文">{{ old('body') }}</textarea>
                        <button type="button" onclick="retrievecache('mainbody')" class="sosad-button-control addon-button">恢复数据</button>
                        <button type="button" onclick="removespace('mainbody')" class="sosad-button-control addon-button">清理段首空格</button>
                        <button href="#" type="button" onclick="wordscount('mainbody');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
                        <br>
                        <br>
                        <div class="">
                            <label><input type="checkbox" name="use_indentation" {{ $thread->use_indentation? 'checked':'' }}>段首缩进(每段前两个空格)？</label>
                            <br>
                            <label><input type="checkbox" name="use_markdown">使用Markdown语法（不推荐）?</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="warning"><h4>章前预警(可省略)：</h4></label>
                        <textarea id="mainannotation" name="warning" data-provide="markdown" rows="2" class="form-control" placeholder="阅读提示或警告…">{{ old('warning') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="annotation"><h4>备注：</h4></label>
                        <textarea id="mainannotation" name="annotation" data-provide="markdown" rows="3" class="form-control" placeholder="作者有话说…">{{ old('annotation') }}</textarea>
                    </div>
                    @if(!$thread->is_bianyuan)
                    <div class="checkbox">
                        <label><input type="checkbox" name="is_bianyuan" >是否单章限制阅读？（非边缘限制文，但本章节含有<code>任意篇幅的性描写</code>或其他敏感内容的，请自觉勾选此项，本章将只对注册用户开放，避免搜索引擎抓取。应打边限而不打的删文封禁处理）</label>
                    </div>
                    @endif
                    <div class="">
                        <h5>（只有单章更新超过1000字的文章才算正常更新,更多使用帮助见<a href="{{ route('help') }}">《<u>帮助</u>》</a>）</h5>
                    </div>
                    <button type="submit" class="btn btn-md btn-primary sosad-button">发布新章节</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
