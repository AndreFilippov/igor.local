<?php

namespace App\Models;

use App\Interfaces\ModelInterface;

class User extends Model implements ModelInterface {
    const TABLE_NAME = 'users';
    const SALT = 'test';

    protected int $id;
    protected string $password;
    protected string $login;
    protected string $name;
    protected string $created_at;
    protected array | null $books = null;

    public static function getByLogin($login){
        return parent::get('login', $login);
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getBooks(){
        if($this->books === null){
            $users = $this->getMany('book_user', 'user_id', 'book_id', Book::class);
            $this->books = $users;
        }
        return $this->books;
    }

    public function checkIsTakeBook($book): bool {
        foreach ($this->getBooks() as $uBook){
            if($book->getSku() == $uBook->getSku()) return true;
        }
        return false;
    }
}