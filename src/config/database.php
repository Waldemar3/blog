<?php

return [
    'host' => getenv('DB_HOST') ?: 'mysql',
    'database' => getenv('DB_NAME') ?: 'blog_db',
    'username' => getenv('DB_USER') ?: 'blog_user',
    'password' => getenv('DB_PASSWORD') ?: 'blog_password',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
