<?php

namespace App\Controllers\Web;

use App\Models\Category;
use App\Models\Product;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Vesp\Controllers\Controller;
use Vesp\Services\Eloquent;

class Resource extends Controller
{
    private App $app;

    public function __construct(App $app, Eloquent $eloquent)
    {
        parent::__construct($eloquent);
        $this->app = $app;
    }

    public function get(): ResponseInterface
    {
        $uri = trim($this->getProperty('uri', ''), '/');
        $endpoint = trim($this->request->getRequestTarget(), '/');
        $factory = new ServerRequestFactory();

        if (Product::query()->where('uri', $uri)->count()) {
            $endpoint = str_replace('/resource/', '/products/', $endpoint);
            $request = $factory->createServerRequest('GET', $endpoint);
        } elseif (Category::query()->where('uri', $uri)->count()) {
            $endpoint = str_replace('/resource/', '/categories/', $endpoint);
            $request = $factory->createServerRequest('GET', $endpoint);
        } else {
            return $this->failure('Not Found', 404);
        }

        foreach ($this->request->getHeaders() as $key => $value) {
            $request = $request->withHeader($key, $value);
        }

        return $this->app->handle($request);
    }
}