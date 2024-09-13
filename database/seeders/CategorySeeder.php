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
        $categories = ['starter', 'soup', 'fish', 'meat', 'main', 'salad', 'dessert', 'drink'];
        
        $categories_jp = ['オードブル(前菜)', 'スープ', '魚料理', '肉料理', '主菜(メイン)', 'サラダ', 'デザート', 'ドリンク'];
        
        DB::table('categories')->insert(
            array_map(
                fn($name, $name_jp) => ['name' =>$name, 'name_jp' => $name_jp],
                $categories,
                $categories_jp
                )
            );
    }
}
