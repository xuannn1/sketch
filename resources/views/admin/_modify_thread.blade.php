<div>
    <a href="#" data-toggle="modal" data-target="#TriggerAdminThread{{ $thread->id }}" class="btn btn-lg btn-danger sosad-button admin-button"><span class="glyphicon glyphicon-user"></span>管理本帖</a>
</div>

<div class="modal fade" id="TriggerAdminThread{{ $thread->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content h4">
            <form action="{{ route('admin.threadmanagement',$thread->id)}}" method="POST">
                {{ csrf_field() }}
                <div class="admin-symbol">
                    <h2>管理员权限专区：警告！请勿进行私人用户操作！</h2>
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

                <div class="radio">
                    <label><input type="radio" name="controlthread" value="40">帖子上浮（顶帖）</label>
                </div>

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
                    <textarea name="reason"  rows="3" class="form-control" placeholder="请输入处理理由(理由将会公示)，以及处理参数（如禁言时间，精华时间）。"></textarea>
                </div>
                
                <div class="">
                    <button type="submit" class="btn btn-danger sosad-button btn-md admin-button">确定管理</button>
                </div>
            </form>
        </div>
    </div>
</div>
