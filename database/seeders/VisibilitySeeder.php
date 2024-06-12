<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Visibility;

class VisibilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Visibility::create(['id' => Visibility::PUBLIC, 'name' => 'public']);
        Visibility::create(['id' => Visibility::CONTACTS, 'name' => 'contacts']);
        Visibility::create(['id' => Visibility::PRIVATE,  'name' => 'private']);
    }
}
