<div class="">
    <a href="#" data-toggle="modal" data-target="#TriggerAdminUser{{ $user->id }}" class="btn btn-md btn-danger sosad-button admin-button"><span class="glyphicon glyphicon-user"></span>管理该用户</a>
</div>

<div class="modal fade" id="TriggerAdminUser{{ $user->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.usermanagement',$user->id)}}" method="POST">
                {{ csrf_field() }}
                <div class="admin-symbol">
                    <h1>管理员权限专区：警告！请勿进行私人用户操作！</h1>
                </div>
                <div class="radio">
                    <label><input type="radio" name="controluser" value="13">设置禁言时间</label>
                    <label><input type="text" style="width: 40px" name="noposting-days" value="0">天</label>
                    <label><input type="text" style="width: 40px" name="noposting-hours" value="0">小时</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="controluser" value="14">解禁用户</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="controluser" value="18">设置禁止登陆时间</label>
                    <label><input type="text" style="width: 40px" name="nologging-days" value="0">天</label>
                    <label><input type="text" style="width: 40px" name="nologging-hours" value="0">小时</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="controluser" value="19">解除禁止登陆用户</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="controluser" value="20">用户等级积分归零</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="controluser" value="50">分值管理（正加分，负减分，请在<code>管理理由</code>中记录具体分值）</label>
                    <label><input type="text" style="width: 40px" name="jifen" value="0">积分</label>
                    <label><input type="text" style="width: 40px" name="shengfan" value="0">剩饭</label>
                    <label><input type="text" style="width: 40px" name="xianyu" value="0">咸鱼</label>
                    <label><input type="text" style="width: 40px" name="sangdian" value="0">丧点</label>
                    <label><input type="text" style="width: 40px" name="level" value="0">等级</label>
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
