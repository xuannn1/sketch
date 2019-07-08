<?php

namespace App\Sosadfun\Traits;

use Carbon\Carbon;
use Auth;

trait FindModelTrait
{
	public function findModel($model,$id,$array){
		if(!empty($model)&&!empty($id)&&in_array($model, $array)){
			$model = 'App\Models\\'.ucwords($model);
        	return $model::where('id',$id)->first();
        }
		return false;
	}

}
