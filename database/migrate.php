<?php
declare(strict_types=1);

/**
 * Migration entry point.
 * Run from the project root:
 *
 *   php database/migrate.php
 *
 * The actual migration is idempotent and checks existing database objects
 * before creating or altering them.
 */
require_once __DIR__ . '/migrations/001_initial_schema.php';
