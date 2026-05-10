<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Item;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function create(int $item_id)
    {
        $item = Item::findOrFail($item_id);

        if ($item->purchases()->exists()) {
            return redirect('/');
        }

        if (request('address_id')) {
            $address = Address::where('user_id', Auth::id())
                ->findOrFail(request('address_id'));
        } else {
            $address = Auth::user();
        }

        return view('purchase', compact('item', 'address'));
    }

    public function store(PurchaseRequest $request, int $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);
        $address = Address::latest()->first();

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
        ]);

        return redirect('/')->with('success', '購入が完了しました');
    }

    public function checkout(Request $request)
    {
        $item = Item::findOrFail($request->item_id);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://localhost:8080/purchase/success?item_id=' . $item->id,
            'cancel_url' => url('/purchase/' . $item->id),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $user = Auth::user();
        $item = Item::findOrFail($request->item_id);

        $addressId = $request->address_id;

        if (! $addressId) {
            $address = Address::create([
                'user_id' => $user->id,
                'postal_code' => $user->postal_code,
                'address' => $user->address,
                'building' => $user->building,
            ]);

            $addressId = $address->id;
        }

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $addressId,
        ]);

        return redirect('/');
    }
}
