<?php

return [
    'base_url' => getenv('BASE_URL') ?: 'http://localhost:8080',
    'base_path' => '/var/www/html',
    'uploads_path' => '/var/www/html/public/uploads',
    'uploads_url' => '/uploads',
    'per_page' => 9,
    'home_posts_per_category' => 3,
    'similar_posts_count' => 3,
];
