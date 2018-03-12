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
            <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-control addon-button">恢复数据</button>
         </div>
         <div class="checkbox">
           <label><input type="checkbox" name="anonymous" {{ $post->anonymous ? 'checked' : '' }}>马甲？</label>&nbsp;
           <label><input type="checkbox" name="markdown" {{ $post->markdown ? 'checked' : '' }}>使用Markdown语法？</label>
           <label><input type="checkbox" name="indentation" {{ $post->indentation ? 'checked' : '' }}>段首缩进（自动空两格）？</label>
           <div class="form-group text-right grayout" id="majia" style="display:block">
               <input type="text" name="majia" class="form-control" value="{{ $post->majia ?? '匿名咸鱼'}}" disabled>
               <label for="majia"><small>(马甲不可修改，只能脱马或批马)</small></label>
           </div>
         </div>
         <button type="submit" class="btn btn-primary sosad-button">确认修改</button>

      </form>
      <form method="POST" action="{{ route('post.destroy', $post->id) }}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
      <button type="submit" class="pull-right btn btn-danger sosad-button-control">删除帖子</button>
      </form>
   </div>
</div>

@stop
