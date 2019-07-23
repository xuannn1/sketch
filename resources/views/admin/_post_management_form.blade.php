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
                    <label><input type="radio" name="controlpost" value="32">回帖折+禁1（回帖折叠，发帖人禁言+一天）</label>
                    <h6 class="grayout">普通不友善</h6>
                </div>

                <div class="radio">
                    <label><input type="radio" name="controlpost" value="39">回帖折+禁3（回帖折叠，发帖人禁言+3天）</label>
                    <h6 class="grayout">版务区不看首楼，非边限帖中谈论关于边限</h6>
                </div>

                <div class="radio">
                    <label><input type="radio" name="controlpost" value="71">回帖折+禁7+清（回帖折叠，发帖人禁言+1天，积分等级清零）</label>
                    <h6 class="grayout">频繁不友善、不看首楼、在作者问题楼里提无关问题</h6>
                </div>

                <div class="radio">
                    <label><input type="radio" name="controlpost" value="34">回帖折+清+封1（回帖折叠，等级清零，发言人禁止登陆1天）</label>
                    <h6 class="grayout">频繁不友善、不看首楼、水区违禁、比较严重的车轱辘（但没有辱骂）</h6>
                </div>

                <div class="radio">
                    <label><input type="radio" name="controlpost" value="35">回帖删+清+封7（回帖删除，等级清零，发言人禁止登陆7天）</label>
                    <h6 class="grayout">辱骂</h6>
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
                    <textarea name="reason"  rows="3" class="form-control" placeholder="请输入处理理由，方便查看管理记录，如“涉及举报，标题简介违规”，“涉及举报，不友善”，“边限标记不合规”。"></textarea>
                </div>
                <div class="">
                    <button type="submit" class="btn btn-danger sosad-button btn-lg sosad-button">确定管理</button>
                    <a href="{{ route('admin.postform', $post->id) }}" class="btn btn-lg btn-danger admin-button pull-right">其他管理（马甲等）</a>
                </div>
            </form>
        </div>
    </div>
</div>
