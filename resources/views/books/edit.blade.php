@extends('layouts.default')
@section('title', '编辑文章信息')
@section('content')
<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>编辑文章信息</h1>
        </div>
        <div class="panel-body">
            @include('shared.errors')

            <form method="POST" action="{{ route('book.update', $book->id) }}">
                {{ csrf_field() }}
                <h6>（发文前请阅读：<a href="http://sosad.fun/threads/136">《版规的详细说明（草案）》</a>。关于网站使用的常规问题，可以查看如下页面：<a href="{{ route('about') }}">《关于本站》</a>，<a href="{{ route('help') }}">《使用帮助》</a>。除文章原创性之外，其他内容均可修改。感谢发文！）</h6>
                <div class="form-group">
                    <label for="title"><h4>标题：</h4></label>
                    <div id="biaotiguiding" class="h6">
                        标题请规范，尊重汉语语法规则，避免火星文、乱用符号标点等。文章类型、CP、背景、版本相关信息请在简介，文案 ，标签 ，备注等处展示，<span style="color:#d66666">不要放入标题。标题、简介、章节名不得含有性描写、性暗示。<span>
                    </div>
                    <input type="text" name="title" class="form-control" value="{{ $thread->title }}">
                </div>

                <div class="form-group">
                    <label for="brief"><h4>简介：</h4></label>
                    <div id="biaotiguiding" class="h6">
                        <span style="color:#d66666">简介中不得含有性描写、性暗示成分。<span>
                    </div>
                    <input type="text" name="brief" class="form-control" value="{{ $thread->brief }}">
                </div>

                <div class="form-group">
                    <label for="wenan"><h4>文案（可选）：</h4></label>
                    <div id="wenan" class="h6">
                        文案不是正文，文案属于对文章的简单介绍。文案采用“居中排列”的板式，而不是“向左对齐”。如果在这里发布正文，阅读效果不好。正文请在发布文章后，于文案下选择“新建章节”来建立。
                    </div>
                    <textarea name="wenan" id="markdowneditor" data-provide="markdown" rows="5" class="form-control">{{ $thread->mainpost->body }}</textarea>
                    <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-control addon-button">恢复数据</button>
                    <button type="button" onclick="removespace('markdowneditor')" class="sosad-button-control addon-button">清理段首空格</button>
                    <button href="#" type="button" onclick="wordscount('markdowneditor');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
                </div>

                <div class="grayout">
                    <h4>1. 请选择文章原创性</h4>
                    <label class="hidden"><input name="channel_id" value = "{{ $thread->channel_id }}" ></label>
                    <label class="radio-inline"><input type="radio" disabled {{ $thread->channel_id==1 ? 'checked' : '' }} >原创</label>
                    <label class="radio-inline"><input type="radio" disabled {{ $thread->channel_id==2 ? 'checked' : '' }} >同人</label>
                </div>

                <div id="yuanchuang" style="display:{{ $thread->channel_id==1 ? 'block':'none'}}">
                    <h4>  1.1 请选择主题对应类型：</h4>
                    @foreach ($all_book_tags['labels_yuanchuang'] as $label)
                    <label class="radio-inline"><input type="radio" name="label_id" value="{{ $label->id }}" {{ $thread->label_id==$label->id ? 'checked':''}}>{{ $label->labelname }}</label>
                    @endforeach
                </div>
                <div id="tongren" style="display:{{ $thread->channel_id==2?'block':'none'}}">
                    <h4>&nbsp;&nbsp;1.1 请选择主题对应类型：</h4>
                    @foreach ($all_book_tags['labels_tongren'] as $label)
                    <label class="radio-inline"><input type="radio" name="label_id" value="{{ $label->id }}" onClick="show_only_this_label_tongren('{{$label->id}}');" {{ $thread->label_id==$label->id ? 'checked':''}}>{{ $label->labelname }}</label>
                    @endforeach
                    <br>
                    <div id="tongren_yuanzhu">
                        <h4>&nbsp;&nbsp;1.2 从下列同人原著作品中，选择对应的同人原著作品简称</h4>
                        @foreach ($all_book_tags['tags_tongren_yuanzhu'] as $tag)
                        <label class="radio-inline {{$tag->label_id==$thread->label_id? '':'hidden'}} label_{{$tag->label_id}} tongren_yuanzhu_tag"><input type="radio" name="tongren_yuanzhu_tag_id" value="{{ $tag->id }}" onClick="show_only_this_cp_tags('{{$tag->id}}')" {{ $book->tongren->tongren_yuanzhu_tag_id==$tag->id ?'checked':''}}>{{ $tag->tagname }}（{{$tag->tag_explanation}}）</label>
                        @endforeach
                        <label class="radio-inline"><input type="radio" name="tongren_yuanzhu_tag_id" value="0" onClick="show_only_this_cp_tags('{{$tag->id}}')" {{ $book->tongren->tongren_yuanzhu_tag_id==0 ?'checked':''}}>其他原著</label>
                        <br>
                        <div class="">
                            <label>填写原著作品名称:<input type="text" name="tongren_yuanzhu" class="form-control" value="{{ $book->tongren->tongren_yuanzhu }}"></label>
                        </div>
                    </div>
                    <div id="tongren_cp">
                        <h5>&nbsp;&nbsp;1.3 从下列对应同人CP中，选择对应的CP简称</h5>
                        @foreach ($all_book_tags['tags_tongren_cp'] as $tag)
                        <label class="radio-inline {{$tag->tag_belongs_to==$book->tongren->tongren_yuanzhu_tag_id? '':'hidden'}} tongren_yuanzhu_{{$tag->tag_belongs_to}} tongren_cp_tag"><input type="radio" name="tongren_CP_tag_id" value="{{ $tag->id }}" {{ ($book->tongren->tongren_CP_tag_id) == $tag->id ?'checked':'' }}>{{ $tag->tagname }}（{{$tag->tag_explanation}}）</label>
                        @endforeach
                        <label class="radio-inline"><input type="radio" name="tongren_CP_tag_id" value="0" {{ $book->tongren->tongren_CP_tag_id==0 ?'checked':''}}>其他CP</label>
                        <br>
                        <div class="">
                            <label>填写同人作品CP:<input type="text" name="tongren_cp" class="form-control" value="{{ $book->tongren->tongren_cp }}"></label>
                        </div>
                    </div>
                </div>
                <div>
                    <h4>2. 请选择连载进度</h4>
                    <label class="radio-inline"><input type="radio" name="book_status" value="1" {{ $book->book_status==1 ? 'checked':''}}>连载</label>
                    <label class="radio-inline"><input type="radio" name="book_status" value="2" {{ $book->book_status==2 ? 'checked':''}}>完结</label>
                    <label class="radio-inline"><input type="radio" name="book_status" value="3" {{ $book->book_status==3 ? 'checked':''}}>暂停</label>
                </div>

                <div>
                    <h4>3. 请选择文章篇幅</h4>
                    <label class="radio-inline"><input type="radio" name="book_length" value="1" {{ $book->book_length==1?'checked':''}}>短篇</label>
                    <label class="radio-inline"><input type="radio" name="book_length" value="2" {{ $book->book_length==2?'checked':''}}>中篇</label>
                    <label class="radio-inline"><input type="radio" name="book_length" value="3" {{ $book->book_length==3?'checked':''}}>长篇</label>
                    <label class="radio-inline"><input type="radio" name="book_length" value="4" {{ $book->book_length==4?'checked':''}}>大纲</label>
                    <br>
                </div>

                <div>
                    <h4>4. 请选择文章性向</h4>
                    <label class="radio-inline"><input type="radio" name="sexual_orientation" value="1" {{ $book->sexual_orientation==1?'checked':''}}>BL</label>
                    <label class="radio-inline"><input type="radio" name="sexual_orientation" value="2" {{ $book->sexual_orientation==2?'checked':''}}>GL</label>
                    <label class="radio-inline"><input type="radio" name="sexual_orientation" value="3" {{ $book->sexual_orientation==3?'checked':''}}>BG</label>
                    <label class="radio-inline"><input type="radio" name="sexual_orientation" value="4" {{ $book->sexual_orientation==4?'checked':''}}>GB</label>
                    <label class="radio-inline"><input type="radio" name="sexual_orientation" value="5" {{ $book->sexual_orientation==5?'checked':''}}>混合性向</label>
                    <label class="radio-inline"><input type="radio" name="sexual_orientation" value="6" {{ $book->sexual_orientation==6?'checked':''}}>无CP</label>
                    <label class="radio-inline"><input type="radio" name="sexual_orientation" value="7" {{ $book->sexual_orientation==7?'checked':''}}>其他性向</label>
                    <br>
                </div>

                <div>
                    <label for="bianyuan"><h4>5. 是否边缘限制敏感题材？</h4></label>
                    <div id="bianyuan" class="h6">
                        文章含肉超过20%，或开头具有较明显的性行为描写，或题材包含NP、人兽、触手、父子、乱伦、生子、产乳、abo、军政、黑道、性转……等边缘限制敏感题材，或估计不适合未成年人观看的，请勾选此项。勾选后，本文将不受搜索引擎直接抓取，不被未注册游客观看。<span style="color:#d66666">边缘限制敏感题材未勾选边缘限制即发文的，严肃处理。</span>
                    </div>
                    <div>
                        <label class="radio-inline"><input type="radio" name="bianyuan" value="0" onclick="uncheckAll('bianyuantags');document.getElementById('bianyuantags').style.display = 'none'" {{ $thread->bianyuan?'':'checked'}}>非边缘限制敏感</label>
                        <label class="radio-inline"><input type="radio" name="bianyuan" value="1" onclick="document.getElementById('bianyuantags').style.display = 'block'" {{ $thread->bianyuan?'checked':''}}>边缘限制敏感</label>
                    </div>
                </div>

                <div id="alltags">
                    <h4>6. 请从以下标签中选择不多于三个标签：</h4>
                    <div id="feibianyuantags">
                        @foreach ($all_book_tags['tags_feibianyuan'] as $tag)
                        <input type="checkbox" class="tags" name="tags[]" value="{{ $tag->id }}" {{ (is_array($tags))&&(in_array($tag->id, $tags))? 'checked':'' }}>{{ $tag->tagname }}
                        @endforeach
                    </div>
                    <div id="bianyuantags" style="display:{{ $thread->bianyuan?'block':'none'}}">
                        <hr>
                        @foreach ($all_book_tags['tags_bianyuan'] as $tag)
                        <input type="checkbox" class="tags" name="tags[]" value="{{ $tag->id }}" {{ (is_array($tags))&&(in_array($tag->id, $tags))? 'checked':'' }}>{{ $tag->tagname }}
                        @endforeach
                    </div>
                    <div id="tongrentags" style="display: {{ ($thread->channel_id)==1 ? 'none':'block'}}">
                        <hr>
                        @foreach ($all_book_tags['tags_tongren'] as $tag)
                        <input type="checkbox" class="tags" name="tags[]" value="{{ $tag->id }}" {{ (is_array($tags))&&(in_array($tag->id, $tags))? 'checked':'' }}>{{ $tag->tagname }}
                        @endforeach
                    </div>
                </div>
                <br>
                <div class="checkbox">
                    <label><input type="checkbox" name="anonymous" {{ $thread->anonymous? 'checked':'' }}>马甲？</label>
                    <div class="form-group text-right" id="majia">
                        <input type="text" name="majia" class="form-control" value="{{ $thread->majia ?? '匿名咸鱼' }}" readonly>
                        <label for="majia"><small>(马甲仅勾选“匿名”时有效，可以更改披马与否，但马甲名称不能再修改)</small></label>
                    </div>

                    <label><input type="checkbox" name="indentation" {{ $book->indentation ? 'checked' : '' }}>段首缩进（自动空两格）？</label>&nbsp;
                    <br>
                    <label><input type="checkbox" name="public" {{ $thread->public ? 'checked' : '' }}>是否公开可见？</label>&nbsp;
                    <label><input type="checkbox" name="noreply" {{ $thread->noreply ? 'checked' : '' }}>是否禁止回帖？</label>&nbsp;
                    <br>
                    <label><input type="checkbox" name="download_as_thread" {{ $thread->download_as_thread ? 'checked' : '' }}>开放书评下载？</label>&nbsp;
                    <label><input type="checkbox" name="download_as_book" {{  $thread->download_as_book ? 'checked' : '' }}>开放书籍下载？</label>
                </div>

                <button type="submit" class="btn btn-danger sosad-button">确认修改</button>
            </form>
        </div>
    </div>
</div>
@stop
