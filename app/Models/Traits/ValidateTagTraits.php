<?php
namespace App\Models\Traits;

use ConstantObjects;

trait ValidateTagTraits{


    public function thread_validate_tag($tags)//检查由用户提交的tags组合，是否符合基本要求 $tags is an array [1,2,3]...
    {
        $valid_tags = [];//通过检查的tag
        $limit_count_tags = [];//tag数量限制
        $only_one_tags = [];//只能选一个的tag
        foreach($tags as $key => $value){
            $tag = ConstantObjects::find_tag_by_id($value);
            if($tag){//首先应该判断这个tag是否存在，否则会报错Trying to get property 'tag_type' of non-object
                if (array_key_exists($tag->tag_type,config('tag.types'))){//一个正常录入的tag，它的type应该在config中能够找到。
                    $error = '';
                    //检查是否为非边缘文章提交了边缘标签
                    if((!$this->is_bianyuan) && $tag->is_bianyuan){
                        $error = 'bianyuan violation';
                    }
                    //如不属于某channel却选择了专属于某channel的tag,如为非同人thread选择了同人channel的tag
                    if(($tag->channel_id>0)&&( $tag->channel_id != $this->channel_id)){
                        $error = 'channel violation';
                    }

                    //检查是否满足某些类tag只能选一个的限制情况，
                    if (array_key_exists($tag->tag_type, config('tag.limits.only_one'))){
                        if(array_key_exists($tag->tag_type, $only_one_tags)){
                            $error = 'only one tag violation';
                        }else{
                            $only_one_tags[$tag->tag_type] = $tag->id;
                        }
                    }

                    //检查数目限制的那些是否满足要求， sum_limit < sum_limit_count
                    if (array_key_exists($tag->tag_type,config('tag.limits.sum_limit'))){
                        if(!empty($limit_count_tags)&&(count($limit_count_tags)>config('tag.sum_limit_count'))){
                            $error = 'too many tags in total';
                        }else{
                            array_push($limit_count_tags,$tag->id);
                        }
                    }

                    //如果这个tag没有犯上面的任何错误，而且不属于只有编辑才能添加的tag，那么通过检验
                    if((!$tag->user_not_manageable())&&($error==='')){
                        array_push($valid_tags, $tag->id);
                    }else{
                        echo($error.', invalid tag id='.$tag->id."\n");//这个信息应该前端保证它不要出现
                    }
                }
            }
        }//循环结束
        return $valid_tags;
    }
}
