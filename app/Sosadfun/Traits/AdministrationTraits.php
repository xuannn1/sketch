<?php
namespace App\Sosadfun\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


use Auth;

trait AdministrationTraits{
    public function findAdminRecords($id, $paginate)
    {
        $query = DB::table('administrations')
        ->join('users','administrations.user_id','=','users.id')
        ->leftjoin('threads',function($join)
        {
            $join->whereIn('administrations.operation',[1,2,3,4,5,6,9,15,16,40,41,42,43,44,45]);
            $join->on('administrations.item_id','=','threads.id');
        })
        ->leftjoin('posts',function($join)
        {
            $join->whereIn('administrations.operation',[7,10,11,12,30]);
            $join->on('administrations.item_id','=','posts.id');
        })
        ->leftjoin('post_comments',function($join)
        {
            $join->whereIn('administrations.operation',[8,31]);
            $join->on('administrations.item_id','=','post_comments.id');
        })
        ->leftjoin('statuses',function($join)
        {
            $join->where('administrations.operation','=',17);
            $join->on('administrations.item_id','=','statuses.id');
        })
        ->leftjoin('users as operated_users',function($join)
        {
            $join->whereIn('administrations.operation',[13,14,18,19,20,30,31,50]);
            $join->on('administrations.administratee_id','=','operated_users.id');
        });
        if ($id>0){
            $query->where('administrations.administratee_id','=',$id);
        }
        $records = $query->where('administrations.deleted_at','=',null)
        ->select('users.name','administrations.*','threads.title as thread_title','posts.body as post_body','post_comments.body as postcomment_body', 'statuses.content as status_body', 'operated_users.name as operated_users_name' )
        ->orderBy('administrations.created_at','desc')
        ->paginate($paginate);

        return $records;
    }

}
