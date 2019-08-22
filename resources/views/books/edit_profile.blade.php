@extends('layouts.default')
@section('title', '编辑文案信息')
@section('content')

<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
    <!-- 首页／版块／导航 -->
    <div class="">
        <a type="btn btn-lg btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
        /
        <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
        /
        <a href="{{ route('thread.show_profile',$thread->id) }}">{{ $thread->title }}</a>/
        <a href="{{ route('books.edit', $thread->id) }}">编辑书籍</a>
        /编辑文案
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>编辑文案信息</h1>
            <h3>{{$thread->title}}</h3>
            <h4>{{$thread->brief}}</h4>
        </div>
        <div class="panel-body">
            @include('shared.errors')

            <form method="POST" action="{{ route('books.update_profile', $thread->id) }}" name="edit_book_profile">
                {{ csrf_field() }}
                @method('PATCH')
                <div>
                    <h5><span style="color:#d66666">发文发帖前请务必阅读：<a href="http://sosad.fun/threads/136">《<u>版规的详细说明</u>》</a><br>关于网站使用的常规问题：<a href="{{ route('help') }}">《<u>使用帮助</u>》</a></span></h5>
                    <br>
                </div>
                <input name="channel_id" value="{{$thread->channel_id}}" class="hidden">

                <div class="form-group">
                    <label for="title"><h4>1. 标题：</h4></label>
                    <div id="biaotiguiding" class="h6">
                        标题请规范，尊重汉语语法规则，避免火星文、emoji、乱用符号标点等。文章类型、CP、背景、版本相关信息请在简介，文案 ，标签 ，备注等处展示，不要放入标题。<span style="color:#d66666">标题不得含有性描写、性暗示。<span>
                    </div>
                    <input type="text" name="title" class="form-control" value="{{ $thread->title }}" placeholder="请输入不超过20字的标题">
                </div>

                <div class="form-group">
                    <label for="brief"><h4>2. 简介：</h4></label>
                    <div id="biaotiguiding" class="h6">
                        <span style="color:#d66666">简介不得具有性描写、性暗示<span>，不得使用直白的脏话、黄暴词和明显涉及边缘的词汇，不应出现和文章无关的内容如qq号、邀请码、注册指路等，不应使用emoji。
                    </div>
                    <input type="text" name="brief" class="form-control" value="{{$thread->brief}}" placeholder="请输入不超过25字的简介">
                </div>

                <div class="form-group">
                    <label for="body"><h4>3. 文案：</h4></label>
                    <div id="body" class="h6">
                        文案不是正文，文案属于对文章的简单介绍。文案采用“居中排列”的板式，而不是“向左对齐”。如果在这里发布正文，阅读效果不好。正文请在发布文章后，于文案下选择“新建章节”来建立。
                    </div>
                    <textarea name="body" id="markdowneditor" data-provide="markdown" rows="5" class="form-control">{{ $thread->body }}</textarea>
                    <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-control addon-button">切换恢复数据</button>
                    <button href="#" type="button" onclick="wordscount('markdowneditor');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
                </div>

                <div>
                    <label for="is_bianyuan"><h4>4. 是否边缘限制题材？</h4></label>
                    <div id="bianyuan" class="h6">
                        <a href="http://sosad.fun/threads/136">详见<u>《版规》</u></a>：文章含肉超过20%，或开头具有较明显的性行为描写，或题材包含NP、人兽、触手、父子、乱伦、生子、产乳、abo、军政、黑道、性转……等边缘限制敏感题材，或估计不适合未成年人观看的，请务必勾选此项。勾选后，本文将不受搜索引擎直接抓取，不被未注册游客观看。<span style="color:#d66666">属于边限题材却未勾选边缘标记即发文的，严肃处理。</span>
                    </div>
                    <div>
                        <label class="radio-inline"><input type="radio" name="is_bianyuan" value="isnot" {{$thread->is_bianyuan?'':'checked'}}>非边缘限制敏感</label>
                        <label class="radio-inline"><input type="radio" name="is_bianyuan" value="is"  {{$thread->is_bianyuan?'checked':''}}>边缘限制敏感</label>
                    </div>
                </div>

                <div class="checkbox">
                    <label><input type="checkbox" name="is_anonymous" {{ $thread->is_anonymous? 'checked':'' }}>马甲？</label>
                   <div class="form-group text-right" id="majia">
                       <input type="text" name="majia" class="form-control" value="{{ $thread->majia ?? '匿名咸鱼' }}" readonly>
                       <label for="majia"><small>(马甲仅勾选“匿名”时有效，可以更改披马与否，但马甲名称不能再修改)</small></label>
                   </div>
                </div>

                <div class="checkbox">
                    <div class="">
                        <label><input type="checkbox" name="use_indentation" {{ $thread->use_indentation?'checked':''}}>段首缩进（自动空两格）？</label>
                    </div>
                    <div class="">
                        <label><input type="checkbox" name="is_public" {{$thread->is_public?'checked':''}}>是否公开可见？</label>
                    </div>
                    <div class="">
                        <label><input type="checkbox" name="no_reply"{{$thread->no_reply?'checked':''}}>是否禁止回帖？</label>
                    </div>
                    <div class="">
                        <label><input type="checkbox" name="use_markdown" {{$thread->user_markdown?'checked':''}}>使用Markdown语法？</label>
                    </div>
                    <div class="">
                        <label><input type="checkbox" name="download_as_thread" {{$thread->download_as_thread?'checked':''}}>开放讨论帖形式的书评下载？（正文+全部评论，按回帖时间顺序排列）</label>
                    </div>
                    <div class="">
                        <label><input type="checkbox" name="download_as_book"  {{$thread->download_as_book?'checked':''}}>开放脱水书籍下载？（不含回帖的正文章节）</label>
                    </div>

                </div>
                <button type="submit" class="btn btn-lg btn-danger sosad-button">确认修改文案</button>
            </form>
        </div>
    </div>
</div>
@stop
