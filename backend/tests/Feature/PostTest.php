<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Thread;
Use App\Models\User;
Use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{

  /** @test */
  public function login(){
     //$response = $this->json('POST', '/user', ['name' => '学院君']);
    $response = $this->post('api/login',['email' => 'tester@example.com',
    'password' => 'password']);
    $accessToken = $response->content();
    $strarr = json_decode($accessToken, true);
    $stoke = $strarr['data']['token'];
    $response->assertStatus(200);

  }


    /** @test */
      public function a_authorised_user_can_update_post()
      {

        $user = User::find(31);    //$this->be($user);tester

        $response = $this->post('api/login',['email' => 'tester@example.com',
       'password' => 'password']);
        $accessToken = $response->content();
        $strarr = json_decode($accessToken, true);
        $accessToken = $strarr['data']['token'];


        $thread = Thread::find(1);
        $post =  Post::find(1);
        $body = "诗的作者是Dylan Thomas（狄兰·托马斯）诗名是Do not go gentle into that good night, 不要温和地走进那个良夜";

        $request = $this->actingAs($user,'api')
        ->put('api/thread/'.$thread->id.'/post/'.$post->id,
         ['body' => $body]);

        $response = $request->send();

       $this->assertEquals(200, $response->getStatusCode());



      }
}
