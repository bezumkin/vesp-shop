<?php

namespace App\Controllers\Security;

use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

class Register extends Controller
{
    public function post(): ResponseInterface
    {
        try {
            $user = User::createUser($this->getProperties());
        } catch (\Exception $e) {
            return $this->failure($e->getMessage());
        }

        $lang = $this->request->getHeaderLine('Content-Language') ?: 'ru';
        $subject = getenv('EMAIL_SUBJECT_REGISTER_' . strtoupper($lang));
        $data = ['user' => $user->toArray(), 'code' => $user->resetPassword(10), 'lang' => $lang];
        if ($error = $user->sendEmail($subject, 'email-register', $data)) {
            return $this->failure($error);
        }

        return $this->success();
    }
}