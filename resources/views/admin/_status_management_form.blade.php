<div class="modal fade" id="TriggerStatusAdministration{{ $status->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.statusmanagement',$status->id)}}" method="POST">
                {{ csrf_field() }}
                <div class="admin-symbol">
                    <h3>管理员权限专区</h3>
                </div>

                @if($status->is_public)
                <div class="radio">
                    <label><input type="radio" name="controlpost" value="61">动态转私密</label>
                    <h6 class="grayout">单条不是百分百确定的边限动态/黄图等</h6>
                </div>

                <div class="radio">
                    <label><input type="radio" name="controlpost" value="63">私+禁（动态转私密，发帖人禁言+一天）</label>
                    <h6 class="grayout">单条确定的边限动态/黄图等</h6>
                </div>

                <div class="radio">
                    <label><input type="radio" name="controlpost" value="64">私+禁+清（动态转私密，发帖人禁言+1天，积分等级清零）</label>
                    <h6 class="grayout">多次比较严重的边限/违规动态</h6>
                </div>

                <div class="radio">
                    <label><input type="radio" name="controlpost" value="66">删+清+封（动态删除，积分等级清零，发言人禁止登陆1天）</label>
                    <h6 class="grayout">违反商业性规定/恋童</h6>
                </div>

                <div class="radio">
                    <label><input type="radio" name="controlpost" value="36">删+封（动态删除，发言人永久禁止登陆）</label>
                    <h6 class="grayout">恶意广告</h6>
                </div>
                @else
                <div class="radio">
                    <label><input type="radio" name="controlpost" value="62">取消私密</label>
                </div>
                @endif

                <div class="radio admin-symbol pull-right">
                    <label><input type="radio" name="controlpost" value="17">动态删除</label>
                </div>

                <div class="form-group">
                    <label for="reason"></label>
                    <textarea name="reason"  rows="3" class="form-control" placeholder="请输入处理理由，方便查看管理记录，如“涉及举报，标题简介违规”，“涉及举报，不友善”，“边限标记不合规”。"></textarea>
                </div>
                <div class="">
                    <button type="submit" class="btn btn-danger sosad-button btn-md admin-button">确定管理</button>
                </div>
            </form>
        </div>
    </div>
</div>
