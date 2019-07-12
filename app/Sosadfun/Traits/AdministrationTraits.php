<?php
namespace App\Sosadfun\Traits;

trait AdministrationTraits{
    public function findAdminRecords($id, $page=1)
    {
        return \App\Models\Administration::with('operator')
        ->withAdministratee($id)
        ->latest()
        ->paginate(config('preference.index_per_page'))
        ->appends(['page'=>$page]);
    }
}
