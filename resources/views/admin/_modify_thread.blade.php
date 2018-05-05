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
                <div class="radio">
                    <label><input type="radio" name="controlthread" value="1">{{ $thread->locked ? '解锁' : '锁帖' }}</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="controlthread" value="2">{{ $thread->public ? '转为私密' : '转为公开' }}</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="controlthread" value="5">{{ $thread->bianyuan ? '改为非边缘' : '改为边缘' }}</label>
                </div>
                <div class="radio">
                    <p class="lead admin-symbol pull-right"><label><input type="radio" name="controlthread" value="3">{{ $thread->deleted_at ? '恢复删除' : '删除帖子' }}</label></p>
                </div>
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
