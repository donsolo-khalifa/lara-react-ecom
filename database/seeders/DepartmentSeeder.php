<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Home, Garden & Tools',
                'slug' => Str::slug('Home, Garden & Tools'),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fashion',
                'slug' => Str::slug('Fashion'),
                'active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sports & Outdoors',
                'slug' => Str::slug('Sports & Outdoors'),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Books',
                'slug' => Str::slug('Books'),
                'active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Health & Beauty',
                'slug' => Str::slug('Health & Beauty'),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Automotive',
                'slug' => Str::slug('Automotive'),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Toys & Games',
                'slug' => Str::slug('Toys & Games'),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Music Instruments',
                'slug' => Str::slug('Music Instruments'),
                'active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pet Supplies',
                'slug' => Str::slug('Pet Supplies'),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('departments')->insert($departments);

    }
}