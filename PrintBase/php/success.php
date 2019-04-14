<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment Response From Print Base</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--prevents html caching-->
    <meta http-equiv="pragma" content="no-cache" />
    <style>
        div {
            height: 300px;
            width: 500px;
            background-color: #f2f2f2;
            padding: 5px 20px 15px 20px;
            border: 1px solid lightgrey;
            border-radius: 3px;

            position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -150px;
            margin-left: -250px;
        }
        p{
            font-family: Garamond;
            text-align: center;
            vertical-align: middle;
            position: relative;
            top: 20%;
            transform: translateY(-50%);
        }
    </style>
</head>
<body>
<?php
header( "refresh:3;/index.php" );
?>
<div>
    <p style="font-size: 40px"><strong>Payment Successful</strong></p>
    <p style="font-size: 30px"><strong>Thank you for your purchase</strong></p>
</div>
</body>