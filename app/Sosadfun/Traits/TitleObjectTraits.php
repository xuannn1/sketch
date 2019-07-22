<?php
namespace App\Sosadfun\Traits;

use DB;
use Cache;

trait TitleObjectTraits{

    public function default_titles()
    {
        return Cache::remember('default_titles',10,function(){
            return \App\Models\Title::where('id','<=',11)->orderBy('id','asc')->get();
        });
    }

}
