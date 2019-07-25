@extends('layouts.default')
@section('title', '编辑同人信息')
@section('content')

<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
    <!-- 首页／版块／导航 -->
    <div class="">
        <a type="btn btn-lg btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
        /
        <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
        /
        <a href="{{ route('thread.show_profile',$thread->id) }}">{{ $thread->title }}</a>/
        <a href="{{ route('books.edit', $thread->id) }}">编辑书籍</a>
        /编辑标签
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>编辑标签信息</h1>
            <h3>{{$thread->title}}</h3>
            <h4>{{$thread->brief}}</h4>
        </div>
        <div class="panel-body">
            @include('shared.errors')

            <form method="POST" action="{{ route('books.update_tongren', $thread->id) }}" name="edit_book_tongren">
                {{ csrf_field() }}
                @method('PATCH')

                <input name="channel_id" value="{{$thread->channel_id}}" class="hidden">

                <h4>&nbsp;&nbsp;1.1 请选择主题对应类型：</h4>
                @foreach ($tag_range['tongren_primary_tags'] as $tag)
                <label class="radio-inline"><input class="{{ $tag->channel_id==2?'tongren':'' }}"  type="radio" name="primary_tag" value="{{ $tag->id }}" onClick="show_only_children_yuanzhu('{{$tag->id}}');" {{ old('primary_tag')==$tag->id ? 'checked':''}}>{{ $tag->tag_name }}</label>
                @endforeach
                <br>
                <div class="tongren_yuanzhu_block">
                    <h4>&nbsp;&nbsp;1.2 从下列同人原著作品中，选择对应的同人原著作品简称</h4>
                    @foreach ($tag_range['tongren_yuanzhu_tags'] as $tag)
                    <label class="radio-inline {{ $selected_tags->keyBy('id')->get($tag->id)?'':'hidden' }} tongren tongren_yuanzhu {{$tag->parent_id>0?'parent'.$tag->parent_id:''}}"><input type="radio" name="tongren_yuanzhu_tag_id" value="{{ $tag->id }}" onClick="show_only_children_CP('{{$tag->id}}')" {{ $selected_tags->keyBy('id')->get($tag->id)?'checked':'' }}>{{ $tag->tag_name }}（{{$tag->tag_explanation}}）</label>
                    @endforeach
                    <label class="radio-inline"><input type="radio" name="tongren_yuanzhu_tag_id" value="0" onClick="document.getElementById('fill_yuanzhu').style.display = 'block';show_only_children_CP('0');" {{ old('tongren_yuanzhu_tag_id')=='0' ?'checked':''}}>其他原著</label>
                    <div id="fill_yuanzhu" style="display:{{ $tongren&&$tongren->tongren_yuanzhu? 'block':'none' }}">
                        <label>填写原著作品全称:<input type="text"
                            name="tongren_yuanzhu" class="form-control" placeholder="请输入完整原著作品名称" value="{{ $tongren? $tongren->tongren_yuanzhu:'' }}"></label>
                    </div>
                </div>

                <div class="tongren_CP_block">
                    <h5>&nbsp;&nbsp;1.3 从下列对应同人CP中，选择对应的CP简称</h5>
                    @foreach ($tag_range['tongren_CP_tags'] as $tag)
                    <label class="radio-inline {{ $selected_tags->keyBy('id')->get($tag->id)?'':'hidden' }} tongren tongren_CP {{$tag->parent_id>0?'parent'.$tag->parent_id:''}}"><input type="radio" name="tongren_CP_tag_id" value="{{ $tag->id }}" {{ $selected_tags->keyBy('id')->get($tag->id)?'checked':'' }}>{{ $tag->tag_name }}（{{$tag->tag_explanation}}）</label>
                    @endforeach

                    <label class="radio-inline"><input type="radio" name="tongren_CP_tag_id" value="0" onClick="document.getElementById('fill_CP').style.display = 'block'" {{ old('tongren_CP_tag_id')=='0' ?'checked':''}}>其他CP</label>
                    <div id="fill_CP" style="display:{{ $tongren&&$tongren->tongren_CP? 'block':'none' }}">
                        <label>填写同人作品CP全称:<input type="text" name="tongren_CP"
                            class="form-control" placeholder="请输入cp全称" value="{{ $tongren? $tongren->tongren_CP:'' }}"></label>
                    </div>
                </div>

                <button type="submit" class="btn btn-md btn-danger sosad-button">确认修改同人信息</button>
            </form>
        </div>
    </div>
</div>
@stop
