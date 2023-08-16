<?php

namespace App\Controllers\Admin;

use App\Models\Language;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelGetController;

class Languages extends ModelGetController
{
    protected $scope = 'profile';
    protected $model = Language::class;

    protected function beforeCount(Builder $c): Builder
    {
        return $c->where('active', true);
    }

    protected function afterCount(Builder $c): Builder
    {
        if ($this->getProperty('combo')) {
            $c->select('lang', 'title');
        }
        $c->orderBy('rank');

        return $c;
    }
}