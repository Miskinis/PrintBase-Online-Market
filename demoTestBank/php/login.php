<?php
require_once("database.php");
require_once("securityLogger.php");
require_once("whitelist.php");
require_once("tokenGenerator.php");

// Building a whitelist array with keys which will send through the form, no others would be accepted later on
Whitelist(array('token','userID','password'), $_POST);

// define variables and set to empty values
$userID = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (verifyFormToken('LoginForm'))
    {
        if (!empty($_POST["userID"]))
        {
            $userID = normalizeInput($_POST["userID"]);
        }
        if (!empty($_POST["password"]))
        {
            $password = normalizeInput($_POST["password"]);
        }

        $m_database = Database::getInstance();
        $m_connection = $m_database->getConnection();

        $statement = $m_connection->prepare("SELECT FirstName, Balance FROM accounts WHERE UserID='$userID' AND Password='$password'");
        $statement->execute();

        if($statement->rowCount() > 0)
        {
            $result = $statement->fetch();
            $_SESSION['userID'] = $userID;
            $_SESSION['userName'] = $result['FirstName'];
            $_SESSION['userBalance'] = $result['Balance'];

            if($_SESSION['paymentStatus'] === "Pending")
            {
                header("location: /php/pendingTransactions.php");
            }
            else {
                header("location: /php/account.php");
            }
        }else {
            $error = "Your Login Name or Password is invalid";
        }
    }
    else
    {
        writeLog('Formtoken');
        die("Hack-Attempt detected. Got ya!");
    }
}

function wrapToString($data)
{
    return "'" . $data . "'";
}

function normalizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
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
    <link rel="stylesheet" href="/css/LoginForm.css?rndstr=<%= getRandomStr() %">

</head>
<body>
<?php
$newToken = generateFormToken('LoginForm');
?>
<div class="login">
	<h1>Login</h1>
    <form method="post" name="LoginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <input type="hidden" name="token" value="<?php echo $newToken; ?>">
        <p><?php if(isset($error)) {echo $error;} ?></p>
    	<input type="text" name="userID" placeholder="User ID" required="required" />
        <input type="password" name="password" placeholder="Password" required="required" />
        <button type="submit" class="btn btn-primary btn-block btn-large">Confirm</button>
    </form>
</div>
</body>