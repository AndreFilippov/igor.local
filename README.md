Для запуска: 

```
composer i
```

Требуется apache

Методы:

```
POST /login - Авторизация
GET /my-books - Книги авторизованного пользователя
GET /user/books/:id - Книги пользователя по id

GET /books - Все книги
GET /books/:sku - Книги по sku
GET /book/take/:sku - Взять книгу по sku
GET /book/return/:sku - Вернуть кингу по sku
```