<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Traits\PermissionCheckTrait;
use App\Exports\ProductUploadTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;

class ProductUploadController extends Controller
{
    use PermissionCheckTrait;



    public function exportExcel()
    {
        return Excel::download(
            new ProductUploadTemplateExport,
            'sample_product_upload.xlsx'
        );
    }



    public function productUpload($id = '')
    {
         if (!$this->checkPermission('Product-Upload')) {
            return view('unauthorized');
        }

        return view('backend.product-upload.upload');
    }

    public function exportCSV()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_product_upload.csv"',
        ];

        $columns = ['category', 'shop', 'product_name', 'original_price', 'discount_price', 'product_description'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }



    public function storeProductUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120'
        ]);

        Excel::import(new ProductImport, $request->file('file'));

        return redirect()
            ->route('product')
            ->with('success', 'Products uploaded successfully');
    }
}
