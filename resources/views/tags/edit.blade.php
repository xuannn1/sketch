@extends('layouts.default')
@section('title', '标签修改')
@section('content')
<div class="container">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <a href="{{route('tag.index')}}">全站所有标签列表</a>
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>标签修改</h1>
                </div>
                <div class="panel-body">
                    @include('shared.errors')
                    <form method="POST" action="{{ route('tag.update', $tag->id) }}">
                        {{ csrf_field() }}
                        @method('PATCH')
                        <div class="form-group">
                            <label for="tag_name">标签名称：</label>
                            <input name="tag_name" type="text" class="form-control" value="{{ $tag->tag_name }}">
                        </div>

                        <div class="form-group">
                            <label for="tag_name">标签解释：</label>
                            <textarea name="tag_explanation" rows="3" class="form-control">{{ $tag->tag_explanation }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="tag_tyoe">标签类型：</label>
                            <input type="text" class="form-control" value="{{ $tag->tag_type }}">
                            <div class="text-center">
                                <a type="button" data-toggle="collapse" data-target="#tag_type_list" style="cursor: pointer;" class="font-6">可选标签类型列表（必须从表内选择）</a>
                            </div>

                            <div class="collapse grayout font-6" id="tag_type_list">
                                @foreach(config('tag.types') as $key=>$value)
                                {{$value}},&nbsp;
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="is_bianyuan">是否边缘限制</label>
                            <div>
                                <label class="radio-inline"><input type="radio" name="is_bianyuan" value="isnot" {{$tag->is_bianyuan?'':'checked'}}>非边缘限制标签</label>
                                <label class="radio-inline"><input type="radio" name="is_bianyuan" value="is" {{$tag->is_bianyuan?'checked':''}}>是边缘限制标签</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="is_bianyuan">是否大类</label>
                            <div>
                                <label class="radio-inline"><input type="radio" name="is_primary" value="isnot" {{$tag->is_primary?'':'checked'}}>非大类标签</label>
                                <label class="radio-inline"><input type="radio" name="is_primary" value="is" {{$tag->is_primary?'checked':''}}>是大类标签</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><input type="text" style="width: 80px" name="channel_id" value="{{$tag->channel_id}}">从属板块ID</label>
                        </div>

                        <div class="form-group">
                            <label><input type="text" style="width: 80px" name="parent_id" value="{{$tag->parent_id}}">从属上级标签ID</label>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-md btn-danger sosad-button">确认修改</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
