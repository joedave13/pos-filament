<?php

use App\Exports\CategoryExport;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/export-categories', function () {
    return Excel::download(new CategoryExport, 'categories.xlsx');
})->name('categories.export');
