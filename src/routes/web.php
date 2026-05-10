<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ExhibitController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\VerifyCodeRequest;
use Illuminate\Support\Facades\Mail;

// 商品一覧
Route::get('/', [ItemController::class, 'index']);

// 商品詳細
Route::get('/item/{item_id}', [ItemController::class, 'show']);

//ログイン
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// 新規登録
Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store']);

// 決済成功
Route::get('/purchase/success', [PurchaseController::class, 'success']);

// 商品購入（表示）
Route::get('/purchase/{item_id}', [PurchaseController::class, 'create'])->middleware('auth');

// 商品購入（処理）
Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->middleware('auth');

// 住所変更
Route::get('/purchase/address/{item_id}', [AddressController::class, 'edit'])->middleware('auth');
Route::post('/purchase/address/{item_id}', [AddressController::class, 'store'])->middleware('auth');

// 出品
Route::get('/sell', [ExhibitController::class, 'create'])->middleware('auth');
Route::post('/sell', [ExhibitController::class, 'store'])->middleware('auth');

// マイページ
Route::get('/mypage', [MypageController::class, 'index'])->middleware('auth');

// プロフィール編集（表示）
Route::get('/mypage/profile', [MypageController::class, 'edit'])->middleware('auth');

// プロフィール編集（更新）
Route::post('/mypage/profile', [MypageController::class, 'update'])->middleware('auth');

// いいね
Route::post('/like/{item_id}', function ($item_id) {
    $like = Like::where('user_id', Auth::id())
        ->where('item_id', $item_id)
        ->first();

    if ($like) {
        $like->delete();
    } else {
        Like::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
        ]);
    }

    return back();
})->middleware('auth');

// コメント
Route::post('/comment/{item_id}', function (CommentRequest $request, $item_id) {
    Comment::create([
        'user_id' => Auth::id(),
        'item_id' => $item_id,
        'content' => $request->content,
    ]);

    return back();
})->middleware('auth');

// メール認証
Route::get('/email/verify-code', function () {
    return view('auth.verify-code');
})->name('verification.code');

Route::post('/email/verify-code', function (VerifyCodeRequest $request) {

    $user = User::where('verification_code', $request->verification_code)->first();

    if (! $user) {
        return back()->withErrors([
            'verification_code' => '認証コードが正しくありません',
        ]);
    }

    $user->verification_code = null;
    $user->save();

    Auth::login($user);

    return redirect('/mypage/profile');
});

Route::get('/email/verify', function (Request $request) {

    if ($request->has('email')) {
        session(['verify_email' => $request->email]);
    }

    return view('auth.verify-email');
})->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {

    $user = User::where('email', session('verify_email'))->first();

    if (! $user) {
        return back();
    }

    $user->verification_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $user->save();

    Mail::to($user->email)->send(new \App\Mail\VerificationMail($user->verification_code));

    return redirect('/email/verify')
        ->with('message', '認証メールを再送しました');

})->name('verification.send');

// チェックアウト
Route::post('/checkout', [PurchaseController::class, 'checkout'])->middleware('auth');