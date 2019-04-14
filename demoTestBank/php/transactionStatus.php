<?php
require_once('database.php');
require_once('whitelist.php');
require_once('Session.php');
Whitelist(array('token', 'paymentStatus', 'clientID'), $_POST);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['token']) && isset($_POST['paymentStatus']) && isset($_POST['clientID']))
    {
        $paymentToken = $_SESSION['token'];
        $callbackAddress = $_SESSION['callbackAddress'];
        $clientID = $_SESSION['clientID'];

        $status = $_POST['paymentStatus'];
        $date = Date("Y.m.d");

        $userID = $_SESSION['userID'];
        $amountToPay = $_SESSION['amountToPay'];
        $userID = $_SESSION['userID'];
        $amountToPay = $_SESSION['amountToPay'];
        $receiverID = $_SESSION['receiverID'];
        $currency = $_SESSION['currency'];
        $purpose = $_SESSION['purpose'];
        $lastChanged = Date("Y.m.d");

        $m_database = Database::getInstance();
        $m_connection = $m_database->getConnection();

        $statement = $m_connection->prepare("INSERT INTO history (Receiver, Currency, Amount, Purpose, LastChanged, PaymentStatus, accounts_id)
VALUES('$receiverID', '$currency', $amountToPay, '$purpose', '$lastChanged', '$status', '$userID')");
        $statement->execute();

        if($status === "Confirmed") {
            $SenderStatement = $m_connection->prepare("UPDATE accounts SET Balance=Balance-$amountToPay WHERE id='$userID'");
            $SenderStatement->execute();

            $ReceiverStatement = $m_connection->prepare("UPDATE accounts SET Balance=Balance+$amountToPay WHERE id='$userID'");
            $ReceiverStatement->execute();
        }

        echo "<form class='denyForm' id='paymentForm' action='$callbackAddress' method='post' style='float:left;'>";
            echo "<input type='hidden' name='token' value='$paymentToken'>";
            echo "<input type='hidden' name='paymentStatus' value='$status'>";
            echo "<input type='hidden' name='clientID' value='$clientID'>";
        echo "</form>";
        echo "<script type='text/javascript'>document.getElementById('paymentForm').submit();</script>";

        session_destroy();
    }
}
?>