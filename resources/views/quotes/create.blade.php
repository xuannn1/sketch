@extends('layouts.default')
@section('title', '贡献题头')

@section('content')
<div class="container-fluid">
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>贡献题头</h4>
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('quote.store') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="body"><h5>新题头：</h5></label>
                        <textarea name="body" id="markdowneditor" rows="5" class="form-control" placeholder="不丧不成活~">{{ old('body') }}</textarea>
                        <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-control addon-button">切换恢复数据</button>
                    </div>
                    <div class="grayout">
                        <h6>（每人每天只能提交一次题头。题头需要审核，题头审核通过的条件是“有品、有趣、有点丧”。不满足这个条件，过于私密，或可能引起他人不适的题头不会被通过。题头审核耗时较久，介意者慎投。提交时遇到“正文已存在”的意思是，相同内容的题头已经存在于数据库中。）</h6>
                    </div>
                    <div class="checkbox">
                        @if(Auth::user()->isAdmin())
                        <label><input type="checkbox" name="notsad" checked>（管理员专用）是否发布为使用提示？</label>
                        <br>
                        @endif
                        <label><input type="checkbox" name="is_anonymous" onclick="document.getElementById('majia').style.display = 'block'">马甲？</label>
                        <div class="form-group text-right" id="majia" style="display:none">
                            <input type="text" name="majia" class="form-control" value="{{Auth::user()->majia ?:'匿名咸鱼'}}" placeholder="请输入不超过10字的马甲">
                            <label for="majia"><small>(马甲仅勾选“匿名”时有效)</small></label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-lg btn-danger sosad-button">提交</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
