<?php

namespace App\Controllers\Web;

use App\Services\CategoryFilter;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

class Filters extends Controller
{
    public function get(): ResponseInterface
    {
        $service = new CategoryFilter($this->category ?? null);
        $filters = $service->getFilters();

        return $this->success([
            'total' => count($filters),
            'rows' => $filters,
        ]);
    }

    public function post(): ResponseInterface
    {
        $service = new CategoryFilter($this->category ?? null);
        $filters = $service->getFilters($this->getProperties());

        return $this->success([
            'total' => count($filters),
            'rows' => $filters,
        ]);
    }
}