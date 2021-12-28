<?php

namespace App\Models;

use App\Interfaces\ModelInterface;
use App\Services\Text;

class Book extends Model implements ModelInterface {
    const TABLE_NAME = 'books';

    const ALLOWED_FIELDS = ['qty'];

    protected int $id;
    protected string $name;
    protected string $sku;
    protected string $author;
    protected string $created_at;
    protected int $qty;
    public array | null $users = null;

    public function getSku(): string {
        return $this->sku;
    }

    public static function getBySku($sku){
        return parent::get('sku', $sku);
    }

    public static function getBySkuOrFail($sku){
        $book = self::getBySku($sku);
        if(!$book) throw new \Exception(Text::BOOK_NOT_FOUND, 404);

        return $book;
    }

    public function getResponse(): array {
        return [
            'name' => $this->name,
            'sku' => $this->sku,
            'author' => $this->author,
            'qty' => $this->qty,
        ];
    }

    public function getUsers(){
        if($this->users === null){
            $users = $this->getMany('book_user', 'book_id', 'user_id', User::class);
            $this->users = $users;
        }
        return $this->users;
    }

    public function setTakeUser($user){
        if(!$user) throw new \Exception(Text::USER_NOT_FOUND, 400);
        return $this->setMany('book_user', 'book_id', 'user_id', $user);
    }

    public function setReturnUser($user){
        if(!$user) throw new \Exception(Text::USER_NOT_FOUND, 400);
        return $this->deleteMany('book_user', 'book_id', 'user_id', $user);
    }

    public function take($user){
        if($this->qty === 0) throw new \Exception(Text::BOOK_NOT_EXIST);
        foreach ($this->getUsers() as $bookUser){
            if($bookUser->getId() === $user->getId()) throw new \Exception(Text::BOOK_IS_EXIST_TAKE);
        };

        $this->qty--;
        if(!$this->setTakeUser($user) || !$this->save()) throw new \Exception(Text::BOOK_TAKE_FAIL, 400);

        return ['message' => "Вы взяли книгу: {$this->name}"];
    }

    public function return($user){
        $this->qty++;
        if(!$this->setReturnUser($user) || !$this->save()) throw new \Exception(Text::BOOK_TAKE_FAIL, 400);

        return ['message' => "Вы вернули книгу: {$this->name}"];
    }
}