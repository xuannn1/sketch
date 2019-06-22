<a href="#" data-toggle="modal" data-target="#TriggerAdminControlPostComment{{ $postcomment->id }}" class="btn btn-xs btn-danger sosad-button admin-button"><span class="glyphicon glyphicon-user"></span>管理点评</a>
<div class="modal fade" id="TriggerAdminControlPostComment{{ $postcomment->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.postcommentmanagement',$postcomment->id)}}" method="POST">
                {{ csrf_field() }}
                <div class="admin-symbol">
                    <h4>管理员权限专区：警告！请勿进行私人用户操作</h4>
                </div>
                <div class="radio">
                    <label><input type="radio" name="controlpostcomment" value="8">{{ $postcomment->deleted_at ? '解除删除' : '删除点评' }}</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="controlpostcomment" value="10" onclick="document.getElementById('majiaforpostcomment{{$postcomment->id}}').style.display = 'block'">修改马甲？</label>
                    <div class="form-group text-right" id="majiaforpostcomment{{$postcomment->id}}" style="display:none">
                        <label><input type="radio" name="anonymous" value="1" {{ $postcomment->anonymous ? 'checked':'' }}>披上马甲</label>
                        <label><input type="radio" name="anonymous" value="2" {{ $postcomment->anonymous ? '':'checked' }}>揭下马甲</label>
                        <input type="text" name="majia" class="form-control" value="{{$postcomment->majia ?:'匿名咸鱼'}}">
                    </div>
                </div>
                <div class="radio">
                    <label><input type="radio" name="controlpostcomment" value="31">零级小号点评套餐（积分等级清零，回帖折叠，发帖人禁言一天）</label>
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
