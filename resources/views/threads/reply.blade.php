@extends('layouts.default')
@section('title', '回复主题'.$thread->title)

@section('content')
<div class="container-fluid">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      @include('shared.errors')

      <div class="panel-group">
         <div class="panel-header">
            <h4>回复主题<a href="{{ route('thread.show',$thread->id) }}">{{ $thread->title }}</a></h4>
         </div>
         <form id="replyToThread" action="{{ route('post.store', $thread) }}" method="POST">
            {{ csrf_field() }}
            <div class="hidden" id="reply_to_post">
               <span class="" id="reply_to_post_info"></span>
               <button type="button" class="label"><span class="glyphicon glyphicon glyphicon-remove" onclick="cancelreplytopost()"></span></button>
            </div>
            <input type="hidden" name="reply_to_post" id="reply_to_post_id" class="form-control" value="0"></input>
            <div class="form-group">
               <textarea name="body" rows="7" class="form-control" id="markdowneditor"  placeholder="评论十个字起哦～" value="{{ request()->input('body') }}"></textarea>
               <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-control addon-button">恢复数据</button>
               <button href="#" type="button" onclick="wordscount('markdowneditor');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
            </div>
            <div class="checkbox">
              <label><input type="checkbox" name="anonymous" onclick="document.getElementById('majiareplythread{{$thread->id}}').style.display = 'block'">马甲？</label>
              <div class="form-group text-right" id="majiareplythread{{$thread->id}}" style="display:none">
                  <input type="text" name="majia" class="form-control" value="{{Auth::user()->majia ?:'匿名咸鱼'}}" placeholder="请输入不超过10字的马甲">
                  <label for="majia"><small>(马甲仅勾选“匿名”时有效)</small></label>
              </div>
            </div>
            <button type="submit" name="store_button" value="Store" class="btn btn-danger sosad-button">回复</button>
            <label><input type="checkbox" name="markdown_editor" onclick="document.getElementById('markdowneditor').setAttribute("data-provide", "markdown")">Markdown语法？</label>
            <label><input type="checkbox" name="indentation" checked>段首缩进？</label>
            <button type="submit" name="full_editor_button" value="FullEditor" class="btn btn-danger sosad-button">高级编辑器</button>
            @if((Auth::id()==$thread->creator->id)&&($thread->book_id!=0))
               <button type="submit" name="new_chapter_button" value="NewChapter" class="btn btn-danger sosad-button">更新章节</button>
            @endif
         </form>
      </div>
   </div>
</div>
@stop
