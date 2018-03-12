

 <div id="yuanchuang" style="display:{{ ((($book->id>0)&&($book->original==1))||(($book->id==0)&&(old('originalornot')=='1'))) ? 'block' : 'none' }} ">
    <h4>  1.1 请选择主题对应类型：</h4>
   <?php $labels =\App\Channel::find(1)->labels()->get(); ?>
   @foreach ($labels as $index => $label)
      <label class="radio-inline"><input type="radio" name="label" value="{{ $label->id }}" {{ ((($thread->id>0)&&($thread->label_id==$label->id))||(($thread->id==0)&&(old('label')==strval($label->id)))) ? 'checked' : '' }}>{{ $label->labelname }}</label>
   @endforeach
 </div>

 <div id="tongren" style="display:{{ ((($book->id>0)&&($book->original==0))||(($book->id==0)&&(old('originalornot')=='0'))) ? 'block' : 'none' }}">
    <h4>&nbsp;&nbsp;1.1 请选择主题对应类型：</h4>
   <?php $labels = App\Channel::find(2)->labels()->get(); ?>
   @foreach ($labels as $index => $label)
      <label class="radio-inline"><input type="radio" name="label" value="{{ $label->id }}" {{ ((($thread->id>0)&&($thread->label_id==$label->id))||(($thread->id==0)&&(old('label')==strval($label->id)))) ? 'checked' : '' }}>{{ $label->labelname }}</label>
   @endforeach
   <br>
   <h4>&nbsp;&nbsp;1.2 请填写原著作品</h4>
   <input type="text" name="original_work" class="form-control" value="{{ old('original_work') ?? ($tongren ? $tongren->tongren_yuanzhu : '') }}" placeholder="请输入完整原著作品名称">
   <h4>&nbsp;&nbsp;1.3 请填写同人作品CP</h4>
   <input type="text" name="tongren_cp" class="form-control" value="{{ old('tongren_cp') ?? ($tongren ? $tongren->tongren_cp : '') }}" placeholder="请输入cp简称">
 </div>



<div>
   <h4>2. 请选择连载进度</h4>
   <label class="radio-inline"><input type="radio" name="book_status" value="1" {{ (old('book_status') ?? $book->book_status) == '1' ? 'checked' : ''}}>连载</label>
   <label class="radio-inline"><input type="radio" name="book_status" value="2"  {{ (old('book_status') ?? $book->book_status) == '2' ? 'checked' : ''}}>完结</label>
   <label class="radio-inline"><input type="radio" name="book_status" value="3"  {{ (old('book_status') ?? $book->book_status) == '3' ? 'checked' : ''}}>暂停</label>
</div>

<div>
   <h4>3. 请选择文章篇幅</h4>
   <label class="radio-inline"><input type="radio" name="book_length" value="1" {{ (old('book_length') ?? $book->book_length) == '1' ? 'checked' : ''}}>短篇</label>
   <label class="radio-inline"><input type="radio" name="book_length" value="2" {{ (old('book_length') ?? $book->book_length) == '2' ? 'checked' : ''}}>中篇</label>
   <label class="radio-inline"><input type="radio" name="book_length" value="3" {{ (old('book_length') ?? $book->book_length) == '3' ? 'checked' : ''}}>长篇</label>
   <br>
</div>

<div>
   <h4>4. 请选择文章性向</h4>
   <label class="radio-inline"><input type="radio" name="sexual_orientation" value="1" {{ (old('sexual_orientation') ?? $book->sexual_orientation) == '1' ? 'checked' : ''}}>BL</label>
   <label class="radio-inline"><input type="radio" name="sexual_orientation" value="2" {{ (old('sexual_orientation') ?? $book->sexual_orientation) == '2' ? 'checked' : ''}}>GL</label>
   <label class="radio-inline"><input type="radio" name="sexual_orientation" value="3" {{ (old('sexual_orientation') ?? $book->sexual_orientation) == '3' ? 'checked' : ''}}>BG</label>
   <label class="radio-inline"><input type="radio" name="sexual_orientation" value="4" {{ (old('sexual_orientation') ?? $book->sexual_orientation) == '4' ? 'checked' : ''}}>GB</label>
   <label class="radio-inline"><input type="radio" name="sexual_orientation" value="5" {{ (old('sexual_orientation') ?? $book->sexual_orientation) == '5' ? 'checked' : ''}}>混合性向</label>
   <label class="radio-inline"><input type="radio" name="sexual_orientation" value="6" {{ (old('sexual_orientation') ?? $book->sexual_orientation) == '6' ? 'checked' : ''}}>无CP</label>
   <label class="radio-inline"><input type="radio" name="sexual_orientation" value="7" {{ (old('sexual_orientation') ?? $book->sexual_orientation) == '7' ? 'checked' : ''}}>其他性向</label>
   <br>
</div>

<div>
   <label for="bianyuan"><h4>5. 是否边缘敏感题材？</h4></label>
   <a data-toggle="collapse" data-target="#bianyuan" class="h6">（点击查看什么属于边缘敏感题材）</a>
    <div id="bianyuan" class="collapse h6">
       文章含肉超过20%，或开头具有较明显的性行为描写，或题材包含人兽、触手、父子、乱伦、生子、产乳、abo、冰恋、军政、黑道、性转……等边缘敏感题材，或估计不适合未成年人观看的，请勾选此项。勾选后，本文将不受搜索引擎直接抓取，不被未注册游客观看。
    </div>
    <div class="">
      <label class="radio-inline"><input type="radio" name="bianyuan" value="0" {{ old('bianyuan')=='0' ? 'checked' : ($thread->bianyuan==0 ? 'checked' : '' ) }}  onclick="uncheckAll('bianyuantags');document.getElementById('bianyuantags').style.display = 'none'">非边缘</label>
      <label class="radio-inline"><input type="radio" name="bianyuan" value="1" {{ old('bianyuan')=='1' ? 'checked' : ($thread->bianyuan==1 ? 'checked' : '' ) }} onclick="document.getElementById('bianyuantags').style.display = 'block'">边缘</label>
    </div>
</div>

<div id="alltags">
  <h4>6. 请从以下标签中选择不多于三个标签：</h4>
  <?php $tags = DB::table('tags')->where('tag_group', 0)->get();$this_threads_tags = $thread->tags; $tag_list = []; $oldtags = old('tags');
   foreach ($this_threads_tags as $t_tag) {
      array_push($tag_list, $t_tag->id);
   }
   ?>
  @foreach ($tags as $tag)
     <input type="checkbox" class="tags" name="tags[]" value="{{ $tag->id }}" {{ (((is_array($oldtags))&&(in_array($tag->id, old('tags'))))||((!$oldtags)&&(in_array($tag->id, $tag_list))))? 'checked':'' }} >{{ $tag->tagname }}
  @endforeach
  <br>

  <div id="bianyuantags" style="display:{{ ((old('bianyuan')&&(old('bianyuan')=='1'))||($thread->bianyuan==1))? 'block':'none' }}">
     <?php $tags = DB::table('tags')->where('tag_group', 5)->get(); ?>
     @foreach ($tags as $tag)
        <input type="checkbox" class="tags" name="tags[]" value="{{ $tag->id }}" {{ (((is_array($oldtags))&&(in_array($tag->id, old('tags'))))||((!$oldtags)&&(in_array($tag->id, $tag_list))))? 'checked':''}} >{{ $tag->tagname }}
     @endforeach
  </div>
</div>
<br>
<div class="form-group">
  <label for="title"><h4>7. 标题：</h4></label><a data-toggle="collapse" data-target="#biaotiguiding" class="h6">（点击查看关于规范标题格式的说明）</a>
  <div id="biaotiguiding" class="collapse h6">
    标题请规范，尊重汉语语法规则，避免火星文、乱用符号标点等。文章类型、CP、背景、版本相关信息请在简介，文案 ，标签 ，备注等处展示，不要放入标题。
  </div>
  <input type="text" name="title" class="form-control" value="{{ old('title') ?? $thread->title }}">
</div>

<div class="form-group">
<label for="brief"><h4>8. 简介：</h4></label>
<input type="text" name="brief" class="form-control" value="{{ old('brief') ?? $thread->brief }}">
</div>

<div class="form-group">
  <label for="wenan"><h4>9. 文案（不是正文）：</h4></label><a data-toggle="collapse" data-target="#wenan" class="h6">（点击查看“文案”与“正文”的区别）</a>
  <div id="wenan" class="collapse h6">
    文案不是正文，文案属于对文章的简单介绍。文案采用“居中排列”的板式，而不是“向左对齐”。如果在这里发布正文，阅读效果不好。正文请在发布文章后，于文案下选择“新建章节”来建立。
  </div>
  <textarea name="wenan" id="markdowneditor" data-provide="markdown" rows="12" class="form-control">{{ $thread->body }}</textarea>
  <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-control addon-button">恢复数据</button>
  <button href="#" type="button" onclick="wordscount('markdowneditor');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
</div>

<div class="checkbox">
  <label><input type="checkbox" name="anonymous" {{ (old('anonymous') ?? $thread->anonymous) ? 'checked' : '' }}>马甲？</label>
  <div class="form-group {{ $thread->id>0 ? 'grayout' :'' }}" id="majia" style="display:block">
      <input type="text" name="majia" class="form-control" value="{{ old('majia') ?? $thread->majia ?? '匿名咸鱼'}}" {{ $book->id>0 ? 'readonly' :''}}>
      <label for="majia"><small>(马甲不可修改，只能脱马或批马)</small></label>
  </div>
  <label><input type="checkbox" name="markdown" {{ (old('markdown') ?? $thread->markdown) ? 'checked' : '' }}>使用Markdown语法？</label>
  <label><input type="checkbox" name="indentation" {{ (old('indentation') ?? $book->indentation) ? 'checked' : '' }}>段首缩进（自动空两格）？</label>
</div>
<div class="checkbox">
  <label><input type="checkbox" name="public" {{ (old('public') ?? $thread->public) ? 'checked' : '' }}>是否公开可见？</label>&nbsp;
  <label><input type="checkbox" name="noreply" {{ (old('noreply') ?? $thread->noreply) ? 'checked' : '' }}>是否禁止回帖？</label>&nbsp;
  <label><input type="checkbox" name="download_as_thread" {{ (old('download_as_thread') ?? $thread->download_as_thread) ? 'checked' : '' }}>开放书评下载？</label>&nbsp;
  <label><input type="checkbox" name="download_as_book" {{ (old('download_as_thread') ?? $thread->download_as_book) ? 'checked' : '' }}>开放书籍下载？</label>&nbsp;
</div>
