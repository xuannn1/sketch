<?php
namespace App\Sosadfun\Traits;

trait ThreadTraits{

    // public function generate_query_key($request, $group=2){
    //     //$group==2:not-logged; $group>2: logged.
    //
    //     //以后还需要增加对这个结果进行过滤,禁止不合格的query请求
    //     $key = 'threadQuery-UserGroup'.$group
    //     .($request->label? '-Label'.$request->label:'')
    //     .($request->channel? '-Channel'.$request->channel:'')
    //     .($request->book_length? '-Booklength'.$request->book_length:'')
    //     .($request->book_status? '-Bookstatus'.$request->book_status:'')
    //     .($request->sexual_orientation? '-SexualOrientation'.$request->sexual_orientation:'')
    //     .($request->rating? '-Rating'.$request->rating:'')
    //     .($request->orderby? '-Orderby'.$request->orderby:'')
    //     .($request->tags? '-Tags'.$request->tags:'')
    //     .'-P'.($request->page??'1');
    //     return $key;
    // }
}
