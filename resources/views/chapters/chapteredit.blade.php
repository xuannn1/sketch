@extends('layouts.default')
@section('title', $thread->title.'-'.$chapter->title )
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @include('shared.errors')
        <div class="site-map">
          <a href="{{ route('home') }}">
            <span><i class="fa fa-home"></i>&nbsp;首页</span></a>
            /
            <a href="{{ route('channel.show', ['channel'=>$thread->channel_id,'label'=>$thread->label_id]) }}">{{ $thread->label->labelname }}</a>
            /
            <a href="{{ route('book.show', $book) }}">{{ $thread->title }}</a>
          </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>编辑章节</h1>
            </div>
            <div class="panel-body">
              <form method="POST" action="{{ route('book.updatechapter', $chapter) }}">
                {{ csrf_field() }}
                <div class="form-group">
                  <label for="title">章节名称：</label>
                  <div id="biaotiguiding" class="h6">
                        <span style="color:#d66666">章节名中不得具有性描写、性暗示，不得使用直白的脏话、黄暴词和明显涉及边缘的词汇。<span>
                    </div>
                  <input type="text" name="title" class="form-control" value="{{ $chapter->title }}">
                </div>
                <div class="form-group">
                  <label for="brief">概要：</label>
                  <input type="text" name="brief" class="form-control" value="{{ $mainpost->title }}">
                </div>
                <div class="form-group">
                  <label for="body">正文：</label>
                  <textarea name="body" rows="12" class="form-control comment-editor" id="mainbody" data-provide="markdown" placeholder="正文">{{ $mainpost->body }}</textarea>
                  <button type="button" onclick="retrievecache('mainbody')" class="sosad-button-ghost grayout">恢复数据</button>
                  <button type="button" onclick="removespace('mainbody')" class="sosad-button-ghost grayout">清理段首空格</button>
                  <button href="#" type="button" onclick="wordscount('mainbody');return false;" class="pull-right sosad-button-ghost grayout">字数统计：<span id="word-count-mainbody">0</span></button>
                  <br>
                  <br>
                  <div class="">
                    <input type="checkbox" id="mainpost-indentation" name="indentation" {{ $mainpost->indentation ? 'checked' : '' }}>
                    <label for="mainpost-indentation" class="input-helper input-helper--checkbox">段首缩进（自动空两格）？</label>&nbsp;
                    <br>
                    <input type="checkbox" name="markdown" id="markdown" {{ $mainpost->markdown? 'checked':''}}>
                    <label for="markdown" class="input-helper input-helper--checkbox">
                        使用Markdown语法？（建议：如果您对markdwon语法并不熟悉，请直接忽略该选项）
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="annotation">备注：</label>
                  <textarea id="mainannotation" name="annotation" data-provide="markdown" rows="5" class="form-control comment-editor" placeholder="作者有话说">{{ $chapter->annotation }}</textarea>
                  <button type="button" onclick="retrievecache('mainannotation')" class="sosad-button-ghost grayout">恢复数据</button>
                  <button type="button" onclick="removespace('mainannotation')" class="sosad-button-ghost grayout">清理段首空格</button>
                  <button href="#" type="button" class="pull-right sosad-button-ghost grayout">字数统计：<span id="word-count-mainannotation">0</span></button>
                </div>
                @if(!$thread->bianyuan)
                <div class="checkbox">
                  <input type="checkbox" name="bianyuan" id="bianyuan" {{ $mainpost->bianyuan ? 'checked' : '' }}>
                  <label for="bianyuan" class="input-helper input-helper--checkbox">
                      是否限制阅读章节？（非边缘限制文，但本章节含有性描写等敏感内容字段的，请自觉勾选此项，本章将只对注册用户开放，避免搜索引擎抓取。）
                  </label>
                </div>
                @endif
                <div class="">
                  <h6>提示：站内会自动去除段落间多余空行，请使用<code>[br]</code>换行。</h6>
                </div>
                <div class="">
                  <a href="#" class="sosad-button-thread sosad-button-danger" data-toggle="modal" data-target="#delete-chapter">删除帖子</a>
                  <button type="submit" class="sosad-button-thread">确认修改</button>
                </div>
              </form>
            </div>
          </div>
    </div>
</div>

<div class="modal fade" id="delete-chapter" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('post.destroy', $chapter->post_id) }}">
          {{ csrf_field() }}
          {{ method_field('DELETE') }}
          <button class="sosad-button-post sosad-button-danger width100" data-toggle="modal" data-target="delete-chapter">
            <i class="far fa-trash-alt"></i>
            确认删除
          </button>
        </form>
   </div>
</div>

@stop
