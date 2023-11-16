<?php
/*
Скрипт вызывается через скрипт cron_notification_caller.php по крону 1 раз в минуту.
Принимает 2 параметра - делитель и остаток - для того, чтоб при одновременном вызове разных копий серипта - не отбирались одни и те же записи из таблицы

*/
error_reporting(E_ALL);
ini_set('display_errors', 1);


$divided = (int) $_SERVER['argv'][1];
$remainder = (int) $_SERVER['argv'][2];
if(!$divided || $divided<$remainder) exit('incorrect params');


require_once ('inc/conf.php');
require_once ('inc/functions.php');

$mysqli = new mysqli($CFG["host"], $CFG["username"], $CFG["password"], $CFG["database"]);


$res = $mysqli->query("SELECT j.*, u.username, u.email, u.confirmed, u.checked FROM `notificate_jobs` j
                                        LEFT JOIN `users` u ON (j.user_id = u.id)
                                        WHERE j.`working` = 0
                                        AND u.`id`%". $divided ." = ". $remainder ."
                                        LIMIT 1");

$row = $res->fetch_assoc();
if($row){
    // ставим отметку, что задача выполняется
    $mysqli->query("UPDATE `notificate_jobs` SET `working`=1 WHERE `id` = ".$row['id']);

    if(!$row['confirmed'] && !$row['checked']){
        // почта не проверена - проверяем
        $checkEmail = (int) check_email($row['email']);

        // ставим отметку в таблице users, что проверили email
        $mysqli->query("UPDATE `users` SET `checked`=1, `valid`=" . $checkEmail . "
                                            WHERE `id` = " . $row['user_id'] . "
                             ");

        if(!$checkEmail){
            // Почта не прошла проверку - завершаем скрипт
            $mysqli->query("DELETE FROM `notificate_jobs` WHERE `id` = ".$row['id']);
            $mysqli->close();
            exit('done');
        }
    }

    // отправляем почту
    send_email( $CFG['email_from'], $row['email'], $row['username']. " , your subscription is expiring soon" );

    $mysqli->query("DELETE FROM `notificate_jobs` WHERE `id` = ".$row['id']);
}

$mysqli->close();
