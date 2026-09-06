CREATE DATABASE IF NOT EXISTS om_shanthi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE om_shanthi;

CREATE TABLE admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE programs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    slug VARCHAR(190) NOT NULL UNIQUE,
    short_description TEXT NOT NULL,
    description LONGTEXT NOT NULL,
    icon VARCHAR(80) DEFAULT 'heart',
    image VARCHAR(255) DEFAULT NULL,
    status ENUM('draft','published') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE news_articles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(220) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    category VARCHAR(100) DEFAULT 'Updates',
    author VARCHAR(120) DEFAULT 'Om Shanthi Trust',
    status ENUM('draft','published') DEFAULT 'draft',
    published_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE events (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(220) NOT NULL,
    description TEXT NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME NULL,
    venue VARCHAR(220) DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    registration_limit INT UNSIGNED DEFAULT NULL,
    status ENUM('upcoming','completed','cancelled') DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE volunteers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(40),
    city VARCHAR(120),
    interests VARCHAR(255),
    availability VARCHAR(120),
    skills TEXT,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(40),
    subject VARCHAR(220),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE donations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    donor_name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    frequency ENUM('one_time','monthly') DEFAULT 'one_time',
    program_id INT UNSIGNED NULL,
    status ENUM('pending','paid','failed','cancelled') DEFAULT 'pending',
    provider_reference VARCHAR(190) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE SET NULL
);

CREATE TABLE gallery (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    category VARCHAR(100) NOT NULL,
    image VARCHAR(255) NOT NULL,
    verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE faqs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(100) NOT NULL,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT DEFAULT 0,
    status ENUM('published','draft') DEFAULT 'published'
);

CREATE TABLE settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(120) NOT NULL UNIQUE,
    setting_value TEXT
);

INSERT INTO settings(setting_key, setting_value) VALUES
('mission','To support people facing hardship with compassion, dignity and practical assistance while creating opportunities for education and a stronger community.'),
('vision','A compassionate community where every person has access to essential support, opportunity and hope.'),
('contact_email','Add official email'),
('contact_phone','Add official phone'),
('address','Add verified foundation address'),
('facebook',''),
('instagram',''),
('youtube',''),
('whatsapp','');

INSERT INTO programs(title,slug,short_description,description,icon) VALUES
('Food Support','food-support','Support for people facing food insecurity.','Sample content — replace with verified program information.','utensils'),
('Clothing Support','clothing-support','Essential clothing assistance for families in need.','Sample content — replace with verified program information.','shirt'),
('Education Support','education-support','Helping learners access educational opportunities.','Sample content — replace with verified program information.','book-open'),
('Child Support','child-support','Compassionate support focused on children.','Sample content — replace with verified program information.','child'),
('Community Welfare','community-welfare','Community-focused humanitarian assistance.','Sample content — replace with verified program information.','users'),
('Essential Assistance','essential-assistance','Practical support for essential needs.','Sample content — replace with verified program information.','hand-heart');

INSERT INTO faqs(category,question,answer,sort_order) VALUES
('Donations','How can I support the foundation?','Use the donation flow or contact the foundation for verified contribution instructions.',1),
('Volunteering','How can I volunteer?','Submit the volunteer form and the team can review your availability and interests.',2),
('Programs','How are programs managed?','Program details should be maintained through the admin CMS using verified organizational information.',3),
('Transparency','Where can I find official information?','Official registrations, statistics and financial information should be added only after verification.',4);
