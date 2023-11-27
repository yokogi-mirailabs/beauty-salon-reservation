<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\PaymentHistory;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\User;
use App\Models\Stylist;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $shop = Shop::first();
        $stylists = Stylist::where('shop_id', $shop->getkey())
            ->get();
        Reservation::factory()
            ->for($user)
            ->for($shop)
            ->afterCreating(function (Reservation $reservation) use ($stylists) {
                $menus = Stylist::find($reservation->stylist_id)
                    ->menus()
                    ->inRandomOrder()
                    ->take(2)
                    ->get();

                foreach ($menus as $menu) {
                    PaymentHistory::factory()
                        ->for($reservation)
                        ->for($menu)
                        ->create([
                            'count' => 1,
                        ]);
                }
            })
            ->createMany([
                [
                    'date' => '2023-11-20 10:00:00',
                    'time' => 1,
                    'stylist_id' => $stylists->random()->id,
                    // 'shop_id' => $shop->id,
                ],
                [
                    'date' => '2023-12-20 11:00:00',
                    'time' => 3,
                    'stylist_id' => $stylists->random()->id,
                    // 'shop_id' => $shop->id,
                ],
                [
                    'date' => '2023-11-25 12:00:00',
                    'time' => 6,
                    'stylist_id' => $stylists->random()->id,
                    // 'shop_id' => $shop->id,
                ],
                [
                    'date' => '2023-11-22 13:00:00',
                    'time' => 2,
                    'stylist_id' => $stylists->random()->id,
                    // 'shop_id' => $shop->id,
                ],
                [
                    'date' => '2023-11-28 14:00:00',
                    'time' => 2,
                    'stylist_id' => $stylists->random()->id,
                    // 'shop_id' => $shop->id,
                ]
            ]);
    }
}