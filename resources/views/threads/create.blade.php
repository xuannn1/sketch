@extends('layouts.default')
@section('title', '发布新主题')
@section('content')
<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>&nbsp;在&nbsp;<a href="{{ route('channel.show', $channel->id) }}">{{ $channel->channelname }}</a>&nbsp;板块发布新主题</h4>
        </div>
        <div class="panel-body">
            @include('shared.errors')

            <form method="POST" action="{{ route('thread.store', $channel->id) }}">
                {{ csrf_field() }}
                <?php $labels = $channel->labels()->get(); ?>
                <h5><span style="color:#d66666">开帖前请务必阅读：<a href="http://sosad.fun/threads/136">《<u>版规的详细说明</u>》</a>，不要违反版规哦</span>。<br>想要在帖子中讨论<code>边缘限制</code>内容，请移步<a href="http://sosad.fun/threads/1863">《<u>午夜场申请专楼</u> 》</a>。<br>需要<code>帮助</code>可以前往版务区<a href="http://sosad.fun/threads/88">《<u>文章帖子删除、转移、编辑等专楼</u> 》</a>求助。关于网站使用的常规问题，可以查看如下页面：<a href="{{ route('about') }}">《<u>关于本站</u>》</a>，<a href="{{ route('help') }}">《<u>使用帮助</u>》</a>，或前往答疑楼<a href="http://sosad.fun/threads/49">《<u>废文网使用答疑</u>》提问</a>。<br>请不要轻率发布关于“<code>升级</code>”，“<code>签到</code>”，“<code>边限看不到</code>”等话题的日经讨论，不光会被下沉锁定，还有可能面临积分等级<code>清零</code>！<br>感谢开楼讨论!</h5>
                <h4>请选择主题对应类型：</h4>
                @foreach ($labels as $index => $label)
                <label class="radio-inline"><input type="radio" name="label" value="{{ $label->id }}">{{ $label->labelname }}</label>
                @endforeach
                <br>
                <br>
                <div class="form-group">
                    <label for="title">标题：</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="请输入不超过30字的标题">
                </div>

                <div class="form-group">
                    <label for="brief">简介：</label>
                    <input type="text" name="brief" class="form-control" value="{{ old('brief') }}" placeholder="请输入不超过50字的主题简介">
                </div>

                <div class="form-group">
                    <label for="body">新主题正文：</label>
                    <textarea id="mainbody" name="body" data-provide="markdown" rows="15" class="form-control" placeholder="请输入至少20字的内容">{{ old('body') }}</textarea>
                    <button type="button" onclick="retrievecache('mainbody')" class="sosad-button-control addon-button">恢复数据</button>
                    <button type="button" onclick="removespace('mainbody')" class="sosad-button-control addon-button">清理段首空格</button>
                    <button href="#" type="button" onclick="wordscount('mainbody');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
                </div>

                <div class="checkbox">
                    <label><input type="checkbox" name="anonymous" onclick="document.getElementById('majia').style.display = 'block'">马甲？</label>
                    <div class="form-group text-right" id="majia" style="display:none">
                        <input type="text" name="majia" class="form-control" value="{{Auth::user()->majia ?:'匿名咸鱼'}}">
                        <label for="majia"><small>(请输入不超过10字的马甲。马甲仅勾选“匿名”时有效)</small></label>
                    </div>
                </div>
                <div class="checkbox">
                    <!-- <label><input type="checkbox" name="public" checked>是否公开可见</label> -->
                    <label><input type="checkbox" name="noreply">是否禁止回帖</label>
                    <!-- <label><input type="checkbox" name="markdown">使用Markdown语法？</label> -->
                    <label><input type="checkbox" name="indentation" checked>段首缩进（自动空两格）？</label>
                </div>
                <button type="submit" class="btn btn-primary">发布</button>
            </form>
        </div>
    </div>
</div>

@stop
