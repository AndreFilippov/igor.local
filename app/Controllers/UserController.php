<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\Text;

class UserController extends Controller {
    public static function login($params){
        if(!key_exists('login', $params)) throw new \Exception(Text::LOGIN_NOT_EXIST, 400);
        if(!key_exists('password', $params)) throw new \Exception(Text::PASSWORD_NOT_EXIST, 400);

        $user = User::getByLogin($params['login']);
        if(!$user) throw new \Exception(Text::USER_NOT_FOUND, 404);

        $password = crypt($params['password'], User::SALT);
        if($password !== $user->getPassword()) throw new \Exception(Text::PASSWORD_NOT_VALID, 401);

        setcookie('user_id', $user->getId());
        return ['message' => 'success'];
    }

    public static function getBooks($params): array {
        $user = parent::getAuthUserOrFail();

        $books = [];
        foreach ($user->getBooks() as $book){
            $books[] = $book->getResponse();
        }

        return $books;
    }

    public static function getUserBooks($params){
        $user = User::getById($params['id']);
        if(!$user) throw new \Exception(Text::USER_NOT_FOUND);

        $books = [];
        foreach ($user->getBooks() as $book){
            $books[] = $book->getResponse();
        }

        return $books;
    }
}