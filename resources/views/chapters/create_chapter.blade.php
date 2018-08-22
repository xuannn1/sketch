@extends('layouts.default')
@section('title', $book->thread->title.'-更新章节')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="site-map">
        <a href="{{ route('home') }}">
          <span><i class="fa fa-home"></i>&nbsp;首页</span></a>
          /
          <a href="{{ route('channel.show', $book->thread->channel_id) }}">{{ $book->thread->channel->channelname }}</a>
          /
          <a href="{{ route('channel.show', ['channel'=>$book->thread->channel_id,'label'=>$book->thread->label_id]) }}">{{ $book->thread->label->labelname }}</a>
          /
          <a href="{{ route('book.show',$book->id) }}">{{ $book->thread->title }}</a>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
              <h1>新建章节</h1>
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('book.storechapter', $book) }}" name="create_book_chapter">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="title"><h4>章节名称：</h4></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="章节名称">
                    </div>
                    <div class="form-group">
                        <label for="brief"><h4>概要：</h4></label>
                        <input type="text" name="brief" class="form-control" value="{{ old('brief') }}">
                    </div>
                    <div class="form-group">
                        <label for="body"><h4>正文：</h4></label>
                        <textarea id="mainbody" name="body" rows="12" class="form-control comment-editor" data-provide="markdown" placeholder="章节正文">{{ old('body') }}</textarea>
                        <button type="button" onclick="retrievecache('mainbody')" class="sosad-button-ghost grayout">恢复数据</button>
                        <button type="button" onclick="removespace('mainbody')" class="sosad-button-ghost grayout">清理段首空格</button>
                        <button href="#" type="button" class="pull-right sosad-button-ghost grayout">字数统计：<span id="word-count-mainbody">0</span></button>
                        <br>
                        <br>
                        <div class="">
                          <input type="checkbox" id="mainpost-indentation" name="indentation" {{ $book->indentation ? 'checked' : '' }}>
                          <label for="mainpost-indentation" class="input-helper input-helper--checkbox">段首缩进（自动空两格）？</label>&nbsp;
                          <br>
                          <input type="checkbox" name="markdown" id="markdown">
                          <label for="markdown" class="input-helper input-helper--checkbox">
                              使用Markdown语法？（建议：如果您对markdwon语法并不熟悉，请直接忽略该选项）
                          </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="annotation"><h4>备注：</h4></label>
                        <textarea id="mainannotation" name="annotation" data-provide="markdown" rows="5" class="form-control comment-editor" placeholder="作者有话说…">{{ old('annotation') }}</textarea>
                        <button type="button" onclick="retrievecache('mainannotation')" class="sosad-button-ghost grayout">恢复数据</button>
                        <button type="button" onclick="removespace('mainannotation')" class="sosad-button-ghost grayout">清理段首空格</button>
                        <button href="#" type="button" onclick="wordscount('mainannotation');return false;" class="pull-right sosad-button-ghost grayout">字数统计：<span id="word-count-mainannotation">0</span></button>
                    </div>
                    @if(!$book->thread->anonymous)
                    <div class="checkbox">
                      <input type="checkbox" name="sendstatus" id="sendstatus" checked>
                      <label for="sendstatus" class="input-helper input-helper--checkbox">
                          更新动态？
                      </label>
                    </div>
                    @endif
                    @if(!$book->thread->bianyuan)
                    <div class="checkbox">
                      <input type="checkbox" name="bianyuan" id="bianyuan">
                      <label for="bianyuan" class="input-helper input-helper--checkbox">
                          是否限制阅读章节？（非边缘限制文，但本章节含有性描写等敏感内容字段的，请自觉勾选此项，本章将只对注册用户开放，避免搜索引擎抓取。）
                      </label>
                    </div>
                    @endif
                    <button type="submit" class="sosad-button-thread width100">发布新章节</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
