<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Shop;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


class ProductImport implements ToCollection, WithHeadingRow
{

    public $imported = 0;
    public $skipped = 0;

    public function collection(Collection $rows)
    {
        $shop_id = null;

        if (Auth::user()->auth_level == 4) {
            $shop_id = Shop::where('user_id', auth()->id())->value('id');
        }

        foreach ($rows as $row) {

            if (
                empty($row['product_name']) &&
                empty($row['category'])
            ) {
                continue;
            }

            if (empty($row['product_name'])) {
                $this->skipped++;
                continue;
            }

            $category = Category::where('category_name', $row['category'])->first();
            $unit     = Unit::where('unit_name', $row['unit'])->first();

            if (!$category) {
                $this->skipped++;
                continue;
            }

            if (Auth::user()->auth_level != 4) {

                $shop = Shop::where('shop_name', $row['shop'])->first();

                if (!$shop) {
                    $this->skipped++;
                    continue;
                }

                $final_shop_id = $shop->id;
            } else {

                $final_shop_id = $shop_id;
            }

            Product::create([
                'category'            => $category->id,
                'shop'                => $final_shop_id,
                'qty'                 => $row['qty'],
                'unit'                => isset($unit->id) ? $unit->id : null,
                'product_name'        => $row['product_name'],
                'hsn_code'            => $row['hsn_code'],
                'original_price'      => $row['original_price'],
                'discount_price'      => $row['discount_price'],
                'product_description' => $row['product_description'],
            ]);

            $this->imported++;
        }
    }


}
