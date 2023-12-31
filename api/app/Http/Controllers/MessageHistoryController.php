<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\Stylist;
use App\Models\Reservation;
use App\Models\Shop;

class MessageHistoryController extends Controller
{
    public function index(Request $request, Shop $shop)
    {
        $stylists = Stylist::where('shop_id', $shop->getKey())->get();
        $users = Reservation::with(['user'])
            ->where('shop_id', $shop->getKey())
            ->get()
            ->pluck('user')
            ->unique();

        return Inertia::render('Shop/MessageHistory/Index', compact('stylists', 'users'));
    }
}
