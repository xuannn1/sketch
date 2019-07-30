@foreach ($invitation_tokens as $invitation_token)
<div class="row h5">
    <div class="{{ $invitation_token->invite_until<Carbon::now()||$invitation_token->invitation_times<=0 ?'grayout':''}}">
        <span>邀请码内容：{{ $invitation_token->token }}</span>
        <span>邀请人：
            <a href="{{ route('user.show', $invitation_token->user_id) }}">{{ $invitation_token->user->name }}</a>
        </span>
        <span>
            已邀请：{{ $invitation_token->invited }}；剩余次数：{{ $invitation_token->invitation_times }}
        </span>
        <span>
            创建时间：{{ Carbon::parse($invitation_token->created_at)->setTimezone('Asia/Shanghai') }}
        </span>
        <span>
            失效时间：{{ Carbon::parse($invitation_token->invite_until)->setTimezone('Asia/Shanghai') }}
        </span>
    </div>
</div>
<hr>
@endforeach
