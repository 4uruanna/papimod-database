<?php

namespace Papimod\Database\Test;

use Papimod\Database\Database;
use Papimod\Database\sql\SqlTable;

abstract class DatabaseTableTestCase extends DatabaseTestCase
{
    protected string $setup_query = <<<SQL
        CREATE TABLE IF NOT EXISTS test_a (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            t VARCHAR(255) NOT NULL,
            i INT NOT NULL,
            n VARCHAR(255)
        );

        INSERT INTO test_a (t, i, n)
        VALUES
            ('AA', 10, NULL),
            ('AB', 20, NULL),
            ('AC', 20, "NOT NULL"),
            ('AD', 30, NULL),
            ('AE', 40, "NOT NULL"),
            ('AF', 50, NULL),
            ('AG', 60, "NOT NULL"),
            ('AH', 70, NULL),
            ('AI', 80, "NOT NULL"),
            ('AJ', 90, NULL);

        CREATE TABLE IF NOT EXISTS test_b (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            t VARCHAR(255) NOT NULL,
            a_id INT NOT NULL,
            FOREIGN KEY (a_id) REFERENCES test_a(id) ON DELETE CASCADE
        );

        INSERT INTO test_b (t, a_id)
        VALUES
            ('BA', 1),
            ('BA2', 1),
            ('BA3', 1),
            ('BB', 2),
            ('BC', 2),
            ('BE', 4),
            ('BF', 5);

        CREATE TABLE IF NOT EXISTS test_c (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            t VARCHAR(255) NOT NULL,
            a_id INT,
            FOREIGN KEY (a_id) REFERENCES test_a(id) ON DELETE CASCADE
        );

        INSERT INTO test_c (t, a_id)
        VALUES
            ('CA', 1),
            ('CB', 2),
            ('CC', 3),
            ('CD', 4),
            ('CE', 5),
            ('CFA', 6),
            ('CFB', NULL),
            ('CFC', NULL),
            ('CFD', NULL);
    SQL;

    protected string $clear_query = <<<SQL
        DROP TABLE IF EXISTS test_c;
        DROP TABLE IF EXISTS test_b;
        DROP TABLE IF EXISTS test_a;
    SQL;

    protected SqlTable $table_a;
    protected SqlTable $table_b;
    protected SqlTable $table_c;

    public function setUp(): void
    {
        parent::setUp();
        Database::pdo()->exec($this->clear_query);
        Database::pdo()->exec($this->setup_query);
        $this->table_a = new SqlTable("test_a");
        $this->table_b = new SqlTable("test_b");
        $this->table_c = new SqlTable("test_c");
    }
}
