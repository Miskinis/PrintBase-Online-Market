<?php
require_once("database.php");
require_once("tokenGenerator.php");
require_once("whitelist.php");

$m_database = Database::getInstance();
$m_connection = $m_database->getConnection();

Whitelist(array('token', 'paymentStatus', 'clientID'), $_POST);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (verifyFormToken('paymentForm')) {
        if (isset($_POST['token']) && isset($_POST['paymentStatus']) && isset($_POST['clientID'])) {
            $clientID = $_POST['clientID'];
            switch ($_POST['paymentStatus']) {
                case "Confirmed":
                    $statement = $m_connection->prepare("UPDATE client SET status='Confirmed' WHERE id=$clientID");
                    $statement->execute();
                    header("location:success.php");
                    break;
                case "Denied":
                    $statement = $m_connection->prepare("UPDATE client SET status='Denied' WHERE id=$clientID");
                    $statement->execute();
                    header("location:failure.php");
                    break;
            }
            session_destroy();
        }
    }
}

?>