<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Carbon\Carbon;
use App\Helpers\StringProcess;
use App\Helpers\ConstantObjects;

Use App\Models\User;
Use App\Models\Chapter;
Use App\Models\Post;
use App\Models\Thread;

use DB;

class ChapterTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseTransactions;

    public function isDuplicateThread($thread)
    {
        $last_thread = Thread::where('user_id', auth('api')->id())
        ->orderBy('created_at', 'desc')
        ->first();
        return (!empty($last_thread)) && (strcmp($last_thread->title.$last_thread->brief.$last_thread->body, $thread['title'].$thread['brief'].$thread['body']) === 0);
    }

    private function createThread(){

        $channel = ConstantObjects::allChannels()->keyBy('id')->get(1);
        //检查tag是否符合规则
        //这部分还没做
        $thread['title'] = '每次都要新建一个thread';
        $thread['brief'] = '然而用完了就得删';
        $thread['body'] = '不知道为什么觉得这个举动特别渣';
        //处理标题
        $thread['title'] = StringProcess::convert_to_public($thread['title']);
        //假如经过去敏感词，标题竟然为空，返回违禁信息
        if (empty($thread['title'])){
            abort(488);
        }
        //处理简介、正文，正文自动去除段首空格
        $thread['brief'] = StringProcess::convert_to_public($thread['brief']);
        $thread['body'] = StringProcess::trimSpaces($thread['body']);
        //增加其他的变量
        $thread['channel_id']=$channel->id;
        //将boolean值赋予提交的设置
        $thread['is_anonymous']=0;
    
        $thread['no_reply']=false;
        $thread['use_markdown']=false;
        $thread['use_indentation']=false;
        $thread['is_bianyuan']=false;
        $thread['last_responded_at']=Carbon::now();
        $thread['user_id'] = 1;

        if (!$this->isDuplicateThread($thread)){
            $thread = DB::transaction(function () use($thread) {
                $thread = Thread::create($thread);
                //如果是homework，注册相关信息
                //这里还需要记录奖励历史信息
                return $thread;
            });
        }
        return $thread;
    }
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
        // create thread first 
        $thread = $this->createThread();
        $data['body'] = "这是一个测试章节，天地蹦出一石猴";

        $request = $this->actingAs($user,'api')
        ->post('api/thread/'.$thread->id.'/chapter',$data);

        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    // 测试重复提交
    public function createDuplicateChapter()
    {
    	$user = User::find(1);
        $this->be($user);

        $thread = $this->createThread();
        $data['body'] = "这是一个测试章节，天地蹦出一石猴";

        $request = $this->actingAs($user,'api')
        ->post('api/thread/'.$thread->id.'/chapter',$data);

        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode());

        $request = $this->actingAs($user,'api')
        ->post('api/thread/'.$thread->id.'/chapter',$data);
        $response = $request->send();
        $this->assertEquals(409, $response->getStatusCode());
    }

    /** @test */
    // 测试invalidate chapter connection
    // 情况一： 所选的前一个chapter不存在
    public function invalidChapterConn()
    {
    	$user = User::find(1);
    	$this->be($user);

    	$thread = $this->createThread();
    	$data['body'] = "反正不会被存进数据库随他吧";
    	$data['previous_chapter_id'] = 100000;

    	$request = $this->actingAs($user,'api')->post('api/thread/'.$thread->id.'/chapter',$data);
    	$response = $request->send();
    	$this -> assertEquals(595, $response->getStatusCode());

    	$data['body'] = "一个合格的下一章";
    	$data['previous_chapter_id'] = 1;

    	$request = $this->actingAs($user,'api')->post('api/thread/'.$thread->id.'/chapter',$data);
    	$response = $request->send();
    	$this -> assertEquals(200, $response->getStatusCode());

    	$data['body'] = "一个不合格的下一章，上一章已经有下一章啦";
    	$data['previous_chapter_id'] = 1;

    	$request = $this->actingAs($user,'api')->post('api/thread/'.$thread->id.'/chapter',$data);
    	$response = $request->send();
    	$this -> assertEquals(595, $response->getStatusCode());

    }

    /** @test */
    // 测试一系列的章节，相互关联
    public function createChapters()
    {
    	$user = User::find(1);
    	$this->be($user);

    	$thread = $this->createThread();
    	$data[1] = "第一回 风雪惊变";
    	$data[2] = "第二回 江南七怪";
    	$data[3] = "第三回 大漠风沙";
    	$data[4] = "第四回 黑风双煞";
    	$data[5] = "第五回 弯弓射雕";
    	$data[6] = "第六回 崖顶疑阵";
    	$data[7] = "第七回 比武招亲";

    	$previous_chapter_id = 0;
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

    /** @test */
    // 测试章节内容更新
    public function updateChapter()
    {
    	// create a chapter first
    	$user = User::find(1);
        $this->be($user);

        $thread = $this->createThread();
        $data['body'] = "这是一个测试章节，太太说她怀胎十月然后……";

        $request = $this->actingAs($user,'api')
        ->post('api/thread/'.$thread->id.'/chapter',$data);

        $response = $request->send();

        $this->assertEquals(200, $response->getStatusCode());

        # update
        $post_id = Post::where('body','=',$data['body'])->first()->id;
        $data['body'] = "……然后生出来啦！！";
        $request = $this->actingAs($user,'api')
        ->put('api/thread/'.$thread->id.'/chapter/'.$post_id,$data);

        $response = $request->send();
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    // 测试更新post存在但是chapter不存在的情况
    public function updateinvalidChapter()
    {
    	$user = User::find(1);
        $this->be($user);

        $thread = $this->createThread();
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
