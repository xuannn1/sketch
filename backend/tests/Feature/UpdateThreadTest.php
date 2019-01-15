<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Thread;
Use App\Models\User;


class UpdateThreadTest extends TestCase
{


  /** @test */
  public function an_authorised_user_can_update_thread()
  {

    // channle_id = 1,原创
    // channel_id =2, 同人，tags   63,64,
    // 篇幅 : 75,76,77  中篇 短篇  之类
    // 性向: 82，83，84,BG,BL
    // 不能多于3个：66,67,68,69
    // manage: 41,45
    //  $thread = factory(Thread::class,1)->create(); 
      $thread = Thread::find(1);
      $user = User::find($thread->user_id);
      $this->be($user);

      //测试数据1  2，同人 细分为动漫 75中篇  63 64全职高手同人   以下性向82 83 84 85 86
       // $request = $this->actingAs($user,'api')
       // ->put('api/thread/'.$thread->id,
       // ['is_bianyuan' => true,
       // 'channel' => 2,
       // 'tags' =>[2,75,63,64,83]
       //  ]);

    //测试数据2  1,原创 77长篇 80 完结  性向85
        $request = $this->actingAs($user,'api')
        ->put('api/thread/'.$thread->id,
        ['is_bianyuan' => false,
        'channel' => 1,
        'tags' =>[77,80,85]
         ]);

      $response = $request->send();

      return dd($thread);
      $this->assertEquals(200, $response->getStatusCode());



  }
}
