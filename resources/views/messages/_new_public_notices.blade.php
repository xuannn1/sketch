<div class="">
    <a href="{{route('message.public_notice')}}" class="font-5">
        {{ConstantObjects::system_variable()->latest_public_notice_id-$user->public_notice_id}}条新的公共通知&nbsp;&nbsp;>>全部公共通知
    </a>
</div>
