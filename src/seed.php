#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Database\Database;
use App\Database\Schema;
use App\Database\Seeder;

try {
    echo "Connecting to database...\n";
    $pdo = Database::getConnection();

    echo "Creating schema...\n";
    $schema = new Schema($pdo);
    $schema->dropTables();
    $schema->createTables();
    echo "Schema created successfully!\n\n";

    $seeder = new Seeder($pdo);
    $seeder->run();

    echo "\n✓ Database seeded successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
