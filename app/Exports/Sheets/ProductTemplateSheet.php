<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductTemplateSheet implements FromCollection, WithHeadings, WithTitle, WithColumnWidths
{
    public function collection()
    {
        return collect([]);
    }

    public function headings(): array
    {
        return [
            'product_name',
            'category_id',
            'stock',
            'price',
            'barcode'
        ];
    }

    public function title(): string
    {
        return 'Product Import Template';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 26,
            'B' => 15,
            'C' => 11,
            'D' => 15,
            'E' => 20
        ];
    }
}
