<!-- 下面是搜索页面，一直显示，但需要用户登陆才能真正搜索 -->
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="search-container">
            <form method="GET" action="{{route('search')}}" id="search_form">
                <input type="textarea" id="search_input" placeholder="搜索..." name="search" style="max-width:50%">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>
</div>
