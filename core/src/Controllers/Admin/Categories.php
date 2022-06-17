<?php

namespace App\Controllers\Admin;

use App\Models\Category;
use Vesp\Controllers\ModelController;

class Categories extends ModelController
{
    protected $scope = 'products';
    protected $model = Category::class;
}