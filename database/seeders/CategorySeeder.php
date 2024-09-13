<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 英語のカテゴリ名
        $categories = ['starter', 'soup', 'fish', 'meat', 'main', 'salad', 'dessert', 'drink'];

        // 日本語のカテゴリ名
        $categories_jp = ['オードブル(前菜)', 'スープ', '魚料理', '肉料理', '主菜(メイン)', 'サラダ', 'デザート', 'ドリンク'];

        // カテゴリ名と日本語名をまとめて挿入
        DB::table('categories')->insert(
            array_map(function ($name, $name_jp) {
                return ['name' => $name, 'name_jp' => $name_jp];
            }, $categories, $categories_jp)
        );
    }
}