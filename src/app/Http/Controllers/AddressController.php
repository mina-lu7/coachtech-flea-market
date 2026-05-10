<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Address;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function edit(int $item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('address', compact('item'));
    }

    public function store(AddressRequest $request, int $item_id)
    {
        Address::create([
            'user_id' => Auth::id(),
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect('/purchase/' . $item_id . '?payment_method=' . $request->payment_method . '&address_id=' . Address::latest()->first()->id);
    }
}
