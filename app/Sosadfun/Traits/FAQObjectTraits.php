<?php
namespace App\Sosadfun\Traits;

use Cache;
use DB;

trait FAQObjectTraits{


    public function all_faqs()
    {
        return Cache::remember('all_faqs', 30, function () {
            return \App\Models\Helpfaq::all();
        });
    }

    public function clear_all_faqs()
    {
        Cache::forget('all_faqs');
        Cache::forget('find_faqs');
    }

    public function find_faqs()
    {
        return Cache::remember('find_faqs', 10, function () {
            $total_faq =[];
            foreach(config('faq') as $key1=>$value1)
            {
                foreach($value1['children'] as $key2 => $value2)
                {
                    $combokey = $key1.'-'.$key2;
                    $faqs = self::all_faqs()->where('key',$combokey);
                    $total_faq[$combokey]=$faqs;
                }
            }
            return $total_faq;
        });
    }

}
