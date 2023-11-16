## БД
Необходимо создать новую таблицу **notificate_jobs**  
```
create table `notificate_jobs`  
(  
    id bigint unsigned auto_increment primary key,  
    user_id bigint unsigned not null,  
    working tinyint unsigned not null default 0,  
    constraint user_id_foreign  
        foreign key (user_id) references users (id)  
            on delete cascade  
) collate = utf8mb4_unicode_ci;  
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