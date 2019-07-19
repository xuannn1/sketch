<ul class="nav nav-tabs">
    <li role="presentation" class="{{ $show_quote_tab==='all'? 'active':'' }}"><a href="{{ route('quote.index') }}">全站题头</a></li>
    <li role="presentation" class="{{ $show_quote_tab==='mine'? 'active':'' }} pull-right"><a href="{{ route('quote.mine') }}">我的题头</a></li>
</ul>
