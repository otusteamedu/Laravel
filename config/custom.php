<?php
return [
    'perPageAdmin' => 10,
    'categoriesSorts' => ['id', 'title', 'created_at'],
    'categoriesDirections' => ['asc', 'desc'], 
    'ordersSorts' => ['id', 'user', 'created_at'],
    'ordersDirections' => ['asc', 'desc'], 
    'productsSorts' => ['id', 'title', 'category', 'created_at'],
    'productsDirections' => ['asc', 'desc'], 
    'usersSorts' => ['id', 'name', 'email', 'created_at'],
    'usersDirections' => ['asc', 'desc'], 
    'adminEmail' => 'elisad5791@yandex.ru',
    'yookassaId' => env('YOOKASSA_ID'),
    'yookassaSecret' => env('YOOKASSA_SECRET'),
];