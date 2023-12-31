<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shop;
use App\Models\User;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shop = Shop::first();
        $user = User::first();

        Review::factory()
            ->count(3)
            ->create([
                'user_id' => $user->id,
                'shop_id' => $shop->id,
            ]);
    }
}
