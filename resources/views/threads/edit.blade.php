@extends('layouts.default')
@section('title', '编辑主题')
@section('content')
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
     <div class="panel panel-default">
        <?php $channel = $thread->channel ?>
       <div class="panel-heading">
         <h4>更新在<a href="{{ route('channel.show', $channel->id) }}">{{ $channel->channelname }}</a>&nbsp;板块的主题</h4>
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
               <textarea id="mainbody" name="body" data-provide="markdown" rows="20" class="form-control" value="请输入至少20字的内容">{{ $thread->body }}</textarea>
               <button type="button" onclick="retrievecache('mainbody')" class="sosad-button-control addon-button">恢复数据</button>
               <button href="#" type="button" onclick="wordscount('mainbody');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
             </div>

             <div class="checkbox">
               <label><input type="checkbox" name="anonymous" {{ $thread->anonymous ? 'checked' : '' }}>马甲？</label>
               <div class="form-group text-right grayout" id="majia" style="display:block">
                   <input type="text" name="majia" class="form-control" value="{{ $thread->majia }}" disabled>
                   <label for="majia"><small>(马甲不可修改，只能脱马或批马)</small></label>
               </div>
             </div>
             <div class="checkbox">
                 <!-- <label><input type="checkbox" name="markdown" {{ $thread->mainpost->markdown ? 'checked' : '' }}>使用Markdown语法？</label> -->
                 <label><input type="checkbox" name="indentation" {{ $thread->mainpost->indentation ? 'checked' : '' }}>段首缩进（自动空两格）？</label>
                 <br>
                 <!-- <label><input type="checkbox" name="public" {{ $thread->public ? 'checked' : '' }}>是否公开可见</label> -->
                 <label><input type="checkbox" name="noreply" {{ $thread->noreply ? 'checked' : '' }}>是否禁止回帖</label>
                 </div>
             <button type="submit" class="btn btn-primary sosad-button">确认修改</button>
         </form>
       </div>
     </div>
   </div>

@stop
