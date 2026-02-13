<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Shop;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class ProductImport implements ToCollection, WithHeadingRow
{

    public $imported = 0;
    public $skipped = 0;

    public function collection(Collection $rows)
    {


        foreach ($rows as $row) {

            if (
                empty($row['product_name']) &&
                empty($row['category']) &&
                empty($row['shop'])
            ) {
                continue;
            }

            if (empty($row['product_name'])) {
                $this->skipped++;
                continue;
            }



            $category = Category::where('category_name', $row['category'])->first();
            $shop = Shop::where('shop_name', $row['shop'])->first();
            $unit = Unit::where('unit_name', $row['unit'])->first();

            if (!$category || !$shop) {
                $this->skipped++;
                continue;
            }

            Product::create([
                'category'            => $category->id,
                'shop'                => $shop->id,
                'qty'                 => $row['qty'],
                'unit'                => $unit->id,
                'product_name'        => $row['product_name'],
                'original_price'      => $row['original_price'],
                'discount_price'      => $row['discount_price'],
                'product_description' => $row['product_description'],
            ]);
            $this->imported++;
        }
    }
}
