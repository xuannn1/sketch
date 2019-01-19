<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;

Use App\Models\User;
Use App\Models\Chapter;
Use App\Models\Post;
use App\Models\Thread;

class ChapterTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseTransactions;

    /** @test */
    public function login(){
        $response = $this->post('api/login',['email' => 'tester@example.com',
        'password' => 'password']);
        $accessToken = $response->content();
        $strarr = json_decode($accessToken, true);
        $stoke = $strarr['data']['token'];
        $response->assertStatus(200);

    }

    /** @test */
    // 测试新建一个单独的chapter，没有上下章节
    public function createChapter()
    {
        $user = User::find(1);
        $this->be($user);

        $thread = Thread::find(1);
        $data['body'] = "这是一个测试章节，天地蹦出一石猴";

        $request = $this->actingAs($user,'api')
        ->post('api/thread/'.$thread->id.'/chapter',$data);

        $response = $request->send();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    // 测试一系列的章节，相互关联
    public function createChapters()
    {
    	$user = User::find(1);
    	$this->be($user);

    	$thread = Thread::find(1);
    	$data[1] = "第一回 风雪惊变";
    	$data[2] = "第二回 江南七怪";
    	$data[3] = "第三回 大漠风沙";
    	$data[4] = "第四回 黑风双煞";
    	$data[5] = "第五回 弯弓射雕";
    	$data[6] = "第六回 崖顶疑阵";
    	$data[7] = "第七回 比武招亲";

    	$previous_chapter_id = 0;
    	echo $previous_chapter_id;
    	for ($x=1; $x <= 7; $x++){
    		$current_data['body'] = $data[$x];
    		if (!$previous_chapter_id == 0){
    			$current_data['previous_chapter_id'] = $previous_chapter_id;
    		}
    		$request = $this->actingAs($user,'api')->post('api/thread/'.$thread->id.'/chapter',$current_data);
    		$response = $request->send();
    		$this -> assertEquals(200, $response->getStatusCode());
    		// just for test purpose
    		$previous_chapter_id = Post::where('body','=',$data[$x])->orderBy('created_at', 'desc') ->first() ->id;
    	}
    }
}
