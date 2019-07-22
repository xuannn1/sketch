<!-- 查看个人主页，修改个人介绍，佩戴头衔的三个入口 -->
<div class="hidden-sm hidden-md hidden-lg overflow-hidden text-center container-fluid">
    <div class="row text-center">
        <div class="col-xs-4">
            <a href="{{ route('user.show', $user->id) }}" class="btn btn-info btn-md sosad-button-control">查看主页</a>
        </div>
        <div class="col-xs-4">
            <a href="{{ route('user.edit_introduction') }}" class="btn btn-info btn-md sosad-button-control">修改介绍</a>
        </div>
        <div class="col-xs-4">
            <a href="{{route('title.mytitles')}}" class="btn btn-info btn-md sosad-button-control">佩戴头衔</a>
        </div>
    </div>
</div>
<div class="hidden-xs container-fluid">
    <div class="row text-center">
        <div class="col-xs-4">
            <a href="{{ route('user.show', $user->id) }}" class="btn btn-info btn-lg sosad-button-control">查看个人主页</a>
        </div>
        <div class="col-xs-4">
            <a href="{{ route('user.edit_introduction') }}" class="btn btn-info btn-lg sosad-button-control">修改个人介绍</a>
        </div>
        <div class="col-xs-4">
            <a href="{{ route('title.mytitles') }}" class="btn btn-info btn-lg sosad-button-control">佩戴个人头衔</a>
        </div>
    </div>
</div>
