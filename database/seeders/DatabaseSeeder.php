<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->createMany([
            ['name' => '山田 太郎', 'email' => 'taro@example.com'],
            ['name' => '佐藤 花子', 'email' => 'hanako@example.com'],
            ['name' => '鈴木 一郎', 'email' => 'ichiro@example.com'],
            ['name' => '田中 美咲', 'email' => 'misaki@example.com'],
            ['name' => '高橋 健太', 'email' => 'kenta@example.com'],
        ]);
    }
}
