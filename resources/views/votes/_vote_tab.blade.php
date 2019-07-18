<ul class="nav nav-tabs">
    <div class="row">
        <div class="col-xs-6 text-center">
            <li role="presentation" class="{{ $show_vote_tab=='received'? 'active':'' }}"><a href="{{ route('vote.received') }}">收到的评票</a></li>
        </div>
        <div class="col-xs-6 text-center">
            <li role="presentation" class="{{ $show_vote_tab=='sent'? 'active':'' }}"><a href="{{ route('vote.sent') }}">给出的评票</a></li>
        </div>
    </div>
</ul>
