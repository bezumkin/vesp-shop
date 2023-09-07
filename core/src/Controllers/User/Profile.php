<?php

namespace App\Controllers\User;

use App\Controllers\Traits\FileModelController;
use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

class Profile extends Controller
{
    use FileModelController;

    protected $scope = 'profile';
    /** @var User $user */
    protected $user;

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
        try {
            $this->user->fillData($this->getProperties());
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
