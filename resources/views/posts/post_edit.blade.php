@extends('layouts.default')
@section('title', '编辑帖子')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @include('shared.errors')
        <div class="panel panel-default h4">
            <div class="panel-body">
                <form method="POST" action="{{ route('post.update', $post->id) }}">
                    {{ csrf_field() }}
                    @if($post->char_count>config('constants.longcomment_length'))
                    <div class="form-group">
                        <label for="title">帖子标题：</label>
                        <input type="text" class="form-control" name="title" value="{{ $post->title }}">
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="body">帖子正文：</label>
                        <textarea name="body" id="markdowneditor" data-provide="markdown" rows="12" class="form-control" placeholder="请输入至少20字的内容">{{ $post->body }}</textarea>
                        <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-control addon-button">切换恢复数据</button>
                        <button href="#" type="button" onclick="wordscount('markdowneditor');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" name="is_anonymous" {{ $post->is_anonymous ? 'checked' : '' }}>马甲？</label>&nbsp;
                        <label><input type="checkbox" name="use_indentation" {{ $post->use_indentation ? 'checked' : '' }}>段首缩进（自动空两格）？</label>
                        <label class="{{$post->reply_to_id>0?'':'hidden'}}"><input type="checkbox"  name="is_comment" {{$post->type==="comment"? 'checked':''}}>是点评？</label>
                        <div class="form-group text-right grayout" id="majia" style="display:block">
                            <input type="text" name="majia" class="form-control" value="{{ $post->majia ?? '匿名咸鱼'}}" disabled>
                            <label for="majia"><small>(马甲不可修改，只能脱马或批马)</small></label>
                        </div>
                        <div class="grayout">
                            <h6>提示：站内会自动去除段落间多余空行，请使用[br]换行。</h6>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-md btn-primary sosad-button">确认修改</button>
                </form>

                <form method="POST" action="{{ route('post.destroy', $post->id) }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="pull-right btn btn-md btn-danger sosad-button-control">删除帖子</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
