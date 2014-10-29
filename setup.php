<?
require "config.php";
require "functions.php";

import("package/Database.php");
import("package/Request.php");

function error($message) {
    ?>
    <div class="message error-message">
        <?=$message;?>
    </div>
<?
}

function success($message) {
    ?>
    <div class="message success-message">
        <?=$message;?>
    </div>
    <?
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>Настройка сайта</title>

    <link rel="stylesheet" href="/design/css/style.css" type="text/css">
    <link rel="stylesheet" href="/design/css/style-ui.css" type="text/css">

    <link rel="icon" href="/design/images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/design/images/favicon.ico" type="image/x-icon">
</head>
<body>
<div class="container">
<div class="default_page">
<?
$sth = Database::gi()->execute("DROP TABLE IF EXISTS `users`");
$sth = Database::gi()->execute("CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL auto_increment,
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `permissions` int(10) unsigned NOT NULL default '0',
  `email` varchar(100) NOT NULL,
  `creation_time` datetime NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

if ($sth->errorCode() == 0) {
    success("Модуль пользователей настроен.");
} else {
    error("Ошибка в настройке модуля пользователей.");
}

$sth = Database::gi()->execute("DROP TABLE IF EXISTS `posts`");
$sth = Database::gi()->execute("CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL default '0',
  `content_type` tinyint(3) unsigned NOT NULL default '0',
  `creation_time` datetime NOT NULL,
  `title` varchar(255) NOT NULL,
  `thumbnail_url` varchar(255) default NULL,
  `content_url` varchar(255) default NULL,
  `meta_text` text,
  `cached_text` text,
  `positive` smallint(6) unsigned NOT NULL default '0',
  `negative` smallint(6) unsigned NOT NULL default '0',
  `popularity` float unsigned NOT NULL default '0',
  `popular_page` int(10) unsigned NOT NULL default '0',
  `fresh_page` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`post_id`),
  KEY `user_id` (`user_id`),
  KEY `popular_page` (`popular_page`),
  KEY `fresh_page` (`fresh_page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

if ($sth->errorCode() == 0) {
    success("Модуль постов настроен.");
} else {
    error("Ошибка в настройке модуля постов.");
}

$sth = Database::gi()->execute("DROP TABLE IF EXISTS `comments`");
$sth = Database::gi()->execute("CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `meta_text` varchar(3000) default NULL,
  `cached_text` varchar(3000) default NULL,
  `creation_time` datetime NOT NULL,
  PRIMARY KEY  (`comment_id`),
  KEY `post_id` (`post_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

if ($sth->errorCode() == 0) {
    success("Модуль комментариев настроен.");
} else {
    error("Ошибка в настройке модуля комментариев.");
}

$sth = Database::gi()->execute("DROP TABLE IF EXISTS `tags`");
$sth = Database::gi()->execute("CREATE TABLE IF NOT EXISTS `tags` (
  `tag_id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY  (`tag_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

if ($sth->errorCode() == 0) {
    success("Модуль тегов успешно настроен.");
} else {
    error("Ошибка в настройке модуля тегов.");
}

$sth = Database::gi()->execute("DROP TABLE IF EXISTS `posts_tags`");
$sth = Database::gi()->execute("CREATE TABLE IF NOT EXISTS `posts_tags` (
  `post_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  KEY `combined` (`post_id`,`tag_id`),
  KEY `post_id` (`post_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$sth = Database::gi()->execute("DROP TABLE IF EXISTS `users_votes`");
$sth = Database::gi()->execute("CREATE TABLE IF NOT EXISTS `users_votes` (
  `user_id` int(10) unsigned NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `direction` tinyint(4) NOT NULL,
  KEY `combined` (`user_id`,`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

if ($sth->errorCode() == 0) {
    success("Модуль рейтинга успешно настроен.");
} else {
    error("Ошибка в настройке модуля рейтинга.");
}
?>

    <div class="message primary-message">
        Повторный вызов данного скрипта приведет к полному удалению данных!
    </div>
</div>
</div>
</body>
</html>