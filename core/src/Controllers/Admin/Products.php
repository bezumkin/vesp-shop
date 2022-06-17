<?php

namespace App\Controllers\Admin;

use App\Models\Product;
use Vesp\Controllers\ModelController;

class Products extends ModelController
{
    protected $scope = 'products';
    protected $model = Product::class;
}