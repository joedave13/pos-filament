<?php

namespace App\Exports\Sheets;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class CategoryReferenceSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    public function collection()
    {
        return Category::query()->get(['id', 'name']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name'
        ];
    }

    public function title(): string
    {
        return 'Category Reference';
    }
}
