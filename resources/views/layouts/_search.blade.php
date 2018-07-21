<!-- 下面是搜索页面，一直显示，但需要用户登陆才能真正搜索 -->
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="search-container">
            <form method="GET" action="{{ route('search') }}" id="search_form">
                <select name="search_options" form="search_form" onchange="if
                (this.options[this.selectedIndex].value=='tongren_yuanzhu'){
                    document.getElementById('tongren_cp_name').style.display = 'inline';document.getElementById('search_input').placeholder = '同人原著';}else{document.getElementById('tongren_cp_name').style.display = 'none';}">
                    <option value ="threads">标题</option>
                    <option value ="users">用户</option>
                    <option value ="tongren_yuanzhu" >同人</option>
                </select>
                <input type="textarea" id="search_input" placeholder="搜索..." name="search">
                <input type="textarea" placeholder="同人CP" name="tongren_cp" id="tongren_cp_name" style="display:none">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>
</div>
