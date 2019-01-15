<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ConstantObjects;
use DB;

class UpdateThread extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      $thread = request()->route('thread');
      $channel = $thread->channel;
      return ((auth('api')->user()->canManageChannel($thread->channel_id))||((auth('api')->id() === $thread->user_id)&&(!$thread->locked)&&($channel->allow_edit)));

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      return [
          'channel' => 'required|numeric'
      ];
    }

    public function updateThread($thread){

        $tags = $this->tags;
        $bianyuan=$this->is_bianyuan;
        $channelid = $this->channel;
        $tags_data = [];
        $tags_data = $this->tags_validate($tags,$bianyuan,$channelid,$thread);
        $thread['is_bianyuan']=$this->is_bianyuan ? true:false;
        $thread['user_id'] = auth('api')->id();
        $thread['channel_id']=$channelid;  //

         $thread = DB::transaction(function () use($thread,$tags_data) {
             $thread->save();
             $thread->tags()->sync($tags_data);
             return $thread;
         });

        return $thread;

      }

    public function tags_validate($tags,$bianyuan,$channel_id,$thread)
    {

       $sum_limit_count =config('tag.limits.sum_limit_count');
       $limit_count_tags= [];  //记录数目限制的那些
       $thread_data = [];  //记录所有合法tag
       $tongren_tags = [];  //记录同人tag,parent_id>0
       $onlyone_tags = []; //检查是否满足某些类tag只能选一个的限制情况


       //查看tag数目是否符合要求，是否存在边缘tag但是没注明
       foreach($tags as $key => $value){

        $tag = ConstantObjects::allTags()->keyBy('id')->get($value);

        //  1）检查是否满足某些类tag只能选一个的限制情况，
        //篇幅,性向,进度,同人原著,同人CP,结局
        if (array_key_exists($tag->tag_type,config('tag.limits.only_one'))){
           if(array_key_exists($tag->tag_type,$onlyone_tags)){
              abort(422);
           }else{
             $onlyone_tags[$tag->tag_type] = $tag->id;
           }
        }

        // 2）检查数目限制的那些是否满足要求， sum_limit < sum_limit_count
        //故事气氛,整体时代,强弱关系,,,,<3  todo
        if (array_key_exists($tag->tag_type,config('tag.limits.sum_limit'))){
           if(!empty($limit_count_tags)&&(count($limit_count_tags)>$sum_limit_count)){
                abort(422);
           }else{
              $limit_count_tags[$tag->id]=$tag->id;
           }
        }



        // 3. 非大类的tag，检查有没有tag和其他信息的冲突
        // 3.1非边限选边限tag，
        //3.2 如不属于某channel却选择了专属于某channel的tag,如非同人选择了同人
        if (!array_key_exists($tag->tag_type,config('tag.types'))){
            if((!$bianyuan) && $tag->is_bianyuan){
                abort(422);
            }
            if( $tag->channel_id !== $channel_id){
                abort(422);
            }
        }

          //3,保存同人信息
          //用于同人CP寻找同人原著，同人原著寻找同人作品其他分类
          if($tag->channel_id ===  2 && $tag->parent_id > 0 )
          {
            $tongren_tags[$tag->id] = $tag->parent_id;
          }

           //4）检查是否出现了用户提交不应由普通用户添加的tag (这类tag直接剥离)
            if(!$tag->user_not_manageable()){
              $thread_tag[$tag->id]=$tag->id;
            }

          }  //循环结束


          //用于同人CP寻找同人原著，同人原著寻找同人作品其他分类
          //同人CP找不到对应的同人原著 同人原著找不到对应的其他同人作品分类 报错
            if(!empty($tongren_tags)){
              foreach($tongren_tags as $child => $parent){
                if($parent>0 && !array_key_exists($parent,$thread_tag)){
                      abort(422);

                }
              }
            }


          //5,与原threads 管理员加的标签合并
        //  $admin_tags = $thread->tags()->whereIn('tag_type',config('tag.limits.user_not_manageable'));
         $manageabletags = config('tag.limits.user_not_manageable');
         $admin_tags = $thread->tags()->whereIn('tag_type',['编推','管理'])->get();

        if(!empty($admin_tags)&&($admin_tags->count()>0)){
          foreach($admin_tags as $admin_tag){
            $thread_tag[$admin_tag->id] = $admin_tag->id;
          }
        }


         return $thread_tag;

    }
}
