<?php

namespace App\Exports;

use App\Exports\Sheets\CategoryReferenceSheet;
use App\Exports\Sheets\ProductTemplateSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductTemplateExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        return [
            new ProductTemplateSheet,
            new CategoryReferenceSheet
        ];
    }
}
