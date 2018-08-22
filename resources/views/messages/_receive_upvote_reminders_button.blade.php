<button type="button" class="btn-xs sosad-button-tag receiveupvotereminders {{Auth::user()->no_upvote_reminders ? '':'hidden'}}" onclick="receiveupvotereminders()">接收赞赏提示</button>
<button type="button" class="btn-xs sosad-button-tag cancelreceiveupvotereminders {{Auth::user()->no_upvote_reminders ? 'hidden':''}}" onclick="cancelreceiveupvotereminders()">取消接收赞赏提示</button>
