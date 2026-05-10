<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::insert([
            [
                'name' => '出品者ユーザー',
                'email' => 'seller@example.com',
                'password' => bcrypt('password'),
                'postal_code' => '111-1111',
                'address' => '東京都渋谷区1-1-1',
                'building' => 'マンション101',
                'verification_code' => null,
            ],
            [
                'name' => '購入者ユーザー',
                'email' => 'buyer@example.com',
                'password' => bcrypt('password'),
                'postal_code' => '222-2222',
                'address' => '大阪府大阪市2-2-2',
                'building' => 'ハイツ202',
                'verification_code' => null,
            ],
        ]);
    }
}
