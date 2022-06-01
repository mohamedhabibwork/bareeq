<?php

namespace App\Repository\Traits;

trait AuthTrait
{
    public function login(string $phone, string $password): bool|array
    {
//        dd(compact('phone','password'));
        if ((!$user = $this->model->query()->firstWhere('phone', $phone)) || !$user->checkPassword($password)) {
            return false;
        }
        $token = $user->createToken(request()->ip());
        return compact('user', 'token');
    }
}
