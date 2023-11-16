## БД
Таблица **users**
```
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `validts` int DEFAULT '0',
  `confirmed` tinyint unsigned DEFAULT '0',
  `checked` tinyint unsigned DEFAULT '0',
  `valid` tinyint unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `users_validts_IDX` (`validts`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6016 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;  
```
  
  

Таблица **notificate_jobs**  
```
CREATE TABLE IF NOT EXISTS `notificate_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `working` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id_foreign` (`user_id`),
  CONSTRAINT `user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=256 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```
=========================================================================

## cronjob
Нужно на крон повесить 3 скрипта
```
 00 00 * * * /usr/bin/php /path/to/cron_create_jobs.php  
 * * * * * /usr/bin/php /path/to/cron_check_emails_caller.php  
 * * * * * /usr/bin/php /path/to/cron_notification_caller.php
```
- Первый скрипт отбирает всех пользователей, у которых подписка заканчивается через 1 или через 3 дня и вставляет полученные id пользователей в таблицу задач (**notificate_jobs**).
- Второй скрипт в "потоке" вызывает скрипт проверки **email** из таблицы **users** 
- Третий скрипт в "потоке" вызывает скрипт отправки сообщений из задач таблицы **notificate_jobs**

---

В файле **inc/conf.php** находятся настройки подключения к БД, а так же некоторые параметры связанные с количеством потоков для вызова скриптов в кроне