<?php
/**
 * Om Shanthi Trust & Foundation
 * Idempotent database migration.
 *
 * Usage:
 *   php database/migrate.php
 *
 * It is safe to run repeatedly:
 * - creates the database if missing
 * - creates tables if missing
 * - adds missing columns/indexes/foreign keys
 * - inserts required default settings/programs/FAQs only when absent
 *
 * IMPORTANT:
 * Configure DB_HOST, DB_NAME, DB_USER and DB_PASS in config/config.php
 * or environment variables before running.
 */

declare(strict_types=1);

require_once __DIR__ . '/../../config/config.php';

function migrationDb(): PDO {
    static $pdo;
    if ($pdo instanceof PDO) return $pdo;

    $server = new PDO(
        'mysql:host='.DB_HOST.';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $server->exec(
        'CREATE DATABASE IF NOT EXISTS `'.str_replace('`','``',DB_NAME).'`
         CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'
    );

    $pdo = new PDO(
        'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    return $pdo;
}

function tableExists(PDO $db, string $table): bool {
    $s = $db->prepare(
        'SELECT COUNT(*) FROM information_schema.TABLES
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?'
    );
    $s->execute([$table]);
    return (bool)$s->fetchColumn();
}

function columnExists(PDO $db, string $table, string $column): bool {
    $s = $db->prepare(
        'SELECT COUNT(*) FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?'
    );
    $s->execute([$table, $column]);
    return (bool)$s->fetchColumn();
}

function indexExists(PDO $db, string $table, string $index): bool {
    $s = $db->prepare(
        'SELECT COUNT(*) FROM information_schema.STATISTICS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?'
    );
    $s->execute([$table, $index]);
    return (bool)$s->fetchColumn();
}

function foreignKeyExists(PDO $db, string $table, string $constraint): bool {
    $s = $db->prepare(
        'SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
         WHERE CONSTRAINT_SCHEMA = DATABASE()
           AND TABLE_NAME = ?
           AND CONSTRAINT_NAME = ?
           AND CONSTRAINT_TYPE = "FOREIGN KEY"'
    );
    $s->execute([$table, $constraint]);
    return (bool)$s->fetchColumn();
}

function createTable(PDO $db, string $name, string $sql): void {
    if (!tableExists($db, $name)) {
        $db->exec($sql);
        echo "[created] table {$name}\n";
    } else {
        echo "[exists ] table {$name}\n";
    }
}

function addColumn(PDO $db, string $table, string $column, string $definition): void {
    if (!columnExists($db, $table, $column)) {
        $db->exec("ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$definition}");
        echo "[added  ] {$table}.{$column}\n";
    } else {
        echo "[exists ] {$table}.{$column}\n";
    }
}

function addIndex(PDO $db, string $table, string $index, string $definition): void {
    if (!indexExists($db, $table, $index)) {
        $db->exec("ALTER TABLE `{$table}` ADD {$definition}");
        echo "[index  ] {$table}.{$index}\n";
    } else {
        echo "[exists ] index {$table}.{$index}\n";
    }
}

$db = migrationDb();
$db->beginTransaction();

try {
    createTable($db, 'admins', <<<'SQL'
CREATE TABLE `admins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_admins_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'programs', <<<'SQL'
CREATE TABLE `programs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(180) NOT NULL,
  `slug` VARCHAR(190) NOT NULL,
  `short_description` TEXT NOT NULL,
  `description` LONGTEXT NOT NULL,
  `icon` VARCHAR(80) DEFAULT 'heart',
  `image` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('draft','published') NOT NULL DEFAULT 'published',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_programs_slug` (`slug`),
  KEY `idx_programs_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'news_articles', <<<'SQL'
CREATE TABLE `news_articles` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(220) NOT NULL,
  `slug` VARCHAR(220) NOT NULL,
  `excerpt` TEXT,
  `content` LONGTEXT NOT NULL,
  `category` VARCHAR(100) DEFAULT 'Updates',
  `author` VARCHAR(120) DEFAULT 'Om Shanthi Trust',
  `status` ENUM('draft','published') NOT NULL DEFAULT 'draft',
  `published_at` DATETIME NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_news_slug` (`slug`),
  KEY `idx_news_status_date` (`status`,`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'events', <<<'SQL'
CREATE TABLE `events` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(220) NOT NULL,
  `description` TEXT NOT NULL,
  `event_date` DATE NOT NULL,
  `event_time` TIME NULL,
  `venue` VARCHAR(220) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `registration_limit` INT UNSIGNED DEFAULT NULL,
  `status` ENUM('upcoming','completed','cancelled') NOT NULL DEFAULT 'upcoming',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_events_date_status` (`event_date`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'event_registrations', <<<'SQL'
CREATE TABLE `event_registrations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` INT UNSIGNED NOT NULL,
  `full_name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `phone` VARCHAR(40) DEFAULT NULL,
  `status` ENUM('registered','cancelled','attended') NOT NULL DEFAULT 'registered',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_event_reg_event` (`event_id`),
  KEY `idx_event_reg_email` (`email`),
  CONSTRAINT `fk_event_reg_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'volunteers', <<<'SQL'
CREATE TABLE `volunteers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `phone` VARCHAR(40),
  `city` VARCHAR(120),
  `interests` VARCHAR(255),
  `availability` VARCHAR(120),
  `skills` TEXT,
  `message` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_volunteers_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'donors', <<<'SQL'
CREATE TABLE `donors` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `phone` VARCHAR(40) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_donors_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'donations', <<<'SQL'
CREATE TABLE `donations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `donor_id` INT UNSIGNED DEFAULT NULL,
  `donor_name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `currency` CHAR(3) NOT NULL DEFAULT 'INR',
  `frequency` ENUM('one_time','monthly') NOT NULL DEFAULT 'one_time',
  `program_id` INT UNSIGNED DEFAULT NULL,
  `status` ENUM('pending','paid','failed','cancelled') NOT NULL DEFAULT 'pending',
  `provider` VARCHAR(60) DEFAULT NULL,
  `provider_reference` VARCHAR(190) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_donations_status_date` (`status`,`created_at`),
  KEY `idx_donations_program` (`program_id`),
  KEY `idx_donations_provider_ref` (`provider_reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'contact_messages', <<<'SQL'
CREATE TABLE `contact_messages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `phone` VARCHAR(40),
  `subject` VARCHAR(220),
  `message` TEXT NOT NULL,
  `status` ENUM('new','read','replied','archived') NOT NULL DEFAULT 'new',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_contact_status_date` (`status`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'newsletter_subscribers', <<<'SQL'
CREATE TABLE `newsletter_subscribers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(190) NOT NULL,
  `status` ENUM('subscribed','unsubscribed') NOT NULL DEFAULT 'subscribed',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_newsletter_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'gallery', <<<'SQL'
CREATE TABLE `gallery` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(180) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `verified` TINYINT(1) NOT NULL DEFAULT 0,
  `alt_text` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_gallery_category_verified` (`category`,`verified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'videos', <<<'SQL'
CREATE TABLE `videos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(180) NOT NULL,
  `provider` ENUM('mp4','youtube','vimeo') NOT NULL,
  `video_url` TEXT NOT NULL,
  `thumbnail_url` TEXT DEFAULT NULL,
  `category` VARCHAR(100) DEFAULT NULL,
  `verified` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_videos_category_verified` (`category`,`verified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'stories', <<<'SQL'
CREATE TABLE `stories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(220) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `summary` TEXT,
  `content` LONGTEXT NOT NULL,
  `program_id` INT UNSIGNED DEFAULT NULL,
  `outcome` TEXT,
  `consent_status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `status` ENUM('draft','published') NOT NULL DEFAULT 'draft',
  `published_at` DATETIME NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_stories_status_date` (`status`,`published_at`),
  KEY `idx_stories_program` (`program_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'testimonials', <<<'SQL'
CREATE TABLE `testimonials` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `role` VARCHAR(150) DEFAULT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `quote` TEXT NOT NULL,
  `testimonial_date` DATE DEFAULT NULL,
  `consent_status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `status` ENUM('draft','published') NOT NULL DEFAULT 'draft',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_testimonials_status_consent` (`status`,`consent_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'faqs', <<<'SQL'
CREATE TABLE `faqs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` VARCHAR(100) NOT NULL,
  `question` VARCHAR(255) NOT NULL,
  `answer` TEXT NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` ENUM('published','draft') NOT NULL DEFAULT 'published',
  PRIMARY KEY (`id`),
  KEY `idx_faq_category_order` (`category`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'media', <<<'SQL'
CREATE TABLE `media` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `original_name` VARCHAR(255) NOT NULL,
  `stored_name` VARCHAR(255) NOT NULL,
  `mime_type` VARCHAR(120) NOT NULL,
  `file_size` BIGINT UNSIGNED NOT NULL,
  `storage_path` VARCHAR(500) NOT NULL,
  `media_type` ENUM('image','video','document') NOT NULL,
  `verified` TINYINT(1) NOT NULL DEFAULT 0,
  `alt_text` VARCHAR(255) DEFAULT NULL,
  `uploaded_by` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_media_type_verified` (`media_type`,`verified`),
  KEY `idx_media_uploaded_by` (`uploaded_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'settings', <<<'SQL'
CREATE TABLE `settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(120) NOT NULL,
  `setting_value` LONGTEXT NULL,
  `setting_type` VARCHAR(40) NOT NULL DEFAULT 'text',
  `is_public` TINYINT(1) NOT NULL DEFAULT 1,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_settings_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    createTable($db, 'seo_meta', <<<'SQL'
CREATE TABLE `seo_meta` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_key` VARCHAR(160) NOT NULL,
  `title` VARCHAR(220) DEFAULT NULL,
  `description` TEXT,
  `canonical_url` TEXT,
  `og_image` VARCHAR(500) DEFAULT NULL,
  `robots` VARCHAR(100) DEFAULT 'index,follow',
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_seo_page_key` (`page_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

    /* Repair/upgrade older installations without destroying data. */
    addColumn($db,'donations','currency',"CHAR(3) NOT NULL DEFAULT 'INR'");
    addColumn($db,'donations','donor_id',"INT UNSIGNED DEFAULT NULL");
    addColumn($db,'donations','provider',"VARCHAR(60) DEFAULT NULL");
    addColumn($db,'donations','updated_at',"TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    addColumn($db,'contact_messages','status',"ENUM('new','read','replied','archived') NOT NULL DEFAULT 'new'");
    addColumn($db,'gallery','alt_text',"VARCHAR(255) DEFAULT NULL");
    addColumn($db,'news_articles','status',"ENUM('draft','published') NOT NULL DEFAULT 'draft'");
    addColumn($db,'news_articles','published_at',"DATETIME NULL");

    addIndex($db,'donations','idx_donations_status_date',"INDEX `idx_donations_status_date` (`status`,`created_at`)");
    addIndex($db,'contact_messages','idx_contact_status_date',"INDEX `idx_contact_status_date` (`status`,`created_at`)");

    if (!foreignKeyExists($db,'donations','fk_donations_program')) {
        $db->exec("ALTER TABLE donations ADD CONSTRAINT fk_donations_program FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE SET NULL");
        echo "[added  ] FK donations -> programs\n";
    }
    if (!foreignKeyExists($db,'donations','fk_donations_donor')) {
        $db->exec("ALTER TABLE donations ADD CONSTRAINT fk_donations_donor FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE SET NULL");
        echo "[added  ] FK donations -> donors\n";
    }
    if (!foreignKeyExists($db,'stories','fk_stories_program')) {
        $db->exec("ALTER TABLE stories ADD CONSTRAINT fk_stories_program FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE SET NULL");
        echo "[added  ] FK stories -> programs\n";
    }
    if (!foreignKeyExists($db,'media','fk_media_admin')) {
        $db->exec("ALTER TABLE media ADD CONSTRAINT fk_media_admin FOREIGN KEY (uploaded_by) REFERENCES admins(id) ON DELETE SET NULL");
        echo "[added  ] FK media -> admins\n";
    }

    /* Seed only missing records. Existing content is never overwritten. */
    $settings = [
        'mission' => ['To support people facing hardship with compassion, dignity and practical assistance while creating opportunities for education and a stronger community.','text',1],
        'vision' => ['A compassionate community where every person has access to essential support, opportunity and hope.','text',1],
        'organization_name' => ['Om Shanthi Trust & Foundation','text',1],
        'contact_email' => ['Add official email','text',1],
        'contact_phone' => ['Add official phone','text',1],
        'address' => ['Add verified foundation address','text',1],
        'facebook' => ['','url',1],
        'instagram' => ['','url',1],
        'youtube' => ['','url',1],
        'whatsapp' => ['','url',1],
        'default_language' => ['en','text',1],
        'supported_languages' => ['en,ta','text',1],
        'donation_currency' => ['INR','text',1]
    ];
    $check = $db->prepare("SELECT id FROM settings WHERE setting_key=? LIMIT 1");
    $insert = $db->prepare("INSERT INTO settings(setting_key,setting_value,setting_type,is_public) VALUES(?,?,?,?)");
    foreach($settings as $key=>$data){
        $check->execute([$key]);
        if(!$check->fetchColumn()){
            $insert->execute([$key,$data[0],$data[1],$data[2]]);
            echo "[seeded ] setting {$key}\n";
        }
    }

    $programs = [
        ['Food Support','food-support','Support for people facing food insecurity.','Sample content — replace with verified program information.','utensils'],
        ['Clothing Support','clothing-support','Essential clothing assistance for families in need.','Sample content — replace with verified program information.','shirt'],
        ['Education Support','education-support','Helping learners access educational opportunities.','Sample content — replace with verified program information.','book-open'],
        ['Child Support','child-support','Compassionate support focused on children.','Sample content — replace with verified program information.','child'],
        ['Community Welfare','community-welfare','Community-focused humanitarian assistance.','Sample content — replace with verified program information.','users'],
        ['Essential Assistance','essential-assistance','Practical support for essential needs.','Sample content — replace with verified program information.','hand-heart']
    ];
    $check = $db->prepare("SELECT id FROM programs WHERE slug=? LIMIT 1");
    $insert = $db->prepare("INSERT INTO programs(title,slug,short_description,description,icon,status) VALUES(?,?,?,?,?,'published')");
    foreach($programs as $p){
        $check->execute([$p[1]]);
        if(!$check->fetchColumn()){
            $insert->execute($p);
            echo "[seeded ] program {$p[0]}\n";
        }
    }

    $faqs = [
        ['Donations','How can I support the foundation?','Use the donation flow or contact the foundation for verified contribution instructions.',1],
        ['Volunteering','How can I volunteer?','Submit the volunteer form and the team can review your availability and interests.',2],
        ['Programs','How are programs managed?','Program details should be maintained through the admin CMS using verified organizational information.',3],
        ['Transparency','Where can I find official information?','Official registrations, statistics and financial information should be added only after verification.',4]
    ];
    $check = $db->prepare("SELECT id FROM faqs WHERE question=? LIMIT 1");
    $insert = $db->prepare("INSERT INTO faqs(category,question,answer,sort_order,status) VALUES(?,?,?,?, 'published')");
    foreach($faqs as $f){
        $check->execute([$f[1]]);
        if(!$check->fetchColumn()){
            $insert->execute($f);
            echo "[seeded ] FAQ {$f[1]}\n";
        }
    }

    $db->commit();
    echo "\nMigration completed successfully. It is safe to run again.\n";
} catch (Throwable $e) {
    if ($db->inTransaction()) $db->rollBack();
    fwrite(STDERR, "\nMigration failed: ".$e->getMessage()."\n");
    exit(1);
}
