<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
         $users = factory(User::class)->times(30)->create()->each(function ($user) {
             factory('App\Models\UserInfo')->create(['user_id' => $user->id]);
         });
     }
}
