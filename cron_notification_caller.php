<?php
/*
Скрипт вызывается по крону 1 раз в минуту
Скрипт одновременно в несколько "потока" вызывает другой скрипт, который делает проверку
*/

require_once ('inc/conf.php');

for($i=0; $i<$CFG['notification_threads']; $i++){
    exec('php -f '.__DIR__.'/cron_notification_worker.php '.$CFG['notification_threads'].' '.$i.' > /dev/null 2>&1 &');
    //exec('php -f '.__DIR__.'/cron_notification_worker.php '.$CFG['notification_threads'].' '.$i.' ', $out);

    //var_dump($out);
}


