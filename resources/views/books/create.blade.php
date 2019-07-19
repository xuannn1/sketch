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

            <form method="POST" action="{{ route('books.store') }}" name="create_book">
                {{ csrf_field() }}
                <div>
                    <h5><span style="color:#d66666">发文发帖前请务必阅读：<a href="http://sosad.fun/threads/136">《<u>版规的详细说明</u>》</a><br>关于网站使用的常规问题：<a href="{{ route('help') }}">《<u>使用帮助</u>》</a></span></h5>
                    <br>
                    <h4>1. 请选择文章原创性</h4>
                    <label class="radio-inline"><input type="radio" name="channel_id" value="1"  onclick="yuanchuang_checked()" {{ old('channel_id')=='1'?'checked':''}}>原创</label>
                    <label class="radio-inline"><input type="radio" name="channel_id" value="2"  onclick="tongren_checked()"{{ old('channel_id')=='2'?'checked':''}}>同人</label>
                </div>

                <div class="yuanchuang_block {{ old('channel_id')=='1'? '':'hidden'}}">
                    <h4>  1.1 请选择主题对应类型：</h4>
                    @foreach ($tag_range['yuanchuang_primary_tags'] as $tag)
                    <label class="radio-inline"><input class="{{ $tag->channel_id==1?'yuanchuang':'' }}" type="radio" name="primary_tag" value="{{ $tag->id }}" {{ old('primary_tag')==$tag->id ? 'checked':''}}>{{ $tag->tag_name }}</label>
                    @endforeach
                </div>

                <div class="tongren_block {{ old('channel_id')=='2'? '':'hidden'}}">
                    <h4>&nbsp;&nbsp;1.1 请选择主题对应类型：</h4>
                    @foreach ($tag_range['tongren_primary_tags'] as $tag)
                    <label class="radio-inline"><input class="{{ $tag->channel_id==2?'tongren':'' }}"  type="radio" name="primary_tag" value="{{ $tag->id }}" onClick="show_only_children_yuanzhu('{{$tag->id}}');" {{ old('primary_tag')==$tag->id ? 'checked':''}}>{{ $tag->tag_name }}</label>
                    @endforeach
                    <br>
                    <div class="tongren_yuanzhu_block">
                        <h4>&nbsp;&nbsp;1.2 从下列同人原著作品中，选择对应的同人原著作品简称</h4>
                        @foreach ($tag_range['tongren_yuanzhu_tags'] as $tag)
                        <label class="radio-inline hidden tongren tongren_yuanzhu {{$tag->parent_id>0?'parent'.$tag->parent_id:''}}"><input type="radio" name="tongren_yuanzhu_tag_id" value="{{ $tag->id }}" onClick="show_only_children_CP('{{$tag->id}}')" {{ old('tongren_yuanzhu_tag_id')==$tag->id ?'checked':''}}>{{ $tag->tag_name }}（{{$tag->tag_explanation}}）</label>
                        @endforeach
                        <label class="radio-inline"><input type="radio" name="tongren_yuanzhu_tag_id" value="0" onClick="document.getElementById('fill_yuanzhu').style.display = 'block';show_only_children_CP('0');" {{ old('tongren_yuanzhu_tag_id')=='0' ?'checked':''}}>其他原著</label>
                        <div id="fill_yuanzhu" style="display:{{ old('tongren_yuanzhu_tag_id')=='0'? 'block':'none' }}">
                            <label>填写原著作品全称:<input type="text"
                                name="tongren_yuanzhu" class="form-control" placeholder="请输入完整原著作品名称" value="{{ old('tongren_yuanzhu') }}"></label>
                        </div>
                    </div>

                    <div class="tongren_CP_block">
                        <h5>&nbsp;&nbsp;1.3 从下列对应同人CP中，选择对应的CP简称</h5>
                        @foreach ($tag_range['tongren_CP_tags'] as $tag)
                        <label class="radio-inline hidden tongren tongren_CP {{$tag->parent_id>0?'parent'.$tag->parent_id:''}}"><input type="radio" name="tongren_CP_tag_id" value="{{ $tag->id }}" {{ old('tongren_CP_tag_id')==$tag->id ?'checked':''}}>{{ $tag->tag_name }}（{{$tag->tag_explanation}}）</label>
                        @endforeach

                        <label class="radio-inline"><input type="radio" name="tongren_CP_tag_id" value="0" onClick="document.getElementById('fill_CP').style.display = 'block'" {{ old('tongren_CP_tag_id')=='0' ?'checked':''}}>其他CP</label>
                        <div id="fill_CP" style="display:{{ old('tongren_CP_tag_id')=='0'? 'block':'none' }}">
                            <label>填写同人作品CP全称:<input type="text" name="tongren_CP"
                                class="form-control" placeholder="请输入cp全称" value="{{ old('tongren_CP') }}"></label>
                        </div>
                    </div>
                </div>

                <div>
                    <h4>2. 请选择连载进度</h4>
                    @foreach ($tag_range['book_status_tags'] as $tag)
                    <label class="radio-inline"><input type="radio" name="book_status_tag" value="{{ $tag->id }}" {{ old('book_status_tag')==$tag->id ? 'checked':''}}>{{ $tag->tag_name }}</label>
                    @endforeach
                </div>

                <div>
                    <h4>3. 请选择文章篇幅</h4>
                    @foreach ($tag_range['book_length_tags'] as $tag)
                    <label class="radio-inline"><input type="radio" name="book_length_tag" value="{{ $tag->id }}" {{ old('book_length_tag')==$tag->id ? 'checked':''}}>{{ $tag->tag_name }}</label>
                    @endforeach
                </div>

                <div>
                    <h4>4. 请选择文章性向</h4>
                    @foreach ($tag_range['sexual_orientation_tags'] as $tag)
                    <label class="radio-inline"><input type="radio" name="sexual_orientation_tag" value="{{ $tag->id }}" {{ old('sexual_orientation_tag')==$tag->id ? 'checked':''}}>{{ $tag->tag_name }}</label>
                    @endforeach
                </div>

                <div>
                    <label for="is_bianyuan"><h4>5. 是否边缘限制敏感题材？</h4></label>
                    <div id="bianyuan" class="h6">
                        文章含肉超过20%，或开头具有较明显的性行为描写，或题材包含NP、人兽、触手、父子、乱伦、生子、产乳、abo、军政、黑道、性转……等边缘限制敏感题材，或估计不适合未成年人观看的，请务必勾选此项。勾选后，本文将不受搜索引擎直接抓取，不被未注册游客观看。<span style="color:#d66666">边缘题材未勾选边缘限制即发文的，严肃处理。</span>
                    </div>
                    <div>
                        <label class="radio-inline"><input type="radio" name="is_bianyuan" value="0" onclick="non_bianyuan_checked()" {{ old('bianyuan')=='0'?'checked':''}}>非边缘限制敏感</label>
                        <label class="radio-inline"><input type="radio" name="is_bianyuan" value="1" onclick="bianyuan_checked()" {{ old('bianyuan')=='1'?'checked':''}}>边缘限制敏感</label>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <label for="title"><h4>6. 标题：</h4></label>
                    <div id="biaotiguiding" class="h6">
                        标题请规范，尊重汉语语法规则，避免火星文、乱用符号标点等。文章类型、CP、背景、版本相关信息请在简介，文案 ，标签 ，备注等处展示，<span style="color:#d66666">不要放入标题。标题不得含有性描写、性暗示。<span>
                    </div>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="请输入不超过20字的标题">
                </div>

                <div class="form-group">
                    <label for="brief"><h4>7. 简介：</h4></label>
                    <div id="biaotiguiding" class="h6">
                        <span style="color:#d66666">简介中不得含有性描写、性暗示成分。<span>简介中请勿放置与文章无关的内容。
                    </div>
                    <input type="text" name="brief" class="form-control" value="{{ old('brief') }}" placeholder="请输入不超过25字的简介">
                </div>

                <div class="checkbox">
                    <label><input type="checkbox" name="is_anonymous" onclick="document.getElementById('majia').style.display = 'block'" {{ old('is_anonymous')? 'checked':'' }}>马甲？</label>
                    <div class="form-group text-right" id="majia" style="display:{{ old('anonymous')? 'block':'none' }}">
                        <input type="text" name="majia" class="form-control" value="{{Auth::user()->majia ?:'匿名咸鱼'}}">
                        <label for="majia"><small>(请输入不超过10字的马甲。马甲仅勾选“匿名”时有效，可以更改披马与否，但马甲名称不能再修改)</small></label>
                    </div>
                </div>

                <div class="text-center">
                    <a data-toggle="collapse" data-target="#more_options" class="h5">点击展开更多设置，如文案、勾选标签等（也可日后再填）</a>
                </div>

                <div id="more_options" class="collapse">
                    <div id="alltags">
                        <h4>8. 请从以下标签中选择不多于三个标签：</h4>
                        <?php $previous_tag_type = 0; ?>
                        <div>
                            @foreach ($tag_range['book_custom_Tags'] as $tag)
                            @if($previous_tag_type===0||$previous_tag_type!=$tag->tag_type)
                            <br><code>{{ $tag->tag_type }}:</code>
                            @endif
                            <label class="{{ $tag->is_bianyuan?'bianyuan':'' }} {{ $tag->channel_id==1? 'yuanchuang_block':'' }} {{ $tag->channel_id==2? 'tongren_block':'' }}">
                                <input type="checkbox" class="tags  {{ $tag->channel_id==1? 'yuanchuang':'' }} {{ $tag->channel_id==2? 'tongren':'' }} {{ $tag->parent_id>0?'parent'.$tag->parent_id:'' }}" name="tags[]" value="{{ $tag->id }}" {{ (is_array(old('tags')))&&(in_array($tag->id, old('tags')))? 'checked':'' }}>{{ $tag->tag_name }}&nbsp;&nbsp;
                            </label>
                            <?php $previous_tag_type = $tag->tag_type ?>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="wenan"><h4>9. 文案（可选）：</h4></label>
                        <div id="wenan" class="h6">
                            文案不是正文，文案属于对文章的简单介绍。文案采用“居中排列”的板式，而不是“向左对齐”。如果在这里发布正文，阅读效果不好。正文请在发布文章后，于文案下选择“新建章节”来建立。
                        </div>
                        <textarea name="body" id="markdowneditor" data-provide="markdown" rows="5" class="form-control">{{ old('body') }}</textarea>
                        <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-control addon-button">恢复数据</button>
                        <button href="#" type="button" onclick="wordscount('markdowneditor');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
                    </div>

                    <div class="checkbox">
                        <label><input type="checkbox" name="use_indentation" checked>段首缩进（自动空两格）？</label>&nbsp;
                        <br>
                        <label><input type="checkbox" name="is_public" checked>是否公开可见？</label>&nbsp;
                        <label><input type="checkbox" name="no_reply">是否禁止回帖？</label>&nbsp;
                        <br>
                        <label><input type="checkbox" name="use_markdown" >是否使用Markdown语法（不推荐）？</label>
                        <label><input type="checkbox" name="download_as_thread">开放讨论帖形式的书评下载？（正文+全部评论，按回帖时间顺序排列）</label>&nbsp;
                        <label><input type="checkbox" name="download_as_book" >开放脱水书籍下载？（不含回帖的正文章节）</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-lg btn-danger sosad-button">发布</button>
            </form>
        </div>
    </div>
</div>
@stop
