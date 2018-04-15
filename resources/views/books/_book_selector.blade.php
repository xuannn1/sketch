
<div class="dropdown">
   <form method="POST" action="{{ route('books.filter') }}"  name="book_filter">
         {{ csrf_field() }}
         <span class="button-group">
            <button type="button" class="btn btn-default btn-xs dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">原创性<span class="caret"></span></button>
            <ul class="dropdown-menu">
               <li><input type="checkbox" name="original[]" value="1"checked />&nbsp;原创</li>
               <li><input type="checkbox" name="original[]" value="2"checked />&nbsp;同人</li>
            </ul>
         </span>
         <span class="button-group">
            <button type="button" class="btn btn-default btn-xs dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">篇幅<span class="caret"></span></button>
            <ul class="dropdown-menu">
               <li><input type="checkbox" name="length[]" value="1"checked />&nbsp;短篇</li>
               <li><input type="checkbox" name="length[]" value="2"checked />&nbsp;中篇</li>
               <li><input type="checkbox" name="length[]" value="3"checked />&nbsp;长篇</li>
            </ul>
         </span>
         <span class="button-group">
            <button type="button" class="btn btn-default btn-xs dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">进度<span class="caret"></span></button>
            <ul class="dropdown-menu">
               <li><input type="checkbox" name="status[]" value="1"checked />&nbsp;连载</li>
               <li><input type="checkbox" name="status[]" value="2"checked />&nbsp;完结</li>
               <li><input type="checkbox" name="status[]" value="3"checked />&nbsp;暂停</li>
            </ul>
         </span>
         <span class="button-group">
            <button type="button" class="btn btn-default btn-xs dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">性向<span class="caret"></span></button>
            <ul class="dropdown-menu">
               <li><input type="checkbox" name="sexual_orientation[]" value="0"checked />&nbsp;性向未知</li>
               <li><input type="checkbox" name="sexual_orientation[]" value="1"checked />&nbsp;BL</li>
               <li><input type="checkbox" name="sexual_orientation[]" value="2"checked />&nbsp;GL</li>
               <li><input type="checkbox" name="sexual_orientation[]" value="3"checked />&nbsp;BG</li>
               <li><input type="checkbox" name="sexual_orientation[]" value="4"checked />&nbsp;GB</li>
               <li><input type="checkbox" name="sexual_orientation[]" value="5"checked />&nbsp;混合性向</li>
               <li><input type="checkbox" name="sexual_orientation[]" value="6"checked />&nbsp;无CP</li>
               <li><input type="checkbox" name="sexual_orientation[]" value="7"checked />&nbsp;其他性向</li>
            </ul>
         </span>
         <span class="button-group">
            <button type="button" class="btn btn-default btn-xs dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">边缘<span class="caret"></span></button>
            <ul class="dropdown-menu">
               <li><input type="checkbox" name="bianyuan[]" value="0"checked />&nbsp;非边缘</li>
               <li><input type="checkbox" name="bianyuan[]" value="1"checked />&nbsp;边缘</li>
            </ul>
         </span>
        <button type="submit" name="button" class="btn btn-xs btn-primary sosad-button">提交</button>
 </form>
</div>
