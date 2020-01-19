<?php

use Illuminate\Database\Seeder;
use App\Models\Quote;

class QuotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quotes1 = factory(Quote::class)->times(20)->create();
        $quotes2 = factory(Quote::class)->times(20)->create([
            'approved' => false,
        ]);
    }
}
