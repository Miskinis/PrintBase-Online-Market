<?php
require_once("securityLogger.php");
function Whitelist($whitelist, $post)
{
    foreach ($post as $key => $item) {

        // Check if the value $key (fieldname from $_POST) can be found in the whitelisting array, if not, die with a short message to the hacker
        if (!in_array($key, $whitelist)) {
            writeLog('Unknown form fields');
            die("Hack-Attempt detected. Please use only the fields in the form");
        }
    }
}
?>