<?php

use App\Exports\CategoryExport;
use App\Exports\ProductTemplateExport;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/export-categories', function () {
    return Excel::download(new CategoryExport, 'categories.xlsx');
})->name('categories.export');

Route::get('/download-product-template', function () {
    return Excel::download(new ProductTemplateExport, 'product-template.xlsx');
})->name('products.export-product-template');
