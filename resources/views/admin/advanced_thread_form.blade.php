@extends('layouts.default')
@section('title', '主题贴高级管理')

@section('content')
<div class="container-fluid">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      @include('shared.errors')
      <div class="panel panel-default">
         <div class="panel-heading">
            <h4>高级管理</h4>
            <a href="{{route('thread.show','thread')}}">{{ $thread->title }}</a>
         </div>
         <div class="panel-body">
            <form action="{{ route('admin.threadmanagement',$thread->id)}}" method="POST">
            {{ csrf_field() }}
            <div class="admin-symbol">
               <h1>管理员权限专区</h1>
            </div>
            <div class="radio">
               <label><input type="radio" name="controlthread" value="1">{{ $thread->locked ? '解锁' : '锁帖' }}</label>
            </div>
            <div class="radio">
               <label><input type="radio" name="controlthread" value="2">{{ $thread->public ? '转为私密' : '转为公开' }}</label>
            </div>
            <div class="radio">
               <p class="lead admin-symbol"><label><input type="radio" name="controlthread" value="3">{{ $thread->deleted_at ? '恢复删除' : '删除帖子' }}</label></p>
            </div>

            <label><input type="radio" name="controlthread" value="4">转换板块</label>
            @foreach($channels as $channel)
            <div class="">
               <label class="radio-inline"><input type="radio" name="channel" value="{{$channel->id}}" onclick="document.getElementById('{{$channel->channelname}}').style.display = 'block'">{{$channel->channelname}}</label>
               <div id="{{$channel->channelname}}" style="display:none">
                  <p>请选择主题对应类型</p>
                 @foreach ($channel->labels as $index => $label)
                    <label class="radio-inline"><input type="radio" name="label" value="{{ $label->id }}">{{ $label->labelname }}</label>
                 @endforeach
               </div>
            </div>
            @endforeach
            <div class="form-group">
               <label for="reason"></label>
               <textarea name="reason"  rows="3" class="form-control" placeholder="请输入处理理由"></textarea>
            </div>
            <div class="">
               <button type="submit" class="btn btn-danger sosad-button btn-md admin-button">确定管理</button>
            </div>
            </form>
         </div>
      </div>
   </div>
</div>
@stop
