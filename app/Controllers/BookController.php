<?php

namespace App\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Services\Text;

class BookController extends Controller {
    public static function get($params){
        $book = Book::getBySkuOrFail($params['sku']);

        return $book->getResponse();
    }

    public static function take($params): array {
        $user = parent::getAuthUserOrFail();

        /** @var Book $book */
        $book = Book::getBySkuOrFail($params['sku']);

        return $book->take($user);
    }

    public static function return($params): array {
        /** @var User $user */
        $user = parent::getAuthUserOrFail();

        /** @var Book $book */
        $book = Book::getBySkuOrFail($params['sku']);
        if(!$user->checkIsTakeBook($book)) throw new \Exception(Text::USER_NOT_TAKE_BOOK, 400);

        return $book->return($user);
    }

    public static function getAll($params){
        $books = [];
        foreach (Book::getAll() as $book){
            $books[] = $book->getResponse();
        }

        return $books;
    }
}