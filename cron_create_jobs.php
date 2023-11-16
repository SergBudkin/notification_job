<?php
/*
Скрипт вызывается по крону 1 раз в день (в полночь)
Определяет всех тех пользователей, у которых подписка заканчивается через 1 или через 3 дня и
вставляет полученные id пользователей в таблицу задач (notificate_jobs).
*/

require_once ('inc/conf.php');

$mysqli = new mysqli($CFG["host"], $CFG["username"], $CFG["password"], $CFG["database"]);

$oneDayDate = strtotime(date('Y-m-d', strtotime("+1 day"))); // начало завтрешнего дня
$oneDayDateEnd = strtotime(date('Y-m-d 23:59:59', $oneDayDate)); // конец завтрешнего дня

$treeDayDate = strtotime(date('Y-m-d', strtotime("+3 day"))); // начало завтрешнего дня
$treeDayDateEnd = strtotime(date('Y-m-d 23:59:59', $treeDayDate)); // конец завтрешнего дня


$res = $mysqli->query("INSERT INTO `notificate_jobs` (`user_id`)
                        SELECT `id` FROM `users` WHERE
                                        (`validts` BETWEEN " . $oneDayDate . " AND " . $oneDayDateEnd . " OR `validts` BETWEEN " . $treeDayDate . " AND " . $treeDayDateEnd . ")
                                        AND (`checked`=0 OR (`checked`=1 AND `valid`=1))
                         ");

$mysqli->close();
echo 'Done.';
