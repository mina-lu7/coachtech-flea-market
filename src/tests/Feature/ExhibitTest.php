<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExhibitTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品を出品できる()
    {
        $user = \App\Models\User::factory()->create();

        $this->actingAs($user);

        $category = \App\Models\Category::create([
            'name' => 'カテゴリ1',
        ]);

        $response = $this->post('/sell', [
            'name' => 'テスト商品',
            'description' => 'テスト説明',
            'price' => 1000,
            'condition' => 1,
            'categories' => [$category->id],
            'brand' => 'テストブランド',
            'image' => \Illuminate\Http\UploadedFile::fake()->create('test.jpg', 100),
        ]);

        $response->assertRedirect('/mypage?page=sell');

        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'description' => 'テスト説明',
            'price' => 1000,
            'condition' => 1,
            'brand' => 'テストブランド',
        ]);

        $this->assertDatabaseHas('item_categories', [
            'item_id' => \App\Models\Item::first()->id,
            'category_id' => $category->id,
        ]);
    }
}
