<?php
$CFG = [
    'host' => 'host',
    'username' => 'user',
    'password' => 'password',
    'database' => 'db',
];
$CFG["days_before_checking"] = 5; // Количество дней до истечения подписки, чтобы заранее проверить email пользователя на валидацию
$CFG["email_check_threads"] = 10; // Количество потоков для проверки email
$CFG["notification_threads"] = 20; // Количество потоков для отправки нотификаций // 28800 рассылок в день (должно хватить)

$CFG['email_from'] = 'info@domain.com'; // почта отправителя
