<?php

namespace App\Database;

use PDO;

class Schema
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createTables(): void
    {
        $this->createCategoriesTable();
        $this->createArticlesTable();
        $this->createArticleCategoriesTable();
    }

    private function createCategoriesTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            slug VARCHAR(255) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_slug (slug)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->pdo->exec($sql);
    }

    private function createArticlesTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS articles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            image VARCHAR(255),
            description TEXT,
            content TEXT NOT NULL,
            views INT DEFAULT 0,
            published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_slug (slug),
            INDEX idx_published_at (published_at),
            INDEX idx_views (views)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->pdo->exec($sql);
    }

    private function createArticleCategoriesTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS article_categories (
            article_id INT NOT NULL,
            category_id INT NOT NULL,
            PRIMARY KEY (article_id, category_id),
            FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
            INDEX idx_category_id (category_id),
            INDEX idx_article_id (article_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->pdo->exec($sql);
    }

    public function dropTables(): void
    {
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        $this->pdo->exec("DROP TABLE IF EXISTS article_categories");
        $this->pdo->exec("DROP TABLE IF EXISTS articles");
        $this->pdo->exec("DROP TABLE IF EXISTS categories");
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    }
}
