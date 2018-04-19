@extends('layouts.default')
@section('title', '写新文章')
@section('content')
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>写新文章</h1>
            </div>
            <div class="panel-body">
                @include('shared.errors')

                <form method="POST" action="{{ route('book.store') }}" name="create_book">
                    {{ csrf_field() }}
                    <div>
                        <h6>（发文前请阅读：<a href="http://sosad.fun/threads/136">《版规的详细说明（草案）》</a>。关于网站使用的常规问题，可以查看如下页面：<a href="{{ route('about') }}">《关于本站》</a>，<a href="{{ route('help') }}">《使用帮助》</a>。除文章原创性之外，其他内容均可修改。感谢发文！）</h6>
                        <h4>1. 请选择文章原创性</h4>
                        <label class="radio-inline"><input type="radio" name="channel_id" value="1"  onclick="document.getElementById('yuanchuang').style.display = 'block'; document.getElementById('tongren').style.display = 'none';uncheckAll('tongrentags');document.getElementById('tongrentags').style.display = 'none'" {{ old('channel_id')=='1'?'checked':''}}>原创</label>
                        <label class="radio-inline"><input type="radio" name="channel_id" value="2"  onclick="document.getElementById('tongren').style.display = 'block'; document.getElementById('yuanchuang').style.display = 'none';document.getElementById('tongrentags').style.display = 'block'"{{ old('channel_id')=='2'?'checked':''}}>同人</label>
                    </div>

                    <div id="yuanchuang" style="display:{{ old('channel_id')=='1'?'block':'none'}}">
                        <h4>  1.1 请选择主题对应类型：</h4>
                        @foreach ($labels_yuanchuang as $label)
                            <label class="radio-inline"><input type="radio" name="label_id" value="{{ $label->id }}" {{ old('label_id')==$label->id ? 'checked':''}}>{{ $label->labelname }}</label>
                        @endforeach
                    </div>

                    <div id="tongren" style="display:{{ old('channel_id')=='2'?'block':'none'}}">
                        <h4>&nbsp;&nbsp;1.1 请选择主题对应类型：</h4>
                        @foreach ($labels_tongren as $label)
                            <label class="radio-inline"><input type="radio" name="label_id" value="{{ $label->id }}" onClick="show_only_this_label_tongren('{{$label->id}}');" {{ old('label_id')==$label->id ? 'checked':''}}>{{ $label->labelname }}</label>
                        @endforeach
                        <br>
                        <div id="tongren_yuanzhu">
                            <h4>&nbsp;&nbsp;1.2 从下列同人原著作品中，选择对应的同人原著作品简称</h4>
                            @foreach ($tags_tongren_yuanzhu as $tag)
                                <label class="radio-inline hidden label_{{$tag->label_id}} tongren_yuanzhu_tag"><input type="radio" name="tongren_yuanzhu_tag_id" value="{{ $tag->id }}" onClick="show_only_this_cp_tags('{{$tag->id}}')" {{ old('tongren_yuanzhu_tag_id')==(string)$tag->id ?'checked':''}}>{{ $tag->tagname }}（{{$tag->tag_explanation}}）</label>
                            @endforeach
                            <label class="radio-inline"><input type="radio" name="tongren_yuanzhu_tag_id" value="0" onClick="document.getElementById('fill_yuanzhu').style.display = 'block';show_only_this_cp_tags('0');" {{ old('tongren_yuanzhu_tag_id')=='0' ?'checked':''}}>其他原著</label>
                            <div id="fill_yuanzhu" style="display:{{ old('tongren_yuanzhu_tag_id')=='0'? 'block':'none' }}">
                                <label>填写原著作品全称:<input type="text"
                                    name="tongren_yuanzhu" class="form-control" placeholder="请输入完整原著作品名称" value="{{ old('tongren_yuanzhu') }}"></label>
                            </div>
                        </div>

                        <div id="tongren_cp">
                            <h5>&nbsp;&nbsp;1.3 从下列对应同人CP中，选择对应的CP简称</h5>
                            @foreach ($tags_tongren_cp as $tag)
                                <label class="radio-inline hidden tongren_yuanzhu_{{$tag->tag_belongs_to}} tongren_cp_tag"><input type="radio" name="tongren_CP_tag_id" value="{{ $tag->id }}" {{ old('tongren_CP_tag_id')==(string)$tag->id ?'checked':''}}>{{ $tag->tagname }}（{{$tag->tag_explanation}}）</label>
                            @endforeach
                            <label class="radio-inline"><input type="radio" name="tongren_CP_tag_id" value="0" onClick="document.getElementById('fill_CP').style.display = 'block'" {{ old('tongren_CP_tag_id')=='0' ?'checked':''}}>其他CP</label>
                            <div id="fill_CP" style="display:{{ old('tongren_CP_tag_id')=='0'? 'block':'none' }}">
                                <label>填写同人作品CP全称:<input type="text" name="tongren_cp"
                                    class="form-control" placeholder="请输入cp全称" value="{{ old('tongren_cp') }}"></label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4>2. 请选择连载进度</h4>
                        <label class="radio-inline"><input type="radio" name="book_status" value="1" {{ old('book_status')=='1'?'checked':''}}>连载</label>
                        <label class="radio-inline"><input type="radio" name="book_status" value="2" {{ old('book_status')=='2'?'checked':''}}>完结</label>
                        <label class="radio-inline"><input type="radio" name="book_status" value="3" {{ old('book_status')=='3'?'checked':''}}>暂停</label>
                    </div>

                    <div>
                        <h4>3. 请选择文章篇幅</h4>
                        <label class="radio-inline"><input type="radio" name="book_length" value="1" {{ old('book_length')=='1'?'checked':''}}>短篇</label>
                        <label class="radio-inline"><input type="radio" name="book_length" value="2" {{ old('book_length')=='2'?'checked':''}}>中篇</label>
                        <label class="radio-inline"><input type="radio" name="book_length" value="3" {{ old('book_length')=='3'?'checked':''}}>长篇</label>
                        <br>
                    </div>

                    <div>
                        <h4>4. 请选择文章性向</h4>
                        <label class="radio-inline"><input type="radio" name="sexual_orientation" value="1" {{ old('sexual_orientation')=='1'?'checked':''}}>BL</label>
                        <label class="radio-inline"><input type="radio" name="sexual_orientation" value="2" {{ old('sexual_orientation')=='2'?'checked':''}}>GL</label>
                        <label class="radio-inline"><input type="radio" name="sexual_orientation" value="3" {{ old('sexual_orientation')=='3'?'checked':''}}>BG</label>
                        <label class="radio-inline"><input type="radio" name="sexual_orientation" value="4" {{ old('sexual_orientation')=='4'?'checked':''}}>GB</label>
                        <label class="radio-inline"><input type="radio" name="sexual_orientation" value="5" {{ old('sexual_orientation')=='5'?'checked':''}}>混合性向</label>
                        <label class="radio-inline"><input type="radio" name="sexual_orientation" value="6" {{ old('sexual_orientation')=='6'?'checked':''}}>无CP</label>
                        <label class="radio-inline"><input type="radio" name="sexual_orientation" value="7" {{ old('sexual_orientation')=='7'?'checked':''}}>其他性向</label>
                        <br>
                    </div>

                    <div>
                        <label for="bianyuan"><h4>5. 是否边缘敏感题材？</h4></label>
                        <div id="bianyuan" class="h6">
                          文章含肉超过20%，或开头具有较明显的性行为描写，或题材包含人兽、触手、父子、乱伦、生子、产乳、abo、冰恋、军政、黑道、性转……等边缘敏感题材，或估计不适合未成年人观看的，请勾选此项。勾选后，本文将不受搜索引擎直接抓取，不被未注册游客观看。
                        </div>
                        <div>
                            <label class="radio-inline"><input type="radio" name="bianyuan" value="0" onclick="uncheckAll('bianyuantags');document.getElementById('bianyuantags').style.display = 'none'" {{ old('bianyuan')=='0'?'checked':''}}>非边缘</label>
                            <label class="radio-inline"><input type="radio" name="bianyuan" value="1" onclick="document.getElementById('bianyuantags').style.display = 'block'" {{ old('bianyuan')=='1'?'checked':''}}>边缘</label>
                        </div>
                    </div>

                    <div id="alltags">
                        <h4>6. 请从以下标签中选择不多于三个标签：</h4>
                        @foreach ($tags_feibianyuan as $tag)
                            <input type="checkbox" class="tags" name="tags[]" value="{{ $tag->id }}" {{ (is_array(old('tags')))&&(in_array($tag->id, old('tags')))? 'checked':'' }}>{{ $tag->tagname }}
                        @endforeach
                        <br>
                        <div id="bianyuantags" style="display: {{ old('bianyuan')=='1'? 'block':'none'}}">
                        @foreach ($tags_bianyuan as $tag)
                           <input type="checkbox" class="tags" name="tags[]" value="{{ $tag->id }}" {{ (is_array(old('tags')))&&(in_array($tag->id, old('tags')))? 'checked':'' }}>{{ $tag->tagname }}
                        @endforeach
                        </div>
                        <div id="tongrentags" style="display: {{ old('channel_id')=='1'? 'none':'block'}}">
                        @foreach ($tags_tongren as $tag)
                           <input type="checkbox" class="tags" name="tags[]" value="{{ $tag->id }}" {{ (is_array(old('tags')))&&(in_array($tag->id, old('tags')))? 'checked':'' }}>{{ $tag->tagname }}
                        @endforeach
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="title"><h4>7. 标题：</h4></label>
                        <div id="biaotiguiding" class="h6">
                            标题请规范，尊重汉语语法规则，避免火星文、乱用符号标点等。文章类型、CP、背景、版本相关信息请在简介，文案 ，标签 ，备注等处展示，不要放入标题。
                        </div>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="请输入不超过20字的标题">
                    </div>

                    <div class="form-group">
                        <label for="brief"><h4>8. 简介：</h4></label>
                        <input type="text" name="brief" class="form-control" value="{{ old('brief') }}" placeholder="请输入不超过25字的简介">
                    </div>

                    <div class="checkbox">
                      <label><input type="checkbox" name="anonymous" onclick="document.getElementById('majia').style.display = 'block'" {{ old('anonymous')? 'checked':'' }}>马甲？</label>
                      <div class="form-group text-right" id="majia" style="display:{{ old('anonymous')? 'block':'none' }}">
                          <input type="text" name="majia" class="form-control" value="{{Auth::user()->majia ?:'匿名咸鱼'}}">
                          <label for="majia"><small>(请输入不超过10字的马甲。马甲仅勾选“匿名”时有效，可以更改披马与否，但马甲名称不能再修改)</small></label>
                      </div>
                    </div>

                    <div class="text-center">
                        <a data-toggle="collapse" data-target="#more_options" class="h5">点击展开更多设置（可日后再填）</a>
                    </div>

                    <div id="more_options" class="collapse">
                        <div class="form-group">
                            <label for="wenan"><h4>9. 文案（可选）：</h4></label>
                            <div id="wenan" class="h6">
                                文案不是正文，文案属于对文章的简单介绍。文案采用“居中排列”的板式，而不是“向左对齐”。如果在这里发布正文，阅读效果不好。正文请在发布文章后，于文案下选择“新建章节”来建立。
                            </div>
                            <textarea name="wenan" id="markdowneditor" data-provide="markdown" rows="5" class="form-control">{{ old('wenan') }}</textarea>
                            <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-control addon-button">恢复数据</button>
                            <button type="button" onclick="removespace('markdowneditor')" class="sosad-button-control addon-button">清理段首空格</button>
                            <button href="#" type="button" onclick="wordscount('markdowneditor');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
                        </div>

                        <div class="checkbox">
                            <!-- <label><input type="checkbox" name="markdown" >使用Markdown语法？</label>&nbsp; -->
                            <label><input type="checkbox" name="indentation" checked>段首缩进（自动空两格）？</label>&nbsp;
                            <br>
                            <label><input type="checkbox" name="public" checked>是否公开可见？</label>&nbsp;
                            <label><input type="checkbox" name="noreply">是否禁止回帖？</label>&nbsp;
                            <br>
                            <label><input type="checkbox" name="download_as_thread" checked>开放书评下载？</label>&nbsp;
                            <label><input type="checkbox" name="download_as_book" >开放书籍下载？</label>
                          </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-danger sosad-button">发布</button>
                </form>
            </div>
        </div>
    </div>
@stop
