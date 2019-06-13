<span class = "pull-right">
    <a href="#" data-toggle="modal" data-target="#TriggerAdminThread{{ $thread->id }}" class="btn btn-sm btn-danger sosad-button admin-button"><span class="glyphicon glyphicon-user"></span>管理本帖</a>
</span>

<div class="modal fade" id="TriggerAdminThread{{ $thread->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.threadmanagement',$thread->id)}}" method="POST">
                {{ csrf_field() }}
                <div class="admin-symbol">
                    <h1>管理员权限专区：警告！请勿进行私人用户操作！</h1>
                </div>
                @if(!$thread->locked)
                <div class="radio">
                    <label><input type="radio" name="controlthread" value="1">锁帖</label>
                </div>
                @else
                <div class="radio">
                    <label><input type="radio" name="controlthread" value="2">解锁</label>
                </div>
                @endif

                @if($thread->public)
                <div class="radio">
                    <label><input type="radio" name="controlthread" value="3">转私密</label>
                </div>
                @else
                <div class="radio">
                    <label><input type="radio" name="controlthread" value="4">转公开</label>
                </div>
                @endif

                @if(!$thread->bianyuan)
                <div class="radio">
                    <label><input type="radio" name="controlthread" value="15">转边缘</label>
                </div>
                @else
                <div class="radio">
                    <label><input type="radio" name="controlthread" value="16">转非边缘</label>
                </div>
                @endif

                @if(!$thread->deleted_at)
                <div class="radio">
                    <label><input type="radio" name="controlthread" value="5">删除主题</label>
                </div>
                @else
                <div class="radio">
                    <label><input type="radio" name="controlthread" value="6">恢复主题</label>
                </div>
                @endif

                <div class="form-group">
                    <label for="reason"></label>
                    <textarea name="reason"  rows="3" class="form-control" placeholder="请输入处理理由(理由将会公示)"></textarea>
                </div>
                <a href="{{ route('admin.advancedthreadform', $thread) }}">高级管理</a>
                <div class="">
                    <button type="submit" class="btn btn-danger sosad-button btn-md admin-button">确定管理</button>
                </div>
            </form>
        </div>
    </div>
</div>
