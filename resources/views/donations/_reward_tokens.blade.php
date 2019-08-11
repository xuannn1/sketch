@foreach ($tokens as $token)
<div class="row h5">
    <div class="{{ $token->redeem_limit<=0||$token->redeem_until<Carbon::now() ?'grayout':''}}">
        <span>
            福利码：{{ $token->token }}
        </span>
        <span>
            福利内容：{{$token->type==='no_ads'?'永久无广告':''}}{{$token->type==='qiandao+'?'补签卡':''}}
        </span>
        <span>
            已发放福利人次：{{ $token->redeem_count }}；剩余福利额度：{{ $token->redeem_limit }}
        </span>
        <span>
            创建时间：{{ Carbon::parse($token->created_at)->setTimezone('Asia/Shanghai') }}
        </span>
        <span>
            保质期：{{ Carbon::parse($token->redeem_until)->setTimezone('Asia/Shanghai') }}
        </span>
    </div>
</div>
@endforeach
