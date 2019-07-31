@extends('layouts.default')
@section('title', '文库筛选')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div>
                <form method="POST" action="{{ route('books.interpret_selector') }}"  name="book_filter">
                    {{ csrf_field() }}
                    <a type="button" name="button" class="btn btn-md btn-primary sosad-button-control pull-right" href="{{ route('all.tags') }}">全站标签</a>
                    <div class="selector detailed-selector">
                        <div class="">
                            <span class="font-3">类别筛选：</span>
                            <span class="font-6 grayout">（类别筛选会显示符合全部已勾选的结果，等级较低时一部分筛选项不可见）</span>
                        </div>
                        <div class="h4">
                            <span class="lead">原创性：</span>
                            &nbsp;<label><input type="checkbox" name="channel_id[]" value="1" checked />原创</label>&nbsp;&nbsp;&nbsp;
                            &nbsp;<label><input type="checkbox" name="channel_id[]" value="2" checked />同人</label>&nbsp;&nbsp;&nbsp;
                        </div>

                        <div class="h4">
                            <span class="lead">篇幅：</span>
                            @foreach ($tag_range['book_length_tags'] as $tag)
                            &nbsp;<label ><input type="checkbox" name="book_length_tag[]" value={{ $tag->id }} checked />{{ $tag->tag_name }}</label>&nbsp;&nbsp;&nbsp;
                            @endforeach
                        </div>

                        <div class="h4">
                            <span class="lead">进度：</span>
                            @foreach ($tag_range['book_status_tags'] as $tag)
                            &nbsp;<label ><input type="checkbox" name="book_status_tag[]" value={{ $tag->id }} checked />{{ $tag->tag_name }}</label>&nbsp;&nbsp;&nbsp;
                            @endforeach
                        </div>

                        <div class="h4">
                            <span class="lead">性向：</span>
                            @foreach ($tag_range['sexual_orientation_tags'] as $tag)
                            &nbsp;<label ><input type="checkbox" name="sexual_orientation_tag[]" value={{ $tag->id }} checked />{{ $tag->tag_name }}</label>&nbsp;&nbsp;&nbsp;
                            @endforeach
                        </div>

                        <div class="h4">
                            <span class="lead">限制：</span>
                            &nbsp;<label class="radio-inline"><input type="radio" name="withBianyuan" value="" checked/>非边限</label>&nbsp;&nbsp;&nbsp;

                            @if(Auth::check()&&Auth::user()->level>2)
                            &nbsp;<label class="radio-inline"><input type="radio" name="withBianyuan" value="includeBianyuan"/>包括边限</label>&nbsp;&nbsp;&nbsp;
                            @endif
                        </div>
                        <div class="h4">
                            <span class="lead">排序：</span>
                            @foreach(config('selectors.book_index_filter.ordered') as $ordered => $explanation)
                            &nbsp;<label ><input type="radio" name="ordered" value={{$ordered}} checked />{{$explanation}}</label>&nbsp;&nbsp;&nbsp;
                            @endforeach
                        </div>
                        <hr>
                        <div class="">
                            <a type="button" data-toggle="collapse" data-target="#combo-selection" style="cursor: pointer;" class="font-4">
                                点击显示通用标签复合选择</a>
                            <div class="collapse" id="combo-selection">
                                <div class="h4">
                                    <div class="">
                                        <span class="font-3">通用标签复合选择：</span>
                                        <span class="grayout font-6">（通用标签复合选择会选择含有任意一项以下tag的书籍，为了达到筛选目的，建议不要一次选择太多tag。另，等级较低时一部分筛选项不可见）</span>
                                    </div>
                                    <?php $previous_tag_type = 0; ?>
                                    <div>
                                        @foreach ($tag_range['book_custom_Tags'] as $tag)
                                        @if($previous_tag_type!=$tag->tag_type)
                                        <br><span>{{ $tag->tag_type }}:</span>
                                        @elseif($previous_tag_type===0)
                                        <span>{{ $tag->tag_type }}:</span>
                                        @endif
                                        @if(!$tag->is_bianyuan||Auth::check()&&Auth::user()->level>2)
                                        <label class="">
                                            <input type="checkbox" class="" name="withTag[]" value="{{ $tag->id }}">{{ $tag->tag_name }}&nbsp;&nbsp;
                                        </label>
                                        @endif
                                        <?php $previous_tag_type = $tag->tag_type ?>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="">
                            <a type="button" data-toggle="collapse" data-target="#combo-exclude-selection" style="cursor: pointer;" class="font-4">
                                点击显示通用标签反选</a>
                            <div class="collapse" id="combo-exclude-selection">
                                <div class="h4">
                                    <div class="">
                                        <span class="font-3">通用标签反选：</span>
                                        <span class="grayout font-6">（通用标签反选会显示不含以下任意已勾选标签的结果。）</span>
                                    </div>
                                    <?php $previous_tag_type = 0; ?>
                                    <div class="">
                                        @foreach ($tag_range['book_custom_Tags'] as $tag)
                                        @if($previous_tag_type!=$tag->tag_type)
                                        <br><span>{{ $tag->tag_type }}:</span>
                                        @elseif($previous_tag_type===0)
                                        <span>{{ $tag->tag_type }}:</span>
                                        @endif
                                        <label class="admin-anonymous">
                                            <input type="checkbox" class="" name="excludeTag[]" value="{{ $tag->id }}">{{ $tag->tag_name }}
                                        </label>&nbsp;&nbsp;
                                        <?php $previous_tag_type = $tag->tag_type ?>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="button" class="btn btn-lg btn-primary sosad-button">提交</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
