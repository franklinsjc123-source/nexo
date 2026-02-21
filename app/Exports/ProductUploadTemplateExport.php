<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use App\Models\Category;
use App\Models\Shop;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;


class ProductUploadTemplateExport implements FromArray, WithHeadings, WithEvents
{
    public function headings(): array
    {

        if (Auth::user()->auth_level == 4) {

            return [
                'category',
                'product_name',
                'unit',
                'qty',
                'hsn_code',
                'original_price',
                'discount_price',
                'product_description'
            ];
        } else {

            return [
                'category',
                'shop',
                'product_name',
                'unit',
                'qty',
                'hsn_code',
                'original_price',
                'discount_price',
                'product_description'
            ];
        }
    }

    public function array(): array
    {
        return [];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $authLevel = Auth::user()->auth_level;


                if (Auth::user()->auth_level == 4) {
                    $shopCategories = Shop::where('user_id', auth()->id())
                        ->value('category');

                    $categories = [];

                    if ($shopCategories) {

                        $categoryIds = explode(',', $shopCategories);

                        $categories = Category::where('status', 1)
                            ->whereIn('id', $categoryIds)
                            ->pluck('category_name')
                            ->toArray();
                    }
                } else {

                    $categories = Category::where('status', 1)
                        ->pluck('category_name')
                        ->toArray();
                }



                $units = Unit::where('status', 1)
                    ->pluck('unit_name')
                    ->toArray();

                $spreadsheet = $event->sheet->getDelegate()->getParent();
                $listSheet = $spreadsheet->createSheet();
                $listSheet->setTitle('lists');

                foreach ($categories as $i => $value) {
                    $listSheet->setCellValue('A' . ($i + 1), $value);
                }

                foreach ($units as $i => $value) {
                    $listSheet->setCellValue('C' . ($i + 1), $value);
                }

                if ($authLevel != 4) {
                    $shops = Shop::where('status', 1)
                        ->pluck('shop_name')
                        ->toArray();

                    foreach ($shops as $i => $value) {
                        $listSheet->setCellValue('B' . ($i + 1), $value);
                    }
                }

                $listSheet->setSheetState(
                    \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN
                );

                for ($row = 2; $row <= 500; $row++) {

                    // Category dropdown (Column A)
                    $catValidation = $event->sheet->getCell("A{$row}")->getDataValidation();
                    $catValidation->setType(DataValidation::TYPE_LIST);
                    $catValidation->setAllowBlank(false);
                    $catValidation->setShowDropDown(true);
                    $catValidation->setFormula1('=lists!$A$1:$A$' . count($categories));

                    if ($authLevel != 4) {

                        // Shop dropdown (Column B)
                        $shopValidation = $event->sheet->getCell("B{$row}")->getDataValidation();
                        $shopValidation->setType(DataValidation::TYPE_LIST);
                        $shopValidation->setAllowBlank(false);
                        $shopValidation->setShowDropDown(true);
                        $shopValidation->setFormula1('=lists!$B$1:$B$' . count($shops));

                        $unitColumn = 'D'; // because shop column exists
                    } else {
                        $unitColumn = 'C'; // shop column removed
                    }

                    // Unit dropdown
                    $unitValidation = $event->sheet->getCell("{$unitColumn}{$row}")
                        ->getDataValidation();

                    $unitValidation->setType(DataValidation::TYPE_LIST);
                    $unitValidation->setAllowBlank(false);
                    $unitValidation->setShowDropDown(true);
                    $unitValidation->setFormula1('=lists!$C$1:$C$' . count($units));
                }
            }
        ];
    }
}
