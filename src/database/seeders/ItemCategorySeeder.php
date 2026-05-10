<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cat = [
            'ファッション' => \App\Models\Category::where('name', 'ファッション')->value('id'),
            '家電' => \App\Models\Category::where('name', '家電')->value('id'),
            'インテリア' => \App\Models\Category::where('name', 'インテリア')->value('id'),
            'レディース' => \App\Models\Category::where('name', 'レディース')->value('id'),
            'メンズ' => \App\Models\Category::where('name', 'メンズ')->value('id'),
            'コスメ' => \App\Models\Category::where('name', 'コスメ')->value('id'),
            'キッチン' => \App\Models\Category::where('name', 'キッチン')->value('id'),
            'アクセサリー' => \App\Models\Category::where('name', 'アクセサリー')->value('id'),
        ];

        \App\Models\Item::findOrFail(1)->categories()->sync([$cat['ファッション'], $cat['メンズ'], $cat['アクセサリー']]);
        \App\Models\Item::findOrFail(2)->categories()->sync([$cat['家電']]);
        \App\Models\Item::findOrFail(3)->categories()->sync([$cat['キッチン']]);
        \App\Models\Item::findOrFail(4)->categories()->sync([$cat['ファッション'], $cat['メンズ']]);
        \App\Models\Item::findOrFail(5)->categories()->sync([$cat['家電']]);
        \App\Models\Item::findOrFail(6)->categories()->sync([$cat['家電']]);
        \App\Models\Item::findOrFail(7)->categories()->sync([$cat['ファッション'], $cat['レディース']]);
        \App\Models\Item::findOrFail(8)->categories()->sync([$cat['キッチン']]);
        \App\Models\Item::findOrFail(9)->categories()->sync([$cat['キッチン']]);
        \App\Models\Item::findOrFail(10)->categories()->sync([$cat['コスメ']]);
    }
}
