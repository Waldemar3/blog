<?php

namespace App\Models;

use PDO;

class Category
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM categories
            WHERE slug = :slug
        ");

        $stmt->execute(['slug' => $slug]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function getCategoriesWithArticles(): array
    {
        $stmt = $this->pdo->query("
            SELECT c.*
            FROM categories c
            INNER JOIN article_categories ac ON c.id = ac.category_id
            GROUP BY c.id
            ORDER BY c.name
        ");

        return $stmt->fetchAll();
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY name");
        return $stmt->fetchAll();
    }

    public function getArticleCount(int $categoryId): int
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as count
            FROM article_categories
            WHERE category_id = :category_id
        ");

        $stmt->execute(['category_id' => $categoryId]);
        return (int)$stmt->fetch()['count'];
    }
}
