<?php
require_once('Session.php');
require_once("database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Demo Test Bank</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--prevents html caching-->
    <meta http-equiv="pragma" content="no-cache" />

    <link rel="stylesheet" href="/css/CoreStyle.css?rndstr=<%= getRandomStr() %">
    <link rel="stylesheet" href="/css/ResponsiveTable.css?rndstr=<%= getRandomStr() %">
    <link rel="stylesheet" href="/css/RiseButton.css?rndstr=<%= getRandomStr() %">

    <!--Jquery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    <h1>Account Information</h1>
    <table class="rwd-table">
        <tr>
            <th>Receiver ID</th>
            <th>Amount</th>
            <th>Currency</th>
            <th>Purpose</th>
            <th style="text-align: center">Action</th>
        </tr>
        <?php
        $paymentToken = $_SESSION['token'];
        $callbackAddress = $_SESSION['callbackAddress'];
        $clientID = $_SESSION['clientID'];

        $m_database = Database::getInstance();
        $m_connection = $m_database->getConnection();

        $userID = $_SESSION['userID'];

        $statement = $m_connection->prepare("SELECT Balance, Currency FROM accounts WHERE id='$userID'");
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if($_SESSION['currency'] != $result['Currency'] || $_SESSION['amountToPay'] > $result['Balance'])
        {
            echo "<form class='denyForm' id='paymentForm' action='transactionStatus.php' method='post' style='float:left;'>";
            echo "<input type='hidden' name='token' value='$paymentToken'>";
            echo "<input type='hidden' name='paymentStatus' value='Denied'>";
            echo "<input type='hidden' name='clientID' value='$clientID'>";
            echo "</form>";
            echo "<script type='text/javascript'>document.getElementById('paymentForm').submit();</script>";
            die();
        }

            echo "<tr>";
            echo "<td data-th='Receiver ID'>" . $_SESSION['receiverID'] . "</td>";
            echo "<td data-th='Amount'>" . $_SESSION['amountToPay'] . "</td>";
            echo "<td data-th='Currency'>" . $_SESSION['currency'] . "</td>";
            echo "<td data-th='Purpose'>" . $_SESSION['purpose'] . "</td>";
            echo "<td data-th='Action'>";
                echo "<form id='paymentForm' action='transactionStatus.php' method='post' style='float:left;'>";
                    echo "<input type='hidden' name='token' value='$paymentToken'>";
                    echo "<input type='hidden' name='paymentStatus' value='Confirmed'>";
                    echo "<input type='hidden' name='clientID' value='$clientID'>";
                    echo "<button id='ConfirmButton' type='submit' class='raise'>Confirm</button>";
                echo "</form>";
                echo "<form id='paymentForm' action='transactionStatus.php' method='post' style='float:left;'>";
                    echo "<input type='hidden' name='token' value='$paymentToken'>";
                    echo "<input type='hidden' name='paymentStatus' value='Denied'>";
                    echo "<input type='hidden' name='clientID' value='$clientID'>";
                    echo "<button id='DenyButton' type='submit' class='raise'>Deny</button>";
                echo "</form>";
            echo "</td>";
            echo "</tr>";
        ?>
    </table>

    <a href="/php/logout.php"><button class="raise">Logout</button></a>
</body>
</html>
