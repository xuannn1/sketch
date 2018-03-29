@extends('layouts.default')
@section('title', '交作业')
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
               <button href="#" type="button" onclick="wordscount('mainbody');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
             </div>

             <div class="checkbox">
               <label><input type="checkbox" name="anonymous" onclick="document.getElementById('majia').style.display = 'block'">马甲？</label>
               <!-- <label><input type="checkbox" name="markdown">使用Markdown语法？</label> -->
               <div class="form-group text-right" id="majia" style="display:none">
                   <input type="text" name="majia" class="form-control" value="{{Auth::user()->majia ?:'匿名咸鱼'}}" placeholder="请输入不超过10字的马甲">
                   <label for="majia"><small>(马甲仅勾选“匿名”时有效)</small></label>
               </div>
             </div>

             <button type="submit" class="btn btn-primary">发布</button>
         </form>
       </div>
     </div>
   </div>

@stop
