<?php

namespace App\Services;

class Text {
    const BOOK_NOT_FOUND = 'Книга не найдена.';
    const METHOD_NOT_FOUND = 'Метод не найден.';
    const LOGIN_NOT_EXIST = 'Укажите login.';
    const PASSWORD_NOT_EXIST = 'Укажите пароль.';
    const USER_NOT_FOUND = 'Пользователь не найден.';
    const PASSWORD_NOT_VALID = 'Не верный пароль.';
    const USER_NOT_LOGIN = 'Авторизуйтесь.';
    const BOOK_NOT_EXIST = 'Данная книга закончилась.';
    const BOOK_TAKE_FAIL = 'При получении книги возникла ошибка.';
    const BOOK_RETURN_FAIL = 'При возврате книги возникла ошибка.';
    const BOOK_IS_EXIST_TAKE = 'Вы уже взяли эту книгу.';
    const USER_NOT_TAKE_BOOK = 'Вы не брали эту книгу.';
}