@extends('layouts.default')
@section('title', '修改收藏单')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>修改收藏单</h1>
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('collections.collection_list_update', $collection_list->id) }}" name="create_collection_list">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="title"><h4>名称：</h4></label>
                        <input type="text" name="title" class="form-control" value="{{ $collection_list->title }}" placeholder="收藏单名称">
                    </div>
                    <div class="form-group grayout">
                        <label for="brief"><h4>收藏类型：</h4></label><br>
                        <label class="radio-inline"><input type="radio"  disabled  name="collection_type" value="1" {{ $collection_list->type=='1' ? 'checked':''}}>书籍</label>
                        <label class="radio-inline"><input type="radio" disabled  name="collection_type" value="2" {{ $collection_list->type=='2' ? 'checked':''}}>讨论贴</label>
                        <!-- <label class="radio-inline"><input type="radio" name="collection_type" value="3" {{ $collection_list->type=='3' ? 'checked':''}}>帖子</label> -->
                    </div>
                    <div class="form-group">
                        <label for="brief"><h4>简介：</h4></label>
                        <input type="text" name="brief" class="form-control" value="{{ $collection_list->brief }}" placeholder="请输入不超过50字的收藏单简介">
                    </div>
                    <div class="form-group">
                        <label for="body"><h4>描述（非必填）：</h4></label>
                        <textarea id="mainbody" name="body" rows="8" class="form-control" data-provide="markdown" placeholder="收藏单描述正文">{{ $collection_list->body }}</textarea>
                        <button type="button" onclick="retrievecache('mainbody')" class="sosad-button-control addon-button">恢复数据</button>
                        <button type="button" onclick="removespace('mainbody')" class="sosad-button-control addon-button">清理段首空格</button>
                        <button href="#" type="button" onclick="wordscount('mainbody');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
                        <br>
                    </div>
                    <button type="submit" class="btn btn-primary sosad-button">发布收藏单修改结果</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
