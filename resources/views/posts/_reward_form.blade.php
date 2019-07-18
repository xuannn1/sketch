<div class="modal fade" id="TriggerPostReward{{ $post->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('reward.store')}}" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <div class="">
                        <label><input type="radio" name="reward_type" value="salt">盐粒(余额{{$info->salt}})</label>
                    </div>
                    <div class="">
                        <label><input type="radio" name="reward_type" value="fish" checked>咸鱼(余额{{$info->fish}})</label>
                    </div>
                    <div class="">
                        <label><input type="radio" name="reward_type" value="ham">火腿(余额{{$info->ham}})</label>
                    </div>
                    <hr>
                    <div class="">
                        <label><input type="text" style="width: 40px" name="reward_value" value="1">数额(1-100)</label>
                    </div>
                    <hr>
                    <label><input name="rewardable_type" value="post" class="hidden"></label>
                    <label><input name="rewardable_id" value="{{$post->id}}" class="hidden"></label>
                    <div class="text-right">
                        <button type="submit" class="btn btn-lg btn-primary sosad-button">打赏</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
