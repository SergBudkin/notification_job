<?php
/*
Скрипт вызывается через скрипт cron_check_emails_caller.php по крону 1 раз в минуту.
Принимает 2 параметра - делитель и остаток - для того, чтоб при одновременном вызове разных копий серипта - не отбирались одни и те же записи из таблицы
Находит пользователей С ПОДПИСКОЙ, которая заканчивается через Х дня (но не менее 3) и у которых не проверен email (поле checked) и
отправляет email на проверку.
Скрипт предназначен для разгрузки скрипта отправки уведомлений об истекающих подписках (чтобы там проверять как можно меньше или вовсе не проверять валидные email-ы )
*/

$divided = (int) $_SERVER['argv'][1];
$remainder = (int) $_SERVER['argv'][2];
if(!$divided || $divided<$remainder) exit('incorrect params');


require_once ('inc/conf.php');
require_once ('inc/functions.php');

$mysqli = new mysqli($CFG["host"], $CFG["username"], $CFG["password"], $CFG["database"]);

$DayDate = strtotime("+".$CFG["days_before_checking"]." day"); // можно отредактировать




$res = $mysqli->query("SELECT * FROM `users` WHERE
                                        `validts` > " . $DayDate . "
                                        AND `confirmed` = 0
                                        AND `checked`= 0
                                        AND `id`%". $divided ." = ". $remainder ."
                                        LIMIT 1");

$row = $res->fetch_assoc();
if($row){
    $checkEmail = (int) check_email($row['email']);
    $mysqli->query("UPDATE `users` SET `checked`=1, `valid`=" . $checkEmail . "
                                            WHERE `id` = " . $row['id'] . "
                             ");
}

$mysqli->close();
