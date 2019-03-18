<?php

namespace Tests\Feature;

use Tests\TestCase;

class FollowerTest extends TestCase
{
    /**
     * test whether one user can follow another 
     * 
     * @return void
     */
    public function test_one_can_follow_another()
    {
        $follower = factory('App\Models\User')->create();

        // assert that the newly created user doesn't follow the other
        $response = $this->get('api/user/'.$follower->id.'/following/')
            ->assertStatus(200)
            //test whether the information returned is complete
            ->assertJsonStructure([
                'code',
                'data' => [
                    'user_id',
                    'followings',
                    'paginate',
                ],
            ])
            ->assertJsonCount(0,'data.followings');

        //created the new followed user
        $followed_user = factory('App\Models\User')->create();
        $this->actingAs($follower,'api');

        // assert that one cannot follow themself
        $this->post('api/user/follow/'.$follower->id)
            ->assertStatus(412);
        // assert one can follow aother
        $this->post('api/user/follow/'.$followed_user->id)
            ->assertStatus(200);
        // check the following list
        $response = $this->get('api/user/'.$follower->id.'/following/')
                         ->assertJson([
                            'code' => 200,
                            'data' => [
                                'user_id' => $follower->id,
                                'followings' => [
                                    [
                                        'id' => $followed_user->id,
                                        'name' => $followed_user->name
                                    ]
                                ]
                            ]]);

        // check the follower list
        $response = $this->get('api/user/'.$followed_user->id.'/follower/')
                 ->assertJson([
                    'code' => 200,
                    'data' => [
                        'user_id' => $followed_user->id,
                        'followers' => [
                            [
                                'id' => $follower->id,
                                'name' => $follower->name
                            ]
                        ]
                    ]]);
    }


    /**
     * test whether one user can unfollow another 
     *
     * @return void
     */
    public function test_one_can_unfollow_another()
    {
        $follower = factory('App\Models\User')->create();
        $followed_user = factory('App\Models\User')->create();
        // assert that the newly created user doesn't have any followers
        $response = $this->get('api/user/'.$follower->id.'/follower/')
            ->assertStatus(200)
            //test whether the information returned is complete
            ->assertJsonStructure([
                'code',
                'data' => [
                    'user_id',
                    'followers',
                    'paginate',
                ],
            ])
            ->assertJsonCount(0,'data.followers');

        //unfollow a not-following user
        $this->actingAs($follower,'api')
                         ->delete('api/user/follow/'.$followed_user->id)
                         ->assertStatus(412);

        //follow
        $this->post('/api/user/follow/'.$followed_user->id)
                         ->assertStatus(200);
        $response = $this->get('api/user/'.$follower->id.'/following/')
                         ->assertJsonCount(1,'data.followings');

        //unfollow
        $this->actingAs($follower,'api')
            ->delete('api/user/follow/'.$followed_user->id)
            ->assertStatus(200);

        $response = $this->get('api/user/'.$follower->id.'/following/')
                         ->assertJsonCount(0,'data.followings');
    }

    /**
     * test whether one user can switch their following notification status 
     *
     * @return void
     */
    public function test_one_can_change_notification_status()
    {
        $follower = factory('App\Models\User')->create();
        $followed_user = factory('App\Models\User')->create();
        $this->actingAs($follower,'api');
        // test one cannot toggle notification for someone they're not following
        $this->patch('api/user/keepNotified/'.$followed_user->id)
            ->assertStatus(412);
        //follow the user
        $this->post('api/user/follow/'.$followed_user->id)
            ->assertStatus(200);
        // test user by default get notification
        $response = $this->get('api/user/follow/'.$followed_user->id)
            ->assertJson([
                'code' => 200,
                'data' => [
                    'id' => $followed_user->id,
                    'keep_notified' => true,
                    'is_notified' => false
                ]]);
        // test one can toggle notification for some
        $this->patch('api/user/keepNotified/'.$followed_user->id)
            ->assertStatus(200);
        $response = $this->get('api/user/follow/'.$followed_user->id)
            ->assertJson([
                'code' => 200,
                'data' => [
                    'id' => $followed_user->id,
                    'keep_notified' => false,
                    'is_notified' => false
                ]]);
    }

}
