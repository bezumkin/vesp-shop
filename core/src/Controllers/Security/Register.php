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
            $user = new User();
            $properties = $this->getProperties();
            $properties['active'] = false;
            $properties['role_id'] = getenv('REGISTER_ROLE_ID') ?: 2;
            if (empty($properties['password'])) {
                $properties['password'] = bin2hex(random_bytes(20));
            }
            $user->fillData($properties);
            $user->save();
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