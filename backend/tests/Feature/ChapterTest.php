<?php

namespace Tests\Feature;

use Tests\TestCase;

class ChapterTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */

    private function createThread($user){

        $thread = factory('App\Models\Thread')->create([
            'channel_id' => 1,
            'user_id' => $user->id,
        ]);
        return $thread;
    }

    /** @test */
    // 测试新建一个单独的chapter，没有上下章节
    public function thread_owner_can_add_new_chapter()
    {
        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');

        // create thread first
        $thread = $this->createThread($user);

        $data = [
            'title' => 'chapter1',
            'brief' => 'brief1',
            'body' => '这是一个测试章节，天地蹦出一石猴.',
        ];

        $response = $this->post('api/thread/'.$thread->id.'/chapter', $data)
        ->assertStatus(200);
    }

    /** @test */
    // 测试重复提交
    public function thread_owner_can_not_create_duplicate_chapter()
    {
    	$user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');

        // create thread first
        $thread = $this->createThread($user);

        $data = [
            'title' => 'chapter1',
            'brief' => 'brief1',
            'body' => '这是一个测试章节，天地蹦出一石猴.',
        ];

        $response = $this->post('api/thread/'.$thread->id.'/chapter',$data)
        ->assertStatus(200);

        $response = $this->post('api/thread/'.$thread->id.'/chapter',$data)
        ->assertStatus(409);
    }

    /** @test */
    // 测试一系列的章节，相互关联
    public function thread_owner_can_create_a_series_of_chapters()
    {
        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');

        // create thread first
        $thread = $this->createThread($user);

        $data = [
            'title' => 'chapter1',
            'brief' => 'brief1',
            'body' => '这是一个测试章节，天地蹦出一石猴.1',
        ];

        $response = $this->post('api/thread/'.$thread->id.'/chapter',$data)
        ->assertStatus(200);

        $data = [
            'title' => 'chapter2',
            'brief' => 'brief2',
            'body' => '这是一个测试章节，天地蹦出一石猴.2',
        ];

        $response = $this->post('api/thread/'.$thread->id.'/chapter',$data)
        ->assertStatus(200);
        //这里需要增加测试，是否把这两个章节关联上了
    }

    /** @test */
    // 测试章节内容更新
    public function thread_owner_can_update_own_chapter()
    {
        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');

        // create thread first
        $thread = $this->createThread($user);

        $data = [
            'title' => 'chapter1',
            'brief' => 'brief1',
            'body' => '这是一个测试章节，天地蹦出一石猴.',
        ];

        $response = $this->post('api/thread/'.$thread->id.'/chapter', $data)
        ->assertStatus(200);

        $content = $response->decodeResponseJson();

        $data = [
            'title' => 'modifiedchapt',
            'brief' => 'modifiedchapt',
            'body' => 'modifiedchapt',
        ];

        $response = $this->put('api/thread/'.$thread->id.'/chapter/'.$content['data']['id'], $data)

        ->assertStatus(200);
    }


    // 测试更新post存在但是chapter不存在的情况
    public function can_not_update_invalid_chapter()
    {
    	$user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');

        // create thread first
        $thread = $this->createThread($user);
        $data['body'] = "这是一个测试章节，ummmm反正它不会被存进数据库里不然就出问题了！！！";

        # post doesn't exist
        $request = $this->actingAs($user,'api')
        ->put('api/thread/'.$thread->id.'/chapter/1000000',$data);

        $response = $request->send();

        $this->assertEquals(404, $response->getStatusCode());

        # post exist but is not a chapter
        $request = $this->actingAs($user,'api')
        ->put('api/thread/'.$thread->id.'/chapter/19',$data);

        $response = $request->send();

        $this->assertEquals(404, $response->getStatusCode());
    }
}
