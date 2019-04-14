<?php
function writeLog($where)
{

    $ip = $_SERVER["REMOTE_ADDR"]; // Get the IP from superglobal
    $host = gethostbyaddr($ip);    // Try to locate the host of the attack
    $date = date("Y.m.d");

// create a logging message with php heredoc syntax
    $logging = <<<LOG
    \n
<< Start of Message >>
There was a hacking attempt on your form. \n
Date of Attack: {$date}
IP-Address: {$ip} \n
Host of Attacker: {$host}
Point of Attack: {$where}
<< End of Message >>
LOG;

// open log file
    if ($handle = fopen('..\logs\hacklog.log', 'a')) {

        fputs($handle, $logging);  // write the Data to file
        fclose($handle);           // close the file

    } else {  // if first method is not working, for example because of wrong file permissions, email the data
        $to = 'ADMIN@gmail.com';
        $subject = 'HACK ATTEMPT';
        $header = 'From: ADMIN@gmail.com';
        if (mail($to, $subject, $logging, $header)) {
            echo "Sent notice to admin.";
        }

    }
}
?>