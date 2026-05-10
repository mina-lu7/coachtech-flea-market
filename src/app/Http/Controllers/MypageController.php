<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $purchasedItems = $user->purchases()->with('item.purchases')->get();
        $exhibitedItems = $user->items()->with('purchases')->get();

        return view('mypage', compact('user', 'purchasedItems', 'exhibitedItems'));
    }

    public function edit()
    {
        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->latest()->first();

        return view('profile', compact('user', 'address'));
    }

    public function update(ProfileRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $file = $request->profile_image;

        if ($file instanceof \Illuminate\Http\UploadedFile) {
            $path = $file->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        $user->name = $request->name;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building = $request->building;

        $user->save();

        return redirect('/?tab=mylist');
    }
}
