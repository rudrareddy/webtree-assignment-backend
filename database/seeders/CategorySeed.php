<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
class CategorySeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $admin = Category::create([
                  'category_name' => 'admin',
                  'status' => true// optional
              ],[
                'category_name' => 'admin1',
                'status' => true// optional
              ]);


    }
}
