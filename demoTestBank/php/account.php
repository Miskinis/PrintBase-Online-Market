<?php
require_once('database.php');
require_once('Session.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Demo Test Bank</title>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--prevents html caching-->
        <meta http-equiv="pragma" content="no-cache" />

        <link rel="stylesheet" href="/css/CoreStyle.css">
        <link rel="stylesheet" href="/css/ResponsiveTable.css">
        <link rel="stylesheet" href="/css/RiseButton.css">

    </head>
    <body>
        <h1>Account Information</h1>
        <table class="rwd-table">
            <tr>
                <th>Owner</th>
                <th>Balance</th>
                <th>Currency</th>
            </tr>
            <?php

            $m_database = Database::getInstance();
            $m_connection = $m_database->getConnection();

            $userID = $_SESSION['userID'];
            $statement = $m_connection->prepare("SELECT FirstName, Balance, Currency FROM accounts WHERE UserID=$userID");
            $statement->execute();

            while ($row = $statement->fetch(PDO::FETCH_ASSOC))
            {
                echo "<tr>";
                echo "<td data-th='Owner'>" . $row['FirstName'] . "</td>";
                echo "<td data-th='Balance'>" . $row['Balance'] . "</td>";
                echo "<td data-th='Currency'>" . $row['Currency'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <h1>Transaction History</h1>
        <table class="rwd-table">
            <tr>
                <th>Transaction ID</th>
                <th>Receiver</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Purpose</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php
            $userID = $_SESSION['userID'];
            $statement = $m_connection->prepare("SELECT * FROM history WHERE accounts_id IN (SELECT id FROM accounts WHERE UserID=$userID)");
            $statement->execute();

            while ($row = $statement->fetch(PDO::FETCH_ASSOC))
            {
                echo "<tr>";
                    echo "<td data-th='Transaction ID'>" . $row['id'] . "</td>";
                    echo "<td data-th='Receiver'>" . $row['Receiver'] . "</td>";
                    echo "<td data-th='Amount'>" . $row['Amount'] . "</td>";
                    echo "<td data-th='Currency'>" . $row['Currency'] . "</td>";
                    echo "<td data-th='Purpose'>" . $row['Purpose'] . "</td>";
                    echo "<td data-th='Request Time'>" . $row['LastChanged'] . "</td>";
                    echo "<td data-th='Status'>" . $row['PaymentStatus'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>

        <a href="/php/logout.php"><button class="raise">Logout</button></a>
    </body>
</html>