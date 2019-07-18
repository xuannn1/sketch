<ul class="nav nav-tabs">
    <div class="row">
        <div class="col-xs-6 text-center">
            <li role="presentation" class="{{ $show_reward_tab=='received'? 'active':'' }}"><a href="{{ route('reward.received') }}">收到的打赏</a></li>
        </div>
        <div class="col-xs-6 text-center">
            <li role="presentation" class="{{ $show_reward_tab=='sent'? 'active':'' }}"><a href="{{ route('reward.sent') }}">给出的打赏</a></li>
        </div>
    </div>
</ul>
