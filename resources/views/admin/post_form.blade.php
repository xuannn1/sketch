@extends('layouts.default')
@section('title', '回帖管理')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @include('shared.errors')
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="admin-symbol">
                    管理帖子(请不要进行私人操作)
                </span>
                <h4><a href="{{route('post.show',$post->id)}}">{{ $post->title }}{{$post->brief}}</a></h4>
            </div>
            <div class="panel-body">
                <form action="{{ route('admin.postmanagement',$post->id)}}" method="POST">
                    {{ csrf_field() }}
                    <div class="radio">
                        <label><input type="radio" name="controlpost" value="10" onclick="document.getElementById('majiaforpost{{$post->id}}').style.display = 'block'">修改马甲？</label>
                        <div class="form-group text-right" id="majiaforpost{{$post->id}}" style="display:none">
                            <label><input type="radio" name="is_anonymous" value="1" {{ $post->is_anonymous ? 'checked':'' }}>披上马甲</label>
                            <label><input type="radio" name="is_anonymous" value="2" {{ $post->is_anonymous ? '':'checked' }}>揭下马甲</label>
                            <input type="text" name="majia" class="form-control" value="{{$post->majia ?:'匿名咸鱼'}}">
                        </div>
                    </div>

                    @if($post->is_bianyuan)
                    <div class="radio">
                        <label><input type="radio" name="controlpost" value="38">回帖转非边缘</label>
                    </div>
                    @else
                    <div class="radio">
                        <label><input type="radio" name="controlpost" value="37">回帖转边缘</label>
                    </div>
                    @endif

                    @if($post->fold_state===0)
                    <div class="radio">
                        <label><input type="radio" name="controlpost" value="11">折叠帖子</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="controlpost" value="7">删除帖子</label>
                    </div>

                    @else
                    <div class="radio">
                        <label><input type="radio" name="controlpost" value="12">取消折叠</label>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="reason"></label>
                        <textarea name="reason"  rows="3" class="form-control" placeholder="请输入处理理由，方便查看管理记录，如“涉及举报，标题简介违规”，“涉及举报，不友善”，“边限标记不合规”。"></textarea>
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
