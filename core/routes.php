<?php

use Slim\Routing\RouteCollectorProxy;

/** @var Slim\App $app */
$group = $app->group(
    '/api',
    function (RouteCollectorProxy $group) {
        $group->group(
            '/security',
            static function (RouteCollectorProxy $group) {
                $group->map(['OPTIONS', 'POST'], '/login', App\Controllers\Security\Login::class);
                $group->map(['OPTIONS', 'POST'], '/logout', App\Controllers\Security\Logout::class);
                $group->map(['OPTIONS', 'POST'], '/register', App\Controllers\Security\Register::class);
                $group->map(['OPTIONS', 'POST'], '/reset', App\Controllers\Security\Reset::class);
                $group->map(['OPTIONS', 'POST'], '/activate', App\Controllers\Security\Activate::class);
            }
        );

        $group->any('/user/profile', App\Controllers\User\Profile::class);
        $group->get('/image/{id}', App\Controllers\Image::class);

        $group->group(
            '/admin',
            static function (RouteCollectorProxy $group) {
                $group->map(['OPTIONS', 'GET'], '/languages', App\Controllers\Admin\Languages::class);
                $group->any('/user-roles[/{id}]', App\Controllers\Admin\UserRoles::class);
                $group->any('/users[/{id}]', App\Controllers\Admin\Users::class);
                $group->any('/users/{user_id}/addresses[/{id}]', App\Controllers\Admin\User\Addresses::class);
                $group->any('/users/{user_id}/orders[/{id}]', App\Controllers\Admin\User\Orders::class);
                $group->any('/categories[/{id}]', App\Controllers\Admin\Categories::class);
                $group->any('/products[/{id}]', App\Controllers\Admin\Products::class);
                $group->any('/product/{product_id}/files[/{file_id}]', App\Controllers\Admin\Product\Files::class);
                $group->any(
                    '/product/{product_id}/categories[/{category_id}]',
                    App\Controllers\Admin\Product\Categories::class
                );
                $group->any('/product/{product_id}/links[/{link_id}]', App\Controllers\Admin\Product\Links::class);
                $group->any('/orders[/{id}]', App\Controllers\Admin\Orders::class);
            }
        );

        $group->group(
            '/web',
            static function (RouteCollectorProxy $group) {
                $group->any('/resource/{uri:.+}', App\Controllers\Web\Resource::class);
                $group->any('/categories[/{uri:.+}]', App\Controllers\Web\Categories::class);
                $group->any('/category/{category_id}/products', App\Controllers\Web\Category\Products::class);
                $group->any('/category/{category_id}/filters', App\Controllers\Web\Category\Filters::class);
                $group->any('/products[/{uri:.+}]', App\Controllers\Web\Products::class);
                $group->any('/filters', App\Controllers\Web\Filters::class);
                $group->any('/orders', App\Controllers\Web\Orders::class);
                $group->any('/cart[/{id}]', App\Controllers\Web\Cart::class);
                $group->any('/cart/{cart_id}/products[/{product_key}]', App\Controllers\Web\Cart\Products::class);
            }
        );
    }
);

if (class_exists('\Clockwork\Clockwork')) {
    $group->add(Vesp\Middlewares\Clockwork::class);
    $app->get(
        '/__clockwork/{id:(?:[0-9-]+|latest)}[/{direction:(?:next|previous)}[/{count:\d+}]]',
        Vesp\Controllers\Data\Clockwork::class
    );
    if (function_exists('xdebug_get_profiler_filename')) {
        $app->get('/__clockwork/{id:[0-9-]+}/extended', Vesp\Controllers\Data\Clockwork::class);
    }
}