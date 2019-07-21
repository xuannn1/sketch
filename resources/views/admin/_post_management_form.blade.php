<div class="modal fade" id="TriggerPostAdministration{{ $post->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.postmanagement',$post->id)}}" method="POST">
                {{ csrf_field() }}
                <div class="admin-symbol">
                    <h3>管理员权限专区</h3>
                </div>

                @if($post->fold_state===0)
                <div class="radio">
                    <label><input type="radio" name="controlpost" value="11">折叠帖子</label>
                </div>

                <div class="radio">
                    <label><input type="radio" name="controlpost" value="32">回帖折+禁（回帖折叠，发帖人禁言+一天）</label>
                    <h6 class="grayout">比如无意义争执车轱辘、在版务区不看首楼跟帖，在作者问题楼/他人讨论楼里问等级签到问题等情况</h6>
                </div>

                <div class="radio">
                    <label><input type="radio" name="controlpost" value="32">回帖折+禁+清（回帖折叠，发帖人禁言+1天，积分等级清零）</label>
                    <h6 class="grayout">一直一直车轱辘、多次在版务区不看首楼跟帖，多次在作者问题楼/他人讨论楼里问等级签到问题等情况</h6>
                </div>

                <div class="radio">
                    <label><input type="radio" name="controlpost" value="34">回帖折+清+封（回帖折叠，等级清零，发言人禁止登陆1天）</label>
                    <h6 class="grayout">特别屡教不改、置管理于不顾的水区违禁</h6>
                </div>

                <div class="radio">
                    <label><input type="radio" name="controlpost" value="35">回帖删+清+封（回帖删除，等级清零，发言人禁止登陆7天）</label>
                    <h6 class="grayout">辱骂作者，人身攻击</h6>
                </div>

                <div class="radio">
                    <label><input type="radio" name="controlpost" value="36">回帖删+封（回帖删除，等级清零，发言人永久禁止登陆））</label>
                    <h6 class="grayout">全部都是脏话粗话特别不堪入目的人身攻击</h6>
                </div>
                @else
                <div class="radio">
                    <label><input type="radio" name="controlpost" value="12">取消折叠</label>
                </div>
                @endif

                <div class="radio admin-symbol pull-right">
                    <label><input type="radio" name="controlpost" value="7">删除帖子</label>
                </div>

                <div class="form-group">
                    <label for="reason"></label>
                    <textarea name="reason"  rows="3" class="form-control" placeholder="请输入处理理由(理由将会公示)。"></textarea>
                </div>
                <div class="">
                    <button type="submit" class="btn btn-danger sosad-button btn-md admin-button">确定管理</button>
                    <a href="{{ route('admin.postform', $post->id) }}" class="btn btn-md btn-danger admin-button pull-right">高级管理</a>
                </div>
            </form>
        </div>
    </div>
</div>
