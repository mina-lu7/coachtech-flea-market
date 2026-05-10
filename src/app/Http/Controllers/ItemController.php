<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        if (request('tab') === 'mylist') {
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();

                $items = $user->likes()->with('item')->get()->pluck('item');

                if ($keyword = request('keyword')) {
                    $items = $items->filter(function ($item) use ($keyword) {
                        return str_contains(mb_strtolower($item->name), mb_strtolower($keyword));
                    });
                }
            }
            else {
                $items = collect();
            }
        } else {
            if (Auth::check()) {
                $items = Item::where('user_id', '!=', Auth::id())
                    ->when(request('keyword'), function ($query, $keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%');
                    })
                    ->get();
            } else {
                $items = Item::when(request('keyword'), function ($query, $keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                })->get();
            }
        }

        return view('index', compact('items'));
    }

    public function show(int $item_id)
    {
        $item = Item::with([
            'user',
            'comments.user',
            'likes',
            'categories',
            'purchases'
        ])->findOrFail($item_id);

        return view('show', compact('item'));
    }
}
