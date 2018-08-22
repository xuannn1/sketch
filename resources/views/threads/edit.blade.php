@extends('layouts.default')
@section('title', '编辑主题')
@section('content')
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
     <div class="panel panel-default">
        <?php $channel = $thread->channel ?>
       <div class="panel-heading">
         <h1>更新在&nbsp;<a href="{{ route('channel.show', $channel->id) }}">{{ $channel->channelname }}</a>&nbsp;板块的主题</h1>
       </div>
       <div class="panel-body">
         @include('shared.errors')

         <form method="POST" action="{{ route('thread.update', $thread->id) }}">
           {{ csrf_field() }}
            <?php $labels = $channel->labels()->get(); ?>
              <h4>请选择主题对应类型：</h4>
              @foreach ($labels as $index => $label)
                 <label class="radio-inline"><input type="radio" name="label" value="{{ $label->id }}" {{ $label->id == $thread->label_id ? 'checked' : '' }}>{{ $label->labelname }}</label>
              @endforeach
              <br>
              <br>
             <div class="form-group">
               <label for="title">标题：</label>
               <input type="text" name="title" class="form-control" value="{{ $thread->title }}">
             </div>

             <div class="form-group">
               <label for="brief">简介：</label>
               <input type="text" name="brief" class="form-control" value="{{ $thread->brief }}">
             </div>

             <div class="form-group">
               <label for="body">正文：</label>
               <textarea id="mainbody" name="body" data-provide="markdown" rows="20" class="form-control comment-editor" value="请输入至少20字的内容">{{ $thread->mainpost->body }}</textarea>
               <button type="button" onclick="retrievecache('mainbody')" class="sosad-button-ghost grayout">恢复数据</button>
               <button href="#" type="button" class="pull-right sosad-button-ghost grayout">字数统计：<span id="word-count-mainbody">0</span></button>
             </div>

             <div class="checkbox">
                 <input type="checkbox" name="anonymous" id="anonymous" {{ $thread->anonymous ? 'checked' : '' }}>
                 <label for="anonymous" class="input-helper input-helper--checkbox">
                     马甲？
                 </label>
                 <div class="form-group text-right grayout" id="majia" style="display:block">
                     <input type="text" name="majia" class="form-control" value="{{ $thread->majia }}" disabled>
                     <label for="majia"><small>(马甲不可修改，只能脱马或披马)</small></label>
                 </div>
             </div>
             <div class="checkbox">
                 <input type="checkbox" name="indentation" id="indentation" {{ $thread->mainpost->indentation ? 'checked' : '' }}>
                 <label for="indentation" class="input-helper input-helper--checkbox">
                     段首缩进（自动空两格）？
                 </label>
                 <input type="checkbox" name="public" id="public">
                 <label for="public" class="input-helper input-helper--checkbox" {{ $thread->public ? 'checked' : '' }}>
                   是否公开可见
                 </label>
                 <input type="checkbox" name="noreply" id="noreply">
                 <label for="noreply" class="input-helper input-helper--checkbox" {{ $thread->noreply ? 'checked' : '' }}>
                   是否禁止回帖
                 </label>
             </div>
             <button type="submit" class="sosad-button-thread width100">确认修改</button>
         </form>
       </div>
     </div>
   </div>

@stop
