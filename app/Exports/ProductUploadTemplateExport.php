<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use App\Models\Category;
use App\Models\Shop;

class ProductUploadTemplateExport implements FromArray, WithHeadings, WithEvents
{
    public function headings(): array
    {
        return [
            'category',
            'shop',
            'product_name',
            'original_price',
            'discount_price',
            'product_description'
        ];
    }

    public function array(): array
    {
        return []; // empty rows
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                // Fetch data
                $categories = Category::where('status', 1)
                    ->pluck('category_name')
                    ->toArray();

                $shops = Shop::where('status', 1)
                    ->pluck('shop_name')
                    ->toArray();

                // Create hidden sheet
                $spreadsheet = $event->sheet->getDelegate()->getParent();
                $listSheet = $spreadsheet->createSheet();
                $listSheet->setTitle('lists');

                // Fill categories
                foreach ($categories as $i => $value) {
                    $listSheet->setCellValue('A' . ($i + 1), $value);
                }

                // Fill shops
                foreach ($shops as $i => $value) {
                    $listSheet->setCellValue('B' . ($i + 1), $value);
                }

                // Hide sheet
                $listSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

                // Category dropdown (Column A)
                for ($row = 2; $row <= 500; $row++) {
                    $catValidation = $event->sheet
                        ->getCell("A{$row}")
                        ->getDataValidation();

                    $catValidation->setType(DataValidation::TYPE_LIST);
                    $catValidation->setAllowBlank(false);
                    $catValidation->setShowDropDown(true);
                    $catValidation->setFormula1('=lists!$A$1:$A$' . count($categories));

                    // Shop dropdown (Column B)
                    $shopValidation = $event->sheet
                        ->getCell("B{$row}")
                        ->getDataValidation();

                    $shopValidation->setType(DataValidation::TYPE_LIST);
                    $shopValidation->setAllowBlank(false);
                    $shopValidation->setShowDropDown(true);
                    $shopValidation->setFormula1('=lists!$B$1:$B$' . count($shops));
                }
            }
        ];
    }
}
