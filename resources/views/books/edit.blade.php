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

                    <div class="form-group">
                        <label for="title"><h4>标题：</h4></label><a data-toggle="collapse" data-target="#biaotiguiding" class="h6">（点击查看关于规范标题格式的说明）</a>
                        <div id="biaotiguiding" class="collapse h6">
                            标题请规范，尊重汉语语法规则，避免火星文、乱用符号标点等。文章类型、CP、背景、版本相关信息请在简介，文案 ，标签 ，备注等处展示，不要放入标题。
                        </div>
                        <input type="text" name="title" class="form-control" value="{{ $thread->title }}">
                    </div>

                    <div class="form-group">
                        <label for="brief"><h4>简介：</h4></label>
                        <input type="text" name="brief" class="form-control" value="{{ $thread->brief }}">
                    </div>

                    <div class="form-group">
                        <label for="wenan"><h4>文案：</h4></label><a data-toggle="collapse" data-target="#wenan" class="h6">（点击查看“文案”与“正文”的区别）</a>
                        <div id="wenan" class="collapse h6">
                            文案不是正文，文案属于对文章的简单介绍。文案采用“居中排列”的板式，而不是“向左对齐”。如果在这里发布正文，阅读效果不好。正文请在发布文章后，于文案下选择“新建章节”来建立。
                        </div>
                        <textarea name="wenan" id="markdowneditor" data-provide="markdown" rows="5" class="form-control">{{ $thread->body }}</textarea>
                        <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-control addon-button">恢复数据</button>
                        <button href="#" type="button" onclick="wordscount('markdowneditor');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
                    </div>

                    <div class="grayout">
                        <h4>1. 请选择文章原创性</h4>
                        <label class="hidden"><input name="channel_id" value = "{{ $thread->channel_id }}" ></label>
                        <label class="radio-inline"><input type="radio" disabled {{ $thread->channel_id==1 ? 'checked' : '' }} >原创</label>
                        <label class="radio-inline"><input type="radio" disabled {{ $thread->channel_id==2 ? 'checked' : '' }} >同人</label>
                    </div>

                    <div id="yuanchuang" style="display:{{ $thread->channel_id==1?'block':'none'}}">
                        <h4>  1.1 请选择主题对应类型：</h4>
                        @foreach ($labels_yuanchuang as $label)
                            <label class="radio-inline"><input type="radio" name="label_id" value="{{ $label->id }}" {{ $thread->label_id==$label->id ? 'checked':''}}>{{ $label->labelname }}</label>
                        @endforeach
                    </div>

                    <div id="tongren" style="display:{{ $thread->channel_id==2?'block':'none'}}">
                        <h4>&nbsp;&nbsp;1.1 请选择主题对应类型：</h4>
                        @foreach ($labels_tongren as $label)
                            <label class="radio-inline"><input type="radio" name="label_id" value="{{ $label->id }}" {{ $thread->label_id==$label->id ? 'checked':''}}>{{ $label->labelname }}</label>
                        @endforeach
                        <br>
                        <h4>&nbsp;&nbsp;1.2 请填写原著作品</h4>
                        <input type="text" name="tongren_yuanzhu" class="form-control" value="{{ $book->tongren->tongren_yuanzhu }}">
                        <h4>&nbsp;&nbsp;1.3 请填写同人作品CP</h4>
                        <input type="text" name="tongren_cp" class="form-control" value="{{ $book->tongren->tongren_cp }}">
                    </div>

                    <div>
                        <h4>2. 请选择连载进度</h4>
                        <label class="radio-inline"><input type="radio" name="book_status" value="1" {{ $book->book_status=='1'?'checked':''}}>连载</label>
                        <label class="radio-inline"><input type="radio" name="book_status" value="2" {{ $book->book_status=='2'?'checked':''}}>完结</label>
                        <label class="radio-inline"><input type="radio" name="book_status" value="3" {{ $book->book_status=='3'?'checked':''}}>暂停</label>
                    </div>

                    <div>
                        <h4>3. 请选择文章篇幅</h4>
                        <label class="radio-inline"><input type="radio" name="book_length" value="1" {{ $book->book_length=='1'?'checked':''}}>短篇</label>
                        <label class="radio-inline"><input type="radio" name="book_length" value="2" {{ $book->book_length=='2'?'checked':''}}>中篇</label>
                        <label class="radio-inline"><input type="radio" name="book_length" value="3" {{ $book->book_length=='3'?'checked':''}}>长篇</label>
                        <br>
                    </div>

                    <div>
                        <h4>4. 请选择文章性向</h4>
                        <label class="radio-inline"><input type="radio" name="sexual_orientation" value="1" {{ $book->sexual_orientation=='1'?'checked':''}}>BL</label>
                        <label class="radio-inline"><input type="radio" name="sexual_orientation" value="2" {{ $book->sexual_orientation=='2'?'checked':''}}>GL</label>
                        <label class="radio-inline"><input type="radio" name="sexual_orientation" value="3" {{ $book->sexual_orientation=='3'?'checked':''}}>BG</label>
                        <label class="radio-inline"><input type="radio" name="sexual_orientation" value="4" {{ $book->sexual_orientation=='4'?'checked':''}}>GB</label>
                        <label class="radio-inline"><input type="radio" name="sexual_orientation" value="5" {{ $book->sexual_orientation=='5'?'checked':''}}>混合性向</label>
                        <label class="radio-inline"><input type="radio" name="sexual_orientation" value="6" {{ $book->sexual_orientation=='6'?'checked':''}}>无CP</label>
                        <label class="radio-inline"><input type="radio" name="sexual_orientation" value="7" {{ $book->sexual_orientation=='7'?'checked':''}}>其他性向</label>
                        <br>
                    </div>

                    <div>
                        <label for="bianyuan"><h4>5. 是否边缘敏感题材？</h4></label>
                        <a data-toggle="collapse" data-target="#bianyuan" class="h6">（点击查看什么属于边缘敏感题材）</a>
                        <div id="bianyuan" class="collapse h6">
                          文章含肉超过20%，或开头具有较明显的性行为描写，或题材包含人兽、触手、父子、乱伦、生子、产乳、abo、冰恋、军政、黑道、性转……等边缘敏感题材，或估计不适合未成年人观看的，请勾选此项。勾选后，本文将不受搜索引擎直接抓取，不被未注册游客观看。
                        </div>
                        <div>
                            <label class="radio-inline"><input type="radio" name="bianyuan" value="0" onclick="uncheckAll('bianyuantags');document.getElementById('bianyuantags').style.display = 'none'" {{ $thread->bianyuan?'':'checked'}}>非边缘</label>
                            <label class="radio-inline"><input type="radio" name="bianyuan" value="1" onclick="document.getElementById('bianyuantags').style.display = 'block'" {{ $thread->bianyuan?'checked':''}}>边缘</label>
                        </div>
                    </div>

                    <div id="alltags">
                        <h4>6. 请从以下标签中选择不多于三个标签：</h4>
                        @foreach ($tags_feibianyuan as $tag)
                            <input type="checkbox" class="tags" name="tags[]" value="{{ $tag->id }}" {{ (is_array($tags))&&(in_array($tag->id, $tags))? 'checked':'' }}>{{ $tag->tagname }}
                        @endforeach
                        <br>
                        <div id="bianyuantags" style="display:{{ $thread->bianyuan?'block':'none'}}">
                        @foreach ($tags_bianyuan as $tag)
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
                      <!-- <label><input type="checkbox" name="markdown" {{ $thread->mainpost->markdown ? 'checked' : '' }}>使用Markdown语法？</label>&nbsp; -->
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
