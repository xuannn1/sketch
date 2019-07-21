<!-- 下面是搜索页面，一直显示，但需要用户登陆才能真正搜索 -->
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="search-container">
            <form method="GET" action="{{route('search')}}" id="search_form">
                <input type="textarea" id="search_input" placeholder="搜帮助、书、用户、标签...？" name="search" style="width:250px">
                <button type="submit" class="search-button"><i class="fa fa-search bigger-20" type="submit" ></i></button>
            </form>
        </div>
    </div>
</div>
