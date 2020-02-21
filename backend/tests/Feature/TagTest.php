<?php

namespace Tests\Feature;

use Tests\TestCase;

class TagTest extends TestCase
{
    /** @test */
    public function a_guest_can_not_view_all_tags() //游客不能查看所有标签
    {
        $this->get('/api/tag')->assertStatus(401);
    }

    /** @test */
    public function an_authorised_user_can_view_all_tags() //已登陆用户可以查看所有标签
    {
        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');

        $this->get('/api/tag')
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'book_length_tags',
                    'book_status_tags',
                    'sexual_orientation_tags',
                    'editor_tags',
                    'book_public_custom_Tags',
                    'tongren_primary_tags',
                    'tongren_yuanzhu_tags'
                ]
            ]);
    }

    /** @test */
    public function a_guest_can_not_view_a_single_tag() //游客不可以查看标签详情
    {
        $this->get('/api/tag/1')->assertStatus(401);
    }

    /** @test */
    public function an_authorised_user_can_view_a_single_tag() //已登陆用户可以查看标签详情
    {
        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');
        $tag = factory('App\Models\Tag')->create();

        $this->get("/api/tag/{$tag->id}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'tag_name',
                        'tag_explanation',
                        'tag_type',
                        'is_bianyuan',
                        'is_primary',
                        'channel_id',
                        'parent_id',
                        'book_count'
                    ]
                ]
            ])
            ->assertJson([
                'code' => 200,
                'data' => [
                    'type' => 'tag',
                    'id' => $tag->id,
                    'attributes' => [
                        'tag_name' => $tag->tag_name,
                        'tag_explanation' => $tag->tag_explanation,
                        'tag_type' => $tag->tag_type,
                        'is_bianyuan' => $tag->is_bianyuan,
                        'is_primary' => $tag->is_primary,
                        'channel_id' => $tag->channel_id,
                        'parent_id' => $tag->parent_id,
                        'book_count' => $tag->book_count
                    ]
                ]
            ]);
    }

    /** @test */
    public function unauthorized_user_cannot_create_tag() //游客、普通用户不可以创建标签
    {
        $tag = factory('App\Models\Tag')->raw();
        // 未登陆
        $this->post('/api/tag', $tag)->assertStatus(401);

        $user = factory('App\Models\User')->create();
        // 权限不足
        $this->actingAs($user, 'api')->post('/api/tag', $tag)->assertStatus(403);
    }

    /** @test */
    public function admin_can_create_a_new_tag() //管理员可以创建新标签
    {
        $admin = factory('App\Models\User')->create();
        $admin->role = 'admin';
        $this->actingAs($admin, 'api');

        $tag = factory('App\Models\Tag')->raw();

        $this->post('/api/tag', $tag)
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'tag_name',
                        'tag_explanation',
                        'tag_type',
                        'is_bianyuan',
                        'is_primary',
                        'channel_id',
                        'parent_id',
                        'book_count'
                    ]
                ]
            ])
            ->assertJson([
                'code' => 200,
                'data' => [
                    'type' => 'tag',
                    'attributes' => $tag
                ]
            ]);

        $this->assertDatabaseHas('tags', $tag);
    }

    /** @test */
    public function unauthorized_user_cannot_update_tag() //游客、普通用户不可以修改标签
    {
        $old_tag = factory('App\Models\Tag')->create();
        $new_tag = factory('App\Models\Tag')->raw();
        // 未登陆
        $this->patch("/api/tag/{$old_tag->id}", $new_tag)->assertStatus(401);

        $user = factory('App\Models\User')->create();
        // // 权限不足
        $this->actingAs($user, 'api')->patch("/api/tag/{$old_tag->id}", $new_tag)->assertStatus(403);
    }

    /** @test */
    public function admin_can_update_a_tag() //管理员可以修改标签
    {
        $admin = factory('App\Models\User')->create();
        $admin->role = 'admin';
        $this->actingAs($admin, 'api');

        $old_tag = factory('App\Models\Tag')->create();
        $new_tag = factory('App\Models\Tag')->raw();

        $this->assertDatabaseHas('tags', $old_tag->toArray());

        $this->patch("/api/tag/{$old_tag->id}", $new_tag)
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'tag_name',
                        'tag_explanation',
                        'tag_type',
                        'is_bianyuan',
                        'is_primary',
                        'channel_id',
                        'parent_id',
                        'book_count'
                    ]
                ]
            ]);

        $this->get("/api/tag/{$old_tag->id}")->assertJson([
            'code' => 200,
            'data' => [
                'type' => 'tag',
                'id' => $old_tag->id,
                'attributes' => $new_tag
            ]
        ]);

        $this->assertDatabaseMissing('tags', $old_tag->toArray());
        $this->assertDatabaseHas('tags', $new_tag);
    }

    /** @test */
    public function unauthorized_user_cannot_delete_tag() //游客、普通用户不可以删除标签
    {
        $tag = factory('App\Models\Tag')->create();
        // 未登陆
        $this->delete("/api/tag/{$tag->id}")->assertStatus(401);

        $user = factory('App\Models\User')->create();
        // 权限不足
        $this->actingAs($user, 'api')->delete("/api/tag/{$tag->id}")->assertStatus(403);
    }

    /** @test */
    public function admin_can_delete_a_tag() //管理员可以删除标签
    {
        $this->withoutExceptionHandling();
        $admin = factory('App\Models\User')->create();
        $admin->role = 'admin';
        $this->actingAs($admin, 'api');

        $tag = factory('App\Models\Tag')->create();
        $this->assertDatabaseHas('tags', $tag->toArray());

        $this->delete("/api/tag/{$tag->id}")->assertStatus(200);

        $this->assertSoftDeleted('tags', $tag->toArray());
    }

    /** @test */
    public function thread_tag_relationship_will_be_deleted_when_a_tag_is_deleted() //删除tag的同时，会删除与tag相关联的thread的关联记录
    {
        $this->withoutExceptionHandling();
        // 创建一个关联了两个tag的thread
        $channel_id = 6;
        $user = factory('App\Models\User')->create([
            'level' => 5,
            'quiz_level' => 3,
        ]);

        $tag1 = factory('App\Models\Tag')->create(['channel_id' => $channel_id]);
        $tag2 = factory('App\Models\Tag')->create(['channel_id' => $channel_id]);

        $data = [
            'channel_id' => $channel_id,
            'title' => 'test_thread1',
            'brief' => 'brief1',
            'body' => 'body' . $this->faker->paragraph,
            'tags' => [$tag1->id, $tag2->id],
        ];
        $this->actingAs($user, 'api')
            ->post('/api/thread', $data);

        $thread = \App\Models\Thread::latest()->first();

        $this->assertCount(2, $thread->tags);
        // 删除其中一个tag后，对应的tag_thread记录也被删除了
        $admin = factory('App\Models\User')->create();
        $admin->role = 'admin';
        $this->actingAs($admin, 'api');

        $this->delete("/api/tag/{$tag1->id}")->assertStatus(200);

        $this->assertCount(1, $thread->fresh()->tags);
        $this->assertSoftDeleted('tags', $tag1->toArray());
    }
}
