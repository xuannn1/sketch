<a href="#" data-toggle="modal" data-target="#TriggerAdminControlStatus{{ $status->id }}" class="btn btn-xs btn-danger sosad-button admin-button"><span class="glyphicon glyphicon-user"></span>删除动态</a>
<div class="modal fade" id="TriggerAdminControlStatus{{ $status->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.statusmanagement',$status->id)}}" method="POST">
                {{ csrf_field() }}
                <div class="admin-symbol">
                    <h4>管理员权限专区：警告！请勿进行私人用户操作</h4>
                </div>
                <div class="checkbox">
                    <p class="lead admin-symbol pull-right"><label><input type="checkbox" name="delete">删除动态</label></p>
                </div>
                <div class="form-group">
                    <label for="reason"></label>
                    <textarea name="reason"  rows="3" class="form-control" placeholder="请输入处理理由(理由将会公示)"></textarea>
                </div>
                <div class="">
                    <button type="submit" class="btn btn-danger sosad-button btn-md admin-button">确定管理</button>
                </div>
            </form>
        </div>
    </div>
</div>
