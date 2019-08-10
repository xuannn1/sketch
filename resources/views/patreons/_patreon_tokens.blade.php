@foreach ($patreon_tokens as $token)
<div class="row h5">
    <div class="{{ $token->redeem_limit<=0 ?'grayout':''}}">
        <span>福利码内容：{{ $token->token }}</span>
        <span>赞助人：
            <a href="{{ route('user.show', $token->user_id) }}">{{ $token->user->name }}</a>
        </span>
        <span>福利内容：
            {{$token->no_ads? '免广告':''}}&nbsp;{{$token->extra_qiandao_rewards? '签到额外奖励':''}}
        </span>
        <span>
            已发放福利人次：{{ $token->redeem_count }}；剩余福利额度：{{ $token->redeem_limit }}
        </span>
        <span>
            创建时间：{{ Carbon::parse($token->created_at)->setTimezone('Asia/Shanghai') }}
        </span>
    </div>
</div>
<hr>
@endforeach
