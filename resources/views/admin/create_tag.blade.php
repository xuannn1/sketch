@extends('layouts.default')
@section('title', '新建tag')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>新建tag</h4>
            </div>
            <div class="panel-body">
                @include('shared.errors')

                <form method="POST" action="{{ route('admin.store_tag') }}" name="store_tag">
                    {{ csrf_field() }}

                    <div class="">
                        <label class="radio-inline"><input type="radio" name="tongren_tag_group" value="1"  onclick="document.getElementById('fill_yuanzhu_tag').style.display = 'block'; document.getElementById('fill_CP_tag').style.display = 'none'">填同人原著</label>
                        <label class="radio-inline"><input type="radio" name="tongren_tag_group" value="2"  onclick="document.getElementById('fill_yuanzhu_tag').style.display = 'block'; document.getElementById('fill_CP_tag').style.display = 'block'">填同人CP</label>
                    </div>
                    <br>

                    <div class="">
                        <h4>同人-大类</h4>
                        @foreach ($labels_tongren as $label)
                        <label class="radio-inline"><input type="radio" name="label_id" value="{{ $label->id }}" onClick="show_only_this_label_tongren('{{$label->id}}');" {{ old('label_id')==$label->id ? 'checked':''}}>{{ $label->labelname }}</label>
                        @endforeach
                    </div>
                    <br>


                    <div id="fill_yuanzhu_tag" style="display:none">
                        <h4>同人原著</h4>
                        @foreach ($tags_tongren_yuanzhu as $tag)
                        <label class="radio-inline hidden label_{{$tag->label_id}} tongren_yuanzhu_tag"><input type="radio" name="tongren_yuanzhu_tag_id" value="{{ $tag->id }}" onClick="show_only_this_cp_tags('{{$tag->id}}')" {{ old('tongren_yuanzhu_tag_id')==(string)$tag->id ?'checked':''}}>{{ $tag->tagname }}（{{$tag->tag_explanation}}）</label>
                        @endforeach
                        <label class="radio-inline"><input type="radio" name="tongren_yuanzhu_tag_id" value="0" onClick="document.getElementById('fill_yuanzhu').style.display = 'block';" {{ old('tongren_yuanzhu_tag_id')==='0' ?'checked':''}}>其他原著</label>
                        <div id="fill_yuanzhu" style="display:{{ old('tongren_yuanzhu_tag_id')==='0'? 'block':'none' }}">
                            <label>填写原著作品全称:<input type="text" name="tongren_yuanzhu_full" class="form-control" placeholder="请输入完整的原著作品名称" value="{{ old('tongren_yuanzhu') }}"></label>
                            <label>填写原著作品简称（2-4字，中文）:<input type="text" name="tongren_yuanzhu" class="form-control" placeholder="请输入简称（2-4字，中文）" value="{{ old('tongren_yuanzhu') }}"></label>
                        </div>
                    </div>
                    <br>
                    <div id="fill_CP_tag" style="display:none">
                        <h4>同人CP</h4>
                        @foreach ($tags_tongren_cp as $tag)
                        <label class="radio-inline hidden tongren_yuanzhu_{{$tag->tag_belongs_to}} tongren_cp_tag"><input type="radio" name="tongren_CP_tag_id" value="{{ $tag->id }}" {{ old('tongren_CP_tag_id')==(string)$tag->id ?'checked':''}}>{{ $tag->tagname }}（{{$tag->tag_explanation}}）</label>
                        @endforeach
                        <label class="radio-inline"><input type="radio" name="tongren_CP_tag_id" value="0" onClick="document.getElementById('fill_CP').style.display = 'block'" {{ old('tongren_CP_tag_id')==='0' ?'checked':''}}>其他CP</label>
                        <div id="fill_CP" style="display:{{ old('tongren_CP_tag_id')==='0'? 'block':'none' }}">
                            <label>填写同人作品CP全称:<input type="text" name="tongren_cp_full" class="form-control" placeholder="请输入cp全称" value="{{ old('tongren_cp') }}"></label>
                            <label>填写同人作品CP简称（2字）:<input type="text" name="tongren_cp" class="form-control" placeholder="请输入cp简称（2字，中文）" value="{{ old('tongren_cp') }}"></label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-lg btn-danger sosad-button">发布</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
