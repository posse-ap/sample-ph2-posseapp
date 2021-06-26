DROP SCHEMA IF EXISTS posse;
CREATE SCHEMA posse;
USE posse;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  family_name VARCHAR(10) NOT NULL,
  first_name VARCHAR(10) NOT NULL,
  family_name_hira VARCHAR(10) NOT NULL,
  first_name_hira VARCHAR(10) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  generation TINYINT UNSIGNED NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users SET family_name='テスト', first_name='テスト', family_name_hira='テスト', first_name_hira='テスト', email='testtest@com', password=sha1('password'), generation=1;

DROP TABLE IF EXISTS study_hours_posts;
CREATE TABLE study_hours_posts (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  user_id INT NOT NULL,
  total_hour FLOAT NOT NULL,
  study_date DATE NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO study_hours_posts SET user_id=1, total_hour=1.5, study_date='2021-03-10';

DROP TABLE IF EXISTS language_posts;
CREATE TABLE language_posts (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  study_hours_post_id INT NOT NULL,
  language_id INT NOT NULL,
  hour FLOAT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO language_posts SET study_hours_post_id=1, language_id=1, hour=0.5;
INSERT INTO language_posts SET study_hours_post_id=1, language_id=3, hour=0.5;
INSERT INTO language_posts SET study_hours_post_id=1, language_id=6, hour=0.5;

DROP TABLE IF EXISTS content_posts;
CREATE TABLE content_posts (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  study_hours_post_id INT NOT NULL,
  content_id INT NOT NULL,
  hour FLOAT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO content_posts SET study_hours_post_id=1, content_id=1, hour=0.75;
INSERT INTO content_posts SET study_hours_post_id=1, content_id=3, hour=0.75;

DROP TABLE IF EXISTS languages;
CREATE TABLE languages (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  language VARCHAR(255) NOT NULL,
  color_code VARCHAR(10) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO languages SET language='HTML', color_code='#0345ec';
INSERT INTO languages SET language='CSS', color_code='#0f71bd';
INSERT INTO languages SET language='JavaScript', color_code='#20bdde';
INSERT INTO languages SET language='PHP', color_code='#3ccefe';
INSERT INTO languages SET language='Laravel', color_code='#b29ef3';
INSERT INTO languages SET language='SQL', color_code='#6d46ec';
INSERT INTO languages SET language='SHELL', color_code='#4a17ef';
INSERT INTO languages SET language='情報システム基礎知識(その他)', color_code='#3105c0';

DROP TABLE IF EXISTS contents;
CREATE TABLE contents (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  content VARCHAR(255) NOT NULL,
  color_code VARCHAR(10) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO contents SET content='ドットインストール', color_code='#0345ec';
INSERT INTO contents SET content='N予備校', color_code='#0f71bd';
INSERT INTO contents SET content='POSSE課題', color_code='#20bdde';
