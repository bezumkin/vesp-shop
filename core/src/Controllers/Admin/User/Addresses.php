<?php

namespace App\Controllers\Admin\User;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelController;

/**
 * @property User $user
 */
class Addresses extends ModelController
{
    protected string|array $scope = 'users';
    protected string $model = UserAddress::class;

    public function checkScope(string $method): ?ResponseInterface
    {
        if ($check = parent::checkScope($method)) {
            return $check;
        }

        if (!$this->user = User::query()->find($this->getProperty('user_id'))) {
            return $this->failure('', 404);
        }

        return null;
    }

    protected function beforeGet(Builder $c): Builder
    {
        $c->where('user_id', $this->user->id);
        $c->with('user:id,username,fullname');

        return $c;
    }

    protected function beforeCount(Builder $c): Builder
    {
        if ($query = trim($this->getProperty('query', ''))) {
            $c->where(static function (Builder $c) use ($query) {
                $c->where('receiver', 'LIKE', "%$query%");
                $c->orWhere('phone', 'LIKE', "%$query%");
                $c->orWhere('address', 'LIKE', "%$query%");
                $c->orWhere('country', 'LIKE', "%$query%");
                $c->orWhere('city', 'LIKE', "%$query%");
                $c->orWhere('zip', 'LIKE', "%$query%");
            });
        }

        return $this->beforeGet($c);
    }
}