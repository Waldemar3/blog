<?php

namespace App\Database;

use PDO;
use Faker\Factory as Faker;

class Seeder
{
    private PDO $pdo;
    private $faker;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->faker = Faker::create('ru_RU');
    }

    public function run(): void
    {
        echo "Starting seeding...\n";

        $this->seedCategories();
        $this->seedArticles();

        echo "Seeding completed!\n";
    }

    private function seedCategories(): void
    {
        echo "Seeding categories...\n";

        $categories = [
            ['name' => 'Технологии', 'description' => 'Новости и статьи о современных технологиях, гаджетах и инновациях'],
            ['name' => 'Программирование', 'description' => 'Уроки, туториалы и советы по программированию'],
            ['name' => 'Дизайн', 'description' => 'Все о веб-дизайне, UI/UX и графическом дизайне'],
            ['name' => 'Бизнес', 'description' => 'Статьи о предпринимательстве, стартапах и бизнес-стратегиях'],
            ['name' => 'Маркетинг', 'description' => 'Цифровой маркетинг, SEO, контент-маркетинг и SMM'],
            ['name' => 'Наука', 'description' => 'Научные открытия, исследования и популяризация науки'],
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO categories (name, description, slug)
            VALUES (:name, :description, :slug)
        ");

        foreach ($categories as $category) {
            $stmt->execute([
                'name' => $category['name'],
                'description' => $category['description'],
                'slug' => $this->slugify($category['name']),
            ]);
        }

        echo "Created " . count($categories) . " categories\n";
    }

    private function seedArticles(): void
    {
        echo "Seeding articles...\n";

        $categoryIds = $this->pdo->query("SELECT id FROM categories")->fetchAll(PDO::FETCH_COLUMN);

        $images = [
            'technology.jpg',
            'programming.jpg',
            'design.jpg',
            'business.jpg',
            'marketing.jpg',
            'science.jpg',
        ];

        $stmt = $this->pdo->prepare("
            INSERT INTO articles (title, slug, image, description, content, views, published_at)
            VALUES (:title, :slug, :image, :description, :content, :views, :published_at)
        ");

        $articleCategoryStmt = $this->pdo->prepare("
            INSERT INTO article_categories (article_id, category_id)
            VALUES (:article_id, :category_id)
        ");

        for ($i = 0; $i < 50; $i++) {
            $title = $this->faker->sentence(rand(5, 10));
            $title = rtrim($title, '.');

            $paragraphs = $this->faker->paragraphs(rand(5, 10));
            $content = implode("\n\n", $paragraphs);

            $stmt->execute([
                'title' => $title,
                'slug' => $this->slugify($title) . '-' . $i,
                'image' => $images[array_rand($images)],
                'description' => $this->faker->text(200),
                'content' => $content,
                'views' => rand(0, 5000),
                'published_at' => $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d H:i:s'),
            ]);

            $articleId = $this->pdo->lastInsertId();

            $numCategories = rand(1, 3);
            $selectedCategories = (array)array_rand(array_flip($categoryIds), $numCategories);

            foreach ($selectedCategories as $categoryId) {
                $articleCategoryStmt->execute([
                    'article_id' => $articleId,
                    'category_id' => $categoryId,
                ]);
            }
        }

        echo "Created 50 articles\n";
    }

    private function slugify(string $text): string
    {
        $transliteration = [
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
            'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I',
            'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
            'У' => 'U', 'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Ts', 'Ч' => 'Ch',
            'Ш' => 'Sh', 'Щ' => 'Shch', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'shch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        ];

        $text = strtr($text, $transliteration);
        $text = preg_replace('/[^A-Za-z0-9-]+/', '-', $text);
        $text = strtolower(trim($text, '-'));

        return $text;
    }
}
