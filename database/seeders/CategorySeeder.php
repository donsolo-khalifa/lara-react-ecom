<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Smartphones',
                'department_id' => 1, // Electronics
                'parent_id' => null,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Android Phones',
                'department_id' => 1, // Electronics
                'parent_id' => 1, // Smartphones
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laptops',
                'department_id' => 1, // Electronics
                'parent_id' => null,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gardening Tools',
                'department_id' => 2, // Home, Garden & Tools
                'parent_id' => null,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hand Trowels',
                'department_id' => 2, // Home, Garden & Tools
                'parent_id' => 3, // Gardening Tools
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Clothing',
                'department_id' => 3, // Fashion
                'parent_id' => null,
                'active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Running Shoes',
                'department_id' => 4, // Sports & Outdoors
                'parent_id' => null,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fiction Books',
                'department_id' => 5, // Books
                'parent_id' => null,
                'active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fantasy Novels',
                'department_id' => 5, // Books
                'parent_id' => 7, // Fiction Books
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Makeup',
                'department_id' => 6, // Health & Beauty
                'parent_id' => null,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Car Accessories',
                'department_id' => 7, // Automotive
                'parent_id' => null,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Car Mats',
                'department_id' => 7, // Automotive
                'parent_id' => 10, // Car Accessories
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Board Games',
                'department_id' => 8, // Toys & Games
                'parent_id' => null,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Guitar Strings',
                'department_id' => 9, // Music Instruments
                'parent_id' => null,
                'active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Electric Guitars',
                'department_id' => 9, // Music Instruments
                'parent_id' => 13, // Guitar Strings
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dog Food',
                'department_id' => 10, // Pet Supplies
                'parent_id' => null,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('categories')->insert($categories);
        
    }
}
