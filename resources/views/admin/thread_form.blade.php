@extends('layouts.default')
@section('title', '主题贴高级管理')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @include('shared.errors')
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>高级管理</h4>
                <a href="{{route('thread.show',$thread->id)}}">{{ $thread->title }}</a>
            </div>
            <div class="panel-body">
                <form action="{{ route('admin.threadmanagement',$thread->id)}}" method="POST">
                    {{ csrf_field() }}
                    <div class="admin-symbol">
                        <h1>管理员权限专区</h1>
                    </div>
                    @if(!$thread->is_locked)
                    <div class="radio">
                        <label><input type="radio" name="controlthread" value="1">锁帖</label>
                    </div>
                    @else
                    <div class="radio">
                        <label><input type="radio" name="controlthread" value="2">解锁</label>
                    </div>
                    @endif

                    @if($thread->is_public)
                    <div class="radio">
                        <label><input type="radio" name="controlthread" value="3">转私密</label>
                    </div>
                    @else
                    <div class="radio">
                        <label><input type="radio" name="controlthread" value="4">转公开</label>
                    </div>
                    @endif

                    @if(!$thread->is_bianyuan)
                    <div class="radio">
                        <label><input type="radio" name="controlthread" value="15">转边缘</label>
                    </div>
                    @else
                    <div class="radio">
                        <label><input type="radio" name="controlthread" value="16">转非边缘</label>
                    </div>
                    @endif

                    @if(!$thread->deleted_at)
                    <div class="radio">
                        <label><input type="radio" name="controlthread" value="5">删除主题</label>
                    </div>
                    @else
                    <div class="radio">
                        <label><input type="radio" name="controlthread" value="6">恢复主题</label>
                    </div>
                    @endif

                    <div class="radio">
                        <label><input type="radio" name="controlthread" value="40">帖子上浮（顶帖）</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="controlthread" value="41">帖子下沉（踩贴）</label>
                    </div>

                    @if(!$thread->recommended)
                    <div class="radio">
                        <label><input type="radio" name="controlthread" value="42">添加推荐</label>
                    </div>
                    @else
                    <div class="radio">
                        <label><input type="radio" name="controlthread" value="43">取消推荐</label>
                    </div>
                    @endif

                    @if($thread->jinghua < Carbon\Carbon::now())
                    <div class="radio">
                        <label><input type="radio" name="controlthread" value="44">添加精华</label>
                        <label><input type="text" style="width: 40px" name="jinghua-days" value="0">精华时间（天）</label>
                    </div>
                    @else
                    <div class="radio">
                        <label><input type="radio" name="controlthread" value="45">取消精华</label>
                    </div>
                    @endif

                    <label><input type="radio" name="controlthread" value="9">转换板块（注意，如果点选了下面其他选项，记得回头把这个选一下）</label>
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
                        <textarea name="reason"  rows="3" class="form-control" placeholder="请输入处理理由(理由将会公示)，以及处理参数（如禁言时间，精华时间）。"></textarea>
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
