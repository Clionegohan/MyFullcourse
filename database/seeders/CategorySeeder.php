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
        
        DB::table('categories')->insert(
            array_map(fn($name) => ['name' =>$name], $categories)
            );
    }
}
