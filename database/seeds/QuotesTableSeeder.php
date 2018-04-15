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
      Quote::create([
          'quote' => '新的一天啊，依然没什么长进呢，但是我还没有放弃哟♥',
          'notsad' => true,
          'approved' => true,
          'user_id' => 1,
          'anonymous' => true,
          'majia' => '咸鱼总部',
      ]);

      Quote::create([
          'quote' => '每日一丧',
          'approved' => true,
          'user_id' => 1,
          'anonymous' => true,
          'majia' => '咸鱼总部',
      ]);

      Quote::create([
          'quote' => '丧来丧去',
          'approved' => true,
          'user_id' => 1,
          'anonymous' => true,
          'majia' => '咸鱼总部',
      ]);

      Quote::create([
          'quote' => '咸鱼的一生，光辉灿烂',
          'approved' => true,
          'user_id' => 1,
          'anonymous' => true,
          'majia' => '咸鱼总部',
      ]);

      Quote::create([
          'quote' => '新的一天，不想起床',
          'approved' => true,
          'user_id' => 1,
          'anonymous' => true,
          'majia' => '咸鱼总部',
      ]);

      Quote::create([
          'quote' => '新的一天，只想睡觉',
          'approved' => true,
          'user_id' => 1,
          'anonymous' => true,
          'majia' => '咸鱼总部',
      ]);

      Quote::create([
          'quote' => '新的一天啊，依然没什么长进呢',
          'approved' => true,
          'user_id' => 1,
          'anonymous' => true,
          'majia' => '咸鱼总部',
      ]);

      Quote::create([
          'quote' => '今朝干了这杯丧，来世再做苦命郎……',
          'approved' => true,
          'user_id' => 1,
          'anonymous' => true,
          'majia' => '咸鱼总部',
      ]);

      Quote::create([
          'quote' => '人穷脸丑单身狗，土肥大龄且秃头。一日写文三千字，删完还剩一百九。',
          'approved' => true,
          'user_id' => 1,
          'anonymous' => true,
          'majia' => '咸鱼总部',
      ]);

      Quote::create([
          'quote' => '今天码字两千九，明日摆脱单身狗。不管读者有没有，凑够字数我就走。',
          'approved' => true,
          'user_id' => 1,
          'anonymous' => true,
          'majia' => '咸鱼总部',
      ]);

      Quote::create([
          'quote' => '飞光，飞光，劝君一杯丧',
          'approved' => true,
          'user_id' => 1,
          'anonymous' => true,
          'majia' => '咸鱼总部',
      ]);

      Quote::create([
          'quote' => '新的一天，新的丧',
          'approved' => true,
          'user_id' => 1,
          'anonymous' => true,
          'majia' => '咸鱼总部',
      ]);
      Quote::create([
          'quote' => '新的一天，只想断更',
          'approved' => true,
          'user_id' => 1,
          'anonymous' => true,
          'majia' => '咸鱼总部',
      ]);
      Quote::create([
          'quote' => '新的一天，不想更新',
          'approved' => true,
          'user_id' => 1,
          'anonymous' => true,
          'majia' => '咸鱼总部',
      ]);
      Quote::create([
          'quote' => '无日不丧',
          'approved' => true,
          'user_id' => 1,
          'anonymous' => true,
          'majia' => '咸鱼总部',
      ]);
    }
}
