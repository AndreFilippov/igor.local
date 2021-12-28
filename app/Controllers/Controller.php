<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\Text;

abstract class Controller {
    public static function getAuthUserOrFail(): User {
        /** @var User $user */
        $user = User::getById($_COOKIE['user_id']);
        if(!$user) throw new \Exception(Text::USER_NOT_LOGIN, 403);

        return $user;
    }
}