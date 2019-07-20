<!-- 进入筛选模式 -->
<div class="selector">
    <div class="dropdown">
        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">类型<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach($selector['withType'] as $withType => $explanation)
                    <li><a class="" href="{{ route('thread.show', array_merge(['thread'=>$thread->id, 'withType' => $withType], request()->only('withComponent', 'userOnly', 'withReplyTo', 'ordered'))) }}">{{$explanation}}</a></li>
                @endforeach

            </ul>
        </span>
        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">限制<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach($selector['withComponent'] as $withComponent => $explanation)
                    <li><a class="" href="{{ route('thread.show', array_merge(['thread'=>$thread->id, 'withComponent' => $withComponent], request()->only('withType', 'userOnly', 'withReplyTo', 'ordered'))) }}">{{$explanation}}</a></li>
                @endforeach

            </ul>
        </span>
        <span class="button-group">
            <button type="button" class="btn btn-default btn-md dropdown-toggle dropdown-menu-narrow" data-toggle="dropdown">排序<span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach($selector['ordered'] as $ordered => $explanation)
                    <li><a class="" href="{{ route('thread.show', array_merge(['thread'=>$thread->id, 'ordered' => $ordered], request()->only('withType', 'withComponent', 'userOnly', 'withReplyTo'))) }}">{{$explanation}}</a></li>
                @endforeach
            </ul>
        </span>
    </div>
</div>
