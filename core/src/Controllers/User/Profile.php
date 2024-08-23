<?php

namespace App\Controllers\User;

use App\Controllers\Traits\FileModelController;
use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

/**
 * @property User $user
 */
class Profile extends Controller
{
    use FileModelController;

    protected string|array $scope = 'profile';
    public $attachments = ['file'];

    public function get(): ResponseInterface
    {
        $data = $this->user->toArray();
        $data['scope'] = $this->user->role->scope;
        $data['cart'] = $this->user->cart->id ?? null;
        $data['file'] = $this->user->file ? $this->user->file->only('id', 'updated_at') : null;

        return $this->success(['user' => $data]);
    }

    public function patch(): ResponseInterface
    {
        $data = array_filter($this->getProperties(), static function($key) {
            return in_array($key, ['username', 'fullname', 'password', 'email', 'phone']);
        }, ARRAY_FILTER_USE_KEY);
        
        try {
            $this->user->fillData($data);
        } catch (\Exception $e) {
            return $this->failure($e->getMessage());
        }

        if ($error = $this->processFiles($this->user)) {
            return $error;
        }

        $this->user->save();
        $this->user->refresh();

        return $this->get();
    }
}
