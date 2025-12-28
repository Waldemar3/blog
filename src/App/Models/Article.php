<?php

namespace App\Models;

use PDO;

class Article
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM articles
            WHERE slug = :slug
        ");

        $stmt->execute(['slug' => $slug]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function getLatestByCategory(int $categoryId, int $limit = 3): array
    {
        $stmt = $this->pdo->prepare("
            SELECT a.*
            FROM articles a
            INNER JOIN article_categories ac ON a.id = ac.article_id
            WHERE ac.category_id = :category_id
            ORDER BY a.published_at DESC
            LIMIT :limit
        ");

        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByCategoryWithPagination(
        int $categoryId,
        string $sortBy = 'published_at',
        int $page = 1,
        int $perPage = 9
    ): array {
        $offset = ($page - 1) * $perPage;

        $allowedSortFields = ['published_at', 'views'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'published_at';
        }

        $orderBy = $sortBy === 'views' ? 'views DESC' : 'published_at DESC';

        $stmt = $this->pdo->prepare("
            SELECT a.*
            FROM articles a
            INNER JOIN article_categories ac ON a.id = ac.article_id
            WHERE ac.category_id = :category_id
            ORDER BY a.{$orderBy}
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getCountByCategory(int $categoryId): int
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as count
            FROM article_categories
            WHERE category_id = :category_id
        ");

        $stmt->execute(['category_id' => $categoryId]);
        return (int)$stmt->fetch()['count'];
    }

    public function getCategoriesByArticle(int $articleId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*
            FROM categories c
            INNER JOIN article_categories ac ON c.id = ac.category_id
            WHERE ac.article_id = :article_id
            ORDER BY c.name
        ");

        $stmt->execute(['article_id' => $articleId]);
        return $stmt->fetchAll();
    }

    public function getSimilarArticles(int $articleId, array $categoryIds, int $limit = 3): array
    {
        if (empty($categoryIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));

        $stmt = $this->pdo->prepare("
            SELECT DISTINCT a.*
            FROM articles a
            INNER JOIN article_categories ac ON a.id = ac.article_id
            WHERE ac.category_id IN ({$placeholders})
              AND a.id != ?
            ORDER BY a.published_at DESC
            LIMIT ?
        ");

        $params = array_merge($categoryIds, [$articleId, $limit]);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function incrementViews(int $articleId): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE articles
            SET views = views + 1
            WHERE id = :id
        ");

        $stmt->execute(['id' => $articleId]);
    }
}
