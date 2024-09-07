<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Datetime;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('posts')->insert([
            [
                'user_id' => 2,
                'category_id' => 7,
                'title' => '虹の実',
                'body' => '気温や湿度によって七色に味を変え、口にすると体内で味が次々に変化し、食べたものに大きな感動を与える。
                           25メートルプールの水に果汁をたった1滴たらすだけでプールの水全てが芳醇なジュースに変わるほどの高い果汁濃度を
                           持ち、蒸発した果汁は空気中に虹を作る。',
                'address' => '第8ビオトープ',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            
            [
                'user_id' => 2,
                'category_id' =>2,
                'title' => 'センチュリースープ',
                'body' => 'アイスヘルの中央にそびえるグルメショーウインドーから溶け出した天然のスープ。
                           オーロラが立ち昇るほどに無数の食材の旨味が凝縮されており、極めて濃厚な味でありながら喉越しはしつこくなく、
                           飲むと美味しすぎて顔がにやけてしまう。',
                'address' => 'アイスヘル',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            
            [
                'user_id' => 2,
                'category_id' =>1,
                'title' => 'BBコーン',
                'body' => 'たった1粒を強力な火力で煎って爆発させれば、たちまち100人前のポップコーンに変身。
                           圧倒的な香ばしさとコクがあり、一口食べたら食べるのを止められなくなるほどの食欲増進効果に優れている。',
                'address' => 'ウージャングル',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
            ]);
    }
}
