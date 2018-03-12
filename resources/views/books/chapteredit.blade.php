@extends('layouts.default')
@section('title', $thread->title.'-'.$chapter->title)
@section('content')
<div class="container-fluid">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
   @include('shared.errors')
   <div class="panel-group">
      <h5><a type="btn btn-primary" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>/<a href="{{ route('label.show', $thread->label) }}">{{ $thread->label->labelname }}</a>/<a href="{{ route('book.show', $book) }}">{{ $thread->title }}</a></h5>
      <h4>编辑章节</h4>
      <form method="POST" action="{{ route('book.updatechapter', $chapter) }}">
         {{ csrf_field() }}
            <div class="form-group">
               <label for="title">章节名称：</label>
               <input type="text" name="title" class="form-control" value="{{ $chapter->title }}">
            </div>
            <div class="form-group">
               <label for="brief">概要：</label>
               <input type="text" name="brief" class="form-control" value="{{ $mainpost->title }}">
            </div>
            <div class="form-group">
               <label for="body">正文：</label>
               <textarea name="body" rows="12" class="form-control" id="mainbody" data-provide="markdown" placeholder="正文">{{ $mainpost->body }}</textarea>
               <button type="button" onclick="retrievecache('mainbody')" class="sosad-button-control addon-button">恢复数据</button>
               <button href="#" type="button" onclick="wordscount('mainbody');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
               <label><input type="checkbox" name="markdown" {{ $mainpost->markdown? 'checked':''}}>使用Markdown语法？</label>
               <label><input type="checkbox" name="indentation" {{ $mainpost->indentation ? 'checked' : '' }}>段首缩进（自动空两格）？</label>

            </div>
            <div class="form-group">
               <label for="annotation">备注：</label>
               <textarea id="mainannotation" name="annotation" data-provide="markdown" rows="5" class="form-control" placeholder="作者有话说">{{ $chapter->annotation }}</textarea>
               <button type="button" onclick="retrievecache('mainannotation')" class="sosad-button-control addon-button">恢复数据</button>
               <button href="#" type="button" onclick="wordscount('mainannotation');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
            </div>
         </div>
         <button type="submit" class="btn btn-primary sosad-button">确认修改</button>
      </form>
      <form method="POST" action="{{ route('post.destroy', $chapter->post_id) }}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
      <button type="submit" class="pull-right btn btn-danger sosad-button-control">删除帖子</button>
      </form>
   </div>
   </div>
</div>
@stop
