<?php

use Illuminate\Database\Seeder;
use App\Models\Recommendation;

class RecommendationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['long','short','topic'];
        $recommendations = factory(Recommendation::class)->times(20)->create([
            'is_public' => true,
        ]);
        foreach($recommendations as $recommendation){
            $users = \App\Models\User::inRandomOrder()->take(2)->pluck('id')->toArray();
            $recommendation->users()->sync($users);
        }
    }
}
