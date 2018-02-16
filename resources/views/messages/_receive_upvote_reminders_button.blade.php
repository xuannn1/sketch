<button type="button" class="btn btn-xs btn-primary sosad-button receiveupvotereminders {{Auth::user()->no_upvote_reminders ? '':'hidden'}}" onclick="receiveupvotereminders()">接收点赞提示</button>
<button type="button" class="btn btn-xs btn-danger sosad-button cancelreceiveupvotereminders {{Auth::user()->no_upvote_reminders ? 'hidden':''}}" onclick="cancelreceiveupvotereminders()">取消接收点赞提示</button>
