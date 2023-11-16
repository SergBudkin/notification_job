<?php
function check_email( $email )
{
    sleep(rand(1, 60)); // имитация задержки
    return rand(0,1); // имитация ответа
}

function send_email( $from, $to, $text )
{
    sleep(rand(1, 10)); // имитация задержки

    return true;
}
