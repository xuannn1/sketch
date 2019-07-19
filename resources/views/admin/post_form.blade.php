@extends('layouts.default')
@section('title', '回帖管理')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @include('shared.errors')
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2>管理帖子</h2>
                <h3><a href="{{route('post.show',$post->id)}}">{{ $post->title }}{{$post->brief}}</a></h3>
            </div>
            <div class="panel-body">

                <form action="{{ route('admin.postmanagement',$post->id)}}" method="POST">
                    {{ csrf_field() }}
                    <div class="admin-symbol">
                        <h2>管理员权限专区：警告！请勿进行私人用户操作</h2>
                    </div>
                    <div>

                    </div>
                    <div class="radio">
                        <label><input type="radio" name="controlpost" value="7">{{ $post->deleted_at ? '解除删除' : '删除帖子' }}</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="controlpost" value="10" onclick="document.getElementById('majiaforpost{{$post->id}}').style.display = 'block'">修改马甲？</label>
                        <div class="form-group text-right" id="majiaforpost{{$post->id}}" style="display:none">
                            <label><input type="radio" name="is_anonymous" value="1" {{ $post->is_anonymous ? 'checked':'' }}>披上马甲</label>
                            <label><input type="radio" name="is_anonymous" value="2" {{ $post->is_anonymous ? '':'checked' }}>揭下马甲</label>
                            <input type="text" name="majia" class="form-control" value="{{$post->majia ?:'匿名咸鱼'}}">
                        </div>
                    </div>

                    @if(!$post->fold_state)
                    <div class="radio">
                        <label><input type="radio" name="controlpost" value="11">折叠帖子</label>
                    </div>
                    @else
                    <div class="radio">
                        <label><input type="radio" name="controlpost" value="12">取消折叠</label>
                    </div>
                    @endif

                    <div class="radio">
                        <label><input type="radio" name="controlpost" value="30">水贴套餐（积分等级清零，回帖折叠，发帖人禁言一天）</label>
                    </div>

                    <div class="radio">
                        <label><input type="radio" name="controlpost" value="31">车轱辘套餐（回帖折叠，发帖人禁言累计增加一天）</label>
                    </div>

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
