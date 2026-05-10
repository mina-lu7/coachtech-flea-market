<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品詳細が表示される()
    {
        $item = \App\Models\Item::factory()->create([
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト説明',
            'price' => 5000,
            'condition' => 1,
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('テスト説明');
    }

    public function test_複数カテゴリが表示される()
    {
        $item = \App\Models\Item::factory()->create();

        $category1 = \App\Models\Category::create(['name' => 'カテゴリ1']);
        $category2 = \App\Models\Category::create(['name' => 'カテゴリ2']);

        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get('/item/' . $item->id);

        $response->assertSee('カテゴリ1');
        $response->assertSee('カテゴリ2');
    }

    public function test_未認証でも商品詳細が表示される()
{
    $item = \App\Models\Item::factory()->create([
        'name' => 'テスト商品',
    ]);

    $response = $this->get('/item/' . $item->id);

    $response->assertStatus(200);
    $response->assertSee('テスト商品');
}
}
