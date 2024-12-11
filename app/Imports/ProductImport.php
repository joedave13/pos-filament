<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class ProductImport implements ToModel, WithHeadingRow, WithMultipleSheets, SkipsEmptyRows, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Product([
            'name' => $row['product_name'],
            'slug' => Str::slug($row['product_name']) . '-' . strtolower(Str::random(5)),
            'category_id' => $row['category_id'],
            'stock' => $row['stock'],
            'price' => $row['price'],
            'barcode' => $row['barcode']
        ]);
    }

    public function sheets(): array
    {
        return [
            0 => $this
        ];
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string'],
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'stock' => ['required', 'integer'],
            'price' => ['required', 'integer'],
            'barcode' => ['nullable', 'string', Rule::unique('products', 'barcode')]
        ];
    }
}
