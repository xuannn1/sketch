@extends('layouts.default')
@section('title', '编辑帖子')

@section('content')

<div class="panel panel-default">
    <div class="panel-body">
        @include('shared.errors')
        <form method="POST" action="{{ route('post.update', $post->id) }}">
            {{ csrf_field() }}
            @if ($post->long_comment)
            <div class="form-group">
                <label for="title">帖子标题：</label>
                <input type="text" class="form-control" name="title" value="{{ $post->title }}">
            </div>
            @endif
            <div class="form-group">
                <label for="body">帖子正文：</label>
                <textarea name="body" id="markdowneditor" data-provide="markdown" rows="12" class="form-control" placeholder="请输入至少20字的内容">{{ $post->body }}</textarea>
                <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-ghost grayout">恢复数据</button>
                <button type="button" onclick="removespace('markdowneditor')" class="sosad-button-ghost grayout">清理段首空格</button>
                <button href="#" type="button" class="pull-right sosad-button-ghost grayout">字数统计：<span class="word-count">0</span></button>
            </div>
            <div class="checkbox">
              <div class="margin5">
                <input type="checkbox" name="anonymous" id="anonymous" {{ $post->anonymous ? 'checked' : '' }}>
                <label for="anonymous" class="input-helper input-helper--checkbox">
                  马甲？
                </label>&nbsp;

                <input type="checkbox" id="indentation" name="indentation" {{ $post->indentation ? 'checked' : '' }}>
                <label for="indentation" class="input-helper input-helper--checkbox">段首缩进（自动空两格）？</label>&nbsp;

                <input type="checkbox" id="as_longcomment" name="as_longcomment" {{ $post->as_longcomment ? 'checked' : '' }}>
                <label for="as_longcomment" class="input-helper input-helper--checkbox">是否允许展示为长评？</label>
              </div>
                <div class="form-group text-right grayout" id="majia" style="display:block">
                    <input type="text" name="majia" class="form-control" value="{{ $post->majia ?? '匿名咸鱼'}}" disabled>
                    <label for="majia"><small>(马甲不可修改，只能脱马或披马)</small></label>
                </div>
                <div class="">
                    <h6>提示：站内会自动去除段落间多余空行，请使用<code>[br]</code>换行。</h6>
                </div>
            </div>
            <div class="">
              <a href="#" class="sosad-button-thread sosad-button-danger" data-toggle="modal" data-target="#delete-chapter">删除帖子</a>
              <button type="submit" class="sosad-button-thread">确认修改</button>
            </div>

        </form>

    </div>
</div>

<div class="modal fade" id="delete-chapter" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('post.destroy', $post->id) }}">
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
