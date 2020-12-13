<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'name' => 'Category #1',
            'description' => 'Category #1',
            'taken_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('categories')->insert([
            'name' => 'Category #2',
            'description' => 'Category #2',
            'taken_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('categories')->insert([
            'parent_id' => 2,
            'name' => 'Child Category #1',
            'description' => 'Child Category #1',
            'taken_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
