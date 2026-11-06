CREATE DATABASE IF NOT EXISTS my_magazine CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE my_magazine;

CREATE TABLE users (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role ENUM('admin','editor','author') NOT NULL DEFAULT 'author',
 status ENUM('active','inactive') NOT NULL DEFAULT 'active',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 slug VARCHAR(130) NOT NULL UNIQUE,
 description TEXT NULL,
 is_active TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE articles (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 category_id BIGINT UNSIGNED NULL,
 author_id BIGINT UNSIGNED NOT NULL,
 title VARCHAR(220) NOT NULL,
 slug VARCHAR(240) NOT NULL UNIQUE,
 excerpt TEXT NULL,
 content LONGTEXT NOT NULL,
 featured_image VARCHAR(255) NULL,
 status ENUM('draft','pending','published') NOT NULL DEFAULT 'draft',
 is_featured TINYINT(1) NOT NULL DEFAULT 0,
 views BIGINT UNSIGNED NOT NULL DEFAULT 0,
 published_at DATETIME NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP NULL DEFAULT NULL,
 CONSTRAINT fk_articles_category FOREIGN KEY(category_id) REFERENCES categories(id) ON DELETE SET NULL,
 CONSTRAINT fk_articles_author FOREIGN KEY(author_id) REFERENCES users(id) ON DELETE RESTRICT,
 INDEX idx_articles_public(status,published_at),
 INDEX idx_articles_category(category_id)
) ENGINE=InnoDB;

CREATE TABLE comments (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 article_id BIGINT UNSIGNED NOT NULL,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(190) NOT NULL,
 comment TEXT NOT NULL,
 status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_comments_article FOREIGN KEY(article_id) REFERENCES articles(id) ON DELETE CASCADE,
 INDEX idx_comments_status(status)
) ENGINE=InnoDB;

CREATE TABLE tags (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(80) NOT NULL UNIQUE,
 slug VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE article_tags (
 article_id BIGINT UNSIGNED NOT NULL,
 tag_id BIGINT UNSIGNED NOT NULL,
 PRIMARY KEY(article_id,tag_id),
 FOREIGN KEY(article_id) REFERENCES articles(id) ON DELETE CASCADE,
 FOREIGN KEY(tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE subscribers (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 email VARCHAR(190) NOT NULL UNIQUE,
 status ENUM('active','unsubscribed') NOT NULL DEFAULT 'active',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE advertisements (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(160) NOT NULL,
 image VARCHAR(255) NULL,
 target_url VARCHAR(500) NULL,
 placement VARCHAR(60) NOT NULL,
 starts_at DATETIME NULL,
 ends_at DATETIME NULL,
 is_active TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE settings (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 setting_key VARCHAR(100) NOT NULL UNIQUE,
 setting_value TEXT NULL
) ENGINE=InnoDB;

INSERT INTO categories(name,slug,description) VALUES
('News','news','Breaking news and current affairs.'),
('Business','business','Business, markets and entrepreneurship.'),
('Technology','technology','Technology, innovation and digital life.'),
('Lifestyle','lifestyle','People, culture, health and everyday living.'),
('Entertainment','entertainment','Film, music, arts and popular culture.'),
('Sports','sports','Sports news, analysis and interviews.');

INSERT INTO settings(setting_key,setting_value) VALUES
('site_name','My Magazine'),
('site_description','News, stories and ideas that matter.');
