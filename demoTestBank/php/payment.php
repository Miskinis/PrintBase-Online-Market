<?php
require_once("whitelist.php");

Whitelist(array("token", "callbackAddress", "clientID", "amountToPay", "receiverID", "currency", "purpose"), $_POST);

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if(isset($_POST["token"]) && isset($_POST["callbackAddress"]) && isset($_POST["clientID"]) && isset($_POST["amountToPay"]) && isset($_POST["receiverID"]) && isset($_POST["currency"]) && isset($_POST["purpose"]))
    {
        session_start();
        $_SESSION['token'] = $_POST["token"];
        $_SESSION['callbackAddress'] = $_POST["callbackAddress"];
        $_SESSION['paymentStatus'] = "Pending";
        $_SESSION['clientID'] = $_POST["clientID"];
        $_SESSION['amountToPay'] = abs($_POST["amountToPay"]);
        $_SESSION['receiverID'] = $_POST["receiverID"];
        $_SESSION['currency'] = $_POST["currency"];
        $_SESSION['purpose'] = $_POST["purpose"];

        if(!isset($_SESSION['userID'])) {
            header("location:/php/login.php");
        }
        else {
            header("location:/php/pendingTransactions.php");
        }
    }
}
?>
