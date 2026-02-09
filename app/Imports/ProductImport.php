<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Shop;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class ProductImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if (empty($row['product_name'])) {
                continue;
            }

            $category = Category::where('category_name', $row['category'])->first();
            $shop = Shop::where('shop_name', $row['shop'])->first();

            if (!$category || !$shop) {
                continue;
            }

            Product::create([
                'category'            => $category->id,
                'shop'                => $shop->id,
                'product_name'        => $row['product_name'],
                'original_price'      => $row['original_price'],
                'discount_price'      => $row['discount_price'],
                'product_description' => $row['product_description'],
            ]);
        }
    }
}
