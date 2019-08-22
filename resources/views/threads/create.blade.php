@extends('layouts.default')
@section('title', '发布新主题')
@section('content')
<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>&nbsp;在&nbsp;<a href="{{ route('channel.show', $channel->id) }}">{{ $channel->channel_name }}</a>&nbsp;板块发布新主题</h4>
        </div>
        <div class="panel-body">
            @include('shared.errors')

            <form method="POST" action="{{ route('threads.store', ['channel_id'=>$channel->id]) }}">
                {{ csrf_field() }}
                <div class="font-3">
                    <span>发文发帖前请务必阅读：<a href="http://sosad.fun/threads/136">《版规》</a>、<a href="{{ route('help') }}">《帮助》</a></span>
                </div>
                <div class="font-5">
                    请注意，不看帮助、版规，直接发布《帮助》、《版规》中已经写明内容的小白求助，直接发布和等级、升级、签到、看边限等相关的帖子，视作无意义水贴，见即锁且等级清零。
                </div>
                <h4>请选择主题对应类型：</h4>
                @foreach ($tags as $index => $tag)
                <label class="radio-inline"><input type="radio" name="tag" value="{{ $tag->id }}">{{ $tag->tag_name }}</label>
                @endforeach
                <br>
                <br>
                <input name="channel_id" value="{{$channel->id}}" class="hidden">
                <div class="form-group">
                    <label for="title">标题：</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="请输入不超过30字的标题">
                </div>

                <div class="form-group">
                    <label for="brief">简介：</label>
                    <input type="text" name="brief" class="form-control" value="{{ old('brief') }}" placeholder="请输入不超过50字的主题简介">
                </div>
                <div id="biaotiguiding" class="h6">
                    <span style="color:#d66666">标题、简介不得具有性描写、性暗示<span>，不得使用直白的脏话、黄暴词和明显涉及边缘的词汇，不应出现和文章无关的内容如qq号、邀请码、注册指路等，不应使用涉及性暗示的emoji。
                </div>
                <div>
                    <label for="brief">讨论内容是否含有“边缘限制”题材？</label>
                    <div class="font-6">
                        <a href="https://sosad.fun/posts/848">戳《版规》了解什么是“边限”。</a>
                        涉及“边限”的主题讨论，必须勾选边缘限制，在文案预警和规范讨论范畴。讨论内容和性描写相关的，简介需以“午夜场之”开头，预警围观咸鱼。
                    </div>
                    <div>
                        <label class="radio-inline"><input type="radio" name="is_bianyuan" value="isnot" {{ old('bianyuan')==='isnot'?'checked':''}}>非边缘限制敏感</label>
                        <label class="radio-inline"><input type="radio" name="is_bianyuan" value="is" {{ old('bianyuan')=='is'?'checked':''}}>边缘限制敏感</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="body">新主题正文：</label>
                    <textarea id="mainbody" name="body" data-provide="markdown" rows="15" class="form-control" placeholder="请输入至少20字的内容">{{ old('body') }}</textarea>
                    <button type="button" onclick="retrievecache('mainbody')" class="sosad-button-control addon-button">切换恢复数据</button>
                    <button href="#" type="button" onclick="wordscount('mainbody');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
                </div>

                <div class="checkbox">
                    <label><input type="checkbox" name="is_anonymous" onclick="document.getElementById('majia').style.display = 'block'">马甲？</label>
                    <div class="form-group text-right" id="majia" style="display:none">
                        <input type="text" name="majia" class="form-control" value="{{Auth::user()->majia ?:'匿名咸鱼'}}">
                        <label for="majia"><small>(请输入不超过10字的马甲。马甲仅勾选“匿名”时有效)</small></label>
                    </div>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name="is_public" checked>是否公开可见</label><br>
                    <label><input type="checkbox" name="no_reply">是否禁止回帖</label><br>
                    <label><input type="checkbox" name="use_indentation" checked>段首缩进（自动空两格）？</label><br>
                    <label><input type="checkbox" name="use_markdown">是否使用Markdown语法（不推荐）？</label>
                </div>
                <button type="submit" class="btn btn-primary btn-md">发布</button>
            </form>
        </div>
    </div>
</div>

@stop
