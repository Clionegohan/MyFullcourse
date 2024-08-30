<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class UpdateCategoriesNameJpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'starter' => 'オードブル(前菜)',
            'soup' => 'スープ',
            'fish' => '魚料理',
            'meat' => '肉料理',
            'main' => '主菜(メイン)',
            'salad' => 'サラダ',
            'dessert' => 'デザート',
            'drink' => 'ドリンク',
            ];
            
            foreach ($categories as $name => $name_jp) {
                Category::where('name', $name)->update(['name_jp' => $name_jp]);
            }
    }
}
