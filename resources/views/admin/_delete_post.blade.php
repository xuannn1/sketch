<span>
   <a href="#" data-toggle="modal" data-target="#TriggerAdminControlPost{{ $post->id }}" class="btn btn-xs btn-danger sosad-button admin-button"><span class="glyphicon glyphicon-user"></span>管理帖子</a>
</span>

<div class="modal fade" id="TriggerAdminControlPost{{ $post->id }}" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
         <form action="{{ route('admin.postmanagement',$post->id)}}" method="POST">
            {{ csrf_field() }}
            <div class="admin-symbol">
               <h4>管理员权限专区：警告！请勿进行私人用户操作</h4>
            </div>
            <div>

            </div>
            <div class="radio">
               <label><input type="radio" name="controlpost" value="7">{{ $post->deleted_at ? '解除删除' : '删除帖子' }}</label>
            </div>
            <div class="radio">
               <label><input type="radio" name="controlpost" value="10" onclick="document.getElementById('majiaforpost{{$post->id}}').style.display = 'block'">修改马甲？</label>
               <div class="form-group text-right" id="majiaforpost{{$post->id}}" style="display:none">
                 <label><input type="radio" name="anonymous" value="1" {{ $post->anonymous ? 'checked':'' }}>披上马甲</label>
                 <label><input type="radio" name="anonymous" value="2" {{ $post->anonymous ? '':'checked' }}>揭下马甲</label>
                 <input type="text" name="majia" class="form-control" value="{{$post->majia ?:'匿名咸鱼'}}">
               </div>
            </div>
            <div class="radio">
               <label><input type="radio" name="controlpost" value="11">{{ $post->fold_state ? '取消折叠' : '折叠帖子' }}</label>
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
