<?php
require_once("php/database.php");
require_once("php/tokenGenerator.php");
require_once("php/securityLogger.php");
require_once("php/whitelist.php");

// Building a whitelist array with keys which will send through the form, no others would be accepted later on
Whitelist(array('token','firstName','lastName','city','street','postalCode','country','email', 'phone', 'productID', 'productQuantity'), $_POST);

// define variables and set to empty values
$firstName = $lastName = $email = $city = $street = $postalCode = $country = $productID = $productQuantity = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (verifyFormToken('checkoutForm')) {
        if (!empty($_POST["firstName"])) {
            $firstName = normalizeInput($_POST["firstName"]);
        }
        if (!empty($_POST["lastName"])) {
            $lastName = normalizeInput($_POST["lastName"]);
        }
        if (!empty($_POST["city"])) {
            $city = normalizeInput($_POST["city"]);
        }
        if (!empty($_POST["street"])) {
            $street = normalizeInput($_POST["street"]);
        }
        if (!empty($_POST["email"])) {
            if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $email = normalizeInput($_POST["email"]);
            }
        }
        if (!empty($_POST["postalCode"])) {
            $postalCode = normalizeInput($_POST["postalCode"]);
        }
        if (!empty($_POST["country"])) {
            $country = normalizeInput($_POST["country"]);
        }
        if (!empty($_POST["phone"])) {
            $phone = normalizeInput($_POST["phone"]);
        }
        if (!empty($_POST["productID"])) {
            $productID = $_POST["productID"];
        }
        if (!empty($_POST["productQuantity"])) {
            $productQuantity = $_POST["productQuantity"];
        }

        $totalPrice = 0;

        $m_database = Database::getInstance();
        $m_connection = $m_database->getConnection();

        for ($i = 0; $i < count($productID); $i++) {
            $statement = $m_connection->prepare("SELECT Price FROM component WHERE id=$productID[$i]");
            $statement->execute();
            $product = $statement->fetch();
            $totalPrice += $product["Price"] * $productQuantity[$i];
        }

        $orderDate = date("Y.m.d");
        $buyerStatement = $m_connection->prepare("INSERT INTO client (firstName, lastName, city, address, country, email, postalCode, phoneNumber, orderDate, status) 
VALUES('$firstName', '$lastName', '$city', '$street', '$country', '$email', $postalCode, $phone, '$orderDate', 'Pending')");
        $buyerStatement->execute();

        $clientID = $m_connection->lastInsertId();

        for ($i = 0; $i < count($productID); $i++) {
            $orderStatement = $m_connection->prepare("INSERT INTO orders (component_id, amount, client_id) 
VALUES($productID[$i], $productQuantity[$i], $clientID)");
            $orderStatement->execute();
        }

        $paymentToken = generateFormToken('paymentForm');
        echo "<form id='paymentForm' action='https://demotestbank.eu/php/payment.php' method='post'>";
            echo "<input type='hidden' name='token' value='$paymentToken'>";
            echo "<input type='hidden' name='callbackAddress' value='https://printbase.eu/php/callback.php'>";
            echo "<input type='hidden' name='clientID' value='$clientID'>";
            echo "<input type='hidden' name='amountToPay' value='$totalPrice'>";
            echo "<input type='hidden' name='receiverID' value='0000001'>";
            echo "<input type='hidden' name='currency' value='EUR'>";
            echo "<input type='hidden' name='purpose' value='Print Base Purchase'>";
        echo "</form>";
        echo "<script type='text/javascript'>document.getElementById('paymentForm').submit();</script>";
    }
    else
    {
        writeLog('Formtoken');
        die("Hack-Attempt detected. Got ya!");
    }
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
	 <title>Print Base</title>
	 <meta charset="UTF-8">
	 <meta name="viewport" content="width=device-width, initial-scale=1">
	 <!--prevents html caching-->
	 <meta http-equiv="pragma" content="no-cache" />

	 <!--Style-->
	 <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
     <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
     <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">

	 <!--"?rndstr=<%= getRandomStr() %"		  this line prevents file caching-->
     <link rel="stylesheet" href="css/coreStyle.css?rndstr=<%= getRandomStr() %">

	 <!--Jquery-->
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	 <!--Javascript-->
	 <script src="scripts/coreBehavior.js?rndstr=<%= getRandomStr() %>"></script>
</head>
<body>

<?php
$newToken = generateFormToken('checkoutForm');
?>

	 <div class="w3-main">
	 	 <!--Title-->
	 	 <div id="m_Title" class="w3-large w3-center w3-top w3-red w3-button">
			 <h1 style="color:Black;">Print Base</h1>
	 	 </div>

         <img id="m_CheckoutButton" src="images/checkout_cart.png" class="w3-button" style="position:fixed;top:0;right:0;float:right;width:120px;height:90px;z-index: 1;">

         <form method="post" name="checkoutForm" id="m_CheckoutForm" class="row" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" style="margin-top:100px; margin-left:50px;margin-right:50px">
             <input type="hidden" name="token" value="<?php echo $newToken; ?>">
             <h1>Checkout:</h1>

             <div class="tab">Name:
                 <p><input class="formInput" type="text" placeholder="First name..." value="Vardenis" oninput="this.className = 'formInput';updateSummaryField('firstName', this.value)"></p>
                 <p><input class="formInput" type="text" placeholder="Last name..." value="Pavardenis" oninput="this.className = 'formInput';updateSummaryField('lastName', this.value)"></p>

             </div>

             <div class="tab">Address:
                 <p><input class="formInput" type="text" placeholder="City..." value="Kaunas" oninput="this.className = 'formInput';updateSummaryField('city', this.value)"></p>
                 <p><input class="formInput" type="text" placeholder="Street Address..." value="Mano g. 20" oninput="this.className = 'formInput';updateSummaryField('address', this.value)"></p>
                 <p><input class="formInput" type="text" placeholder="Postal Code..." value="55223" oninput="this.className = 'formInput';updateSummaryField('postalCode', this.value)"></p>
                 <p>
                     <select class="formInput" placeholder="Country..." value="Lithuania" oninput="this.className = 'formInput';updateSummaryField('country', this.value)">
                         <option value="Lithuania">Lithuania</option>
                         <option value="Poland">Poland</option>
                         <option value="Germany">Germany</option>
                         <option value="Latvia">Latvia</option>
                         <option value="Finland">Finland</option>
                         <option value="Estonia">Estonia</option>
                     </select>
                 </p>
             </div>

             <div class="tab">Contact Info:
                 <p><input class="formInput" type="email" placeholder="E-mail..." value="vardenis.pavardenis@gmail.com" oninput="this.className = 'formInput';updateSummaryField('email', this.value)"></p>
                 <p><input class="formInput" type="tel" placeholder="Phone..." value="862545235" oninput="this.className = 'formInput';updateSummaryField('phone', this.value)"></p>
             </div>

             <div class="tab">
                 <h1 style="text-align: center">Summary</h1>
                 <div class="m_Row formContainer">
                     <div>
                         <h3>Billing Address</h3>
                         <div class="m_Row">
                             <div class="col-50">
                                <label for="firstName">First Name</label>
                                <input readonly type="text" value="Vardenis" id="firstName" name="firstName">
                             </div>
                             <div class="col-50">
                                <label for="lastName">Last Name</label>
                                <input readonly type="text" value="Pavardenis" id="lastName" name="lastName">
                             </div>
                         </div>
                         <div class="m_Row">
                             <div class="col-50">
                                <label for="street">Street</label>
                                <input readonly type="text" value="Mano g. 20" id="address" name="street">
                             </div>
                             <div class="col-50">
                                 <label for="country">Street</label>
                                 <input readonly type="text" value="Lithuania" id="country" name="country">
                             </div>
                         </div>
                         <div class="m_Row">
                             <div class="col-50">
                                <label for="city">City</label>
                                <input readonly type="text" value="Kaunas" id="city" name="city">
                             </div>
                             <div class="col-50">
                                <label for="postalCode">Postal Code</label>
                                <input readonly type="text" value="55223" id="postalCode" name="postalCode">
                             </div>
                         </div>
                         <label for="email">Email</label>
                         <input readonly type="text" value="vardenis.pavardenis@gmail.com" id="email" name="email">
                         <label for="phone">Phone Number</label>
                         <input readonly type="text" value="862545235" id="phone" name="phone">
                     </div>
                 </div>
             </div>

             <div style="overflow:auto;">
                 <div style="float:right;">
                     <button type="button" id="m_CheckoutPrevBtn" onclick="TabSequence(-1)">Previous</button>
                     <button type="button" id="m_CheckoutNextBtn" onclick="TabSequence(1)">Next</button>
                 </div>
             </div>

             <!-- Circles which indicates the steps of the form: -->
             <div style="text-align:center;margin-top:40px;">
                 <span class="step"></span>
                 <span class="step"></span>
                 <span class="step"></span>
                 <span class="step"></span>
             </div>
             <div class="container" style="display:inline-block;width:100%">
                    <div>
                        <div id="m_ProductID"></div>
                        <div style="text-align:left;width:40%;float:left">
                            <h3>Name</h3>
                            <div id="m_ProductName"></div>
                        </div>
                        <div style="text-align:center;width:30%;float:left">
                            <h3>Quantity</h3>
                            <div id="m_ProductQuantity"></div>
                        </div>
                        <div style="text-align:center;width:20%;float:left">
                            <h3>Price</h3>
                            <div id="m_ProductPrice"></div>
                        </div>
                        <div style="text-align:center;width:10%;float:left">
                            <h3>Remove</h3>
                            <div id="m_ProductRemoval" style="width: 50px"></div>
                        </div>
                    </div>
                        <div>
                            <h4 style="float:left;width:100%"><b id="m_TotalPrice"></b></h4>
                        </div>
             </div>
         </form>


	 	 <div id="m_Catalogue">
	 	 	 <!-- Sidebar/menu -->
	 	 	 <nav id="m_sidebar" class="w3-collapse w3-large w3-padding">
	 	 	 	 <div class="w3-bar-block">
	 	 	 	 	 <h1 class="m_Header w3-xxlarge"><b></b></h1>
	 	 	 	 	 <ul id="m_SideMenu" style="list-style-type:none"></ul>
	 	 	 	 </div>
	 	 	 </nav>

	 	 	 <div class="w3-container m_Container">
	 	 	 	 <!-- Header -->
	 	 	 	 <div class="w3-container" style="margin-top:80px">
	 	 	 	 	 <h1 class="w3-xxxlarge">
	 	 	 	 	 	 <b class="m_Header"></b>
	 	 	 	 	 	 <input id="m_Search" type="text" placeholder="Search..">
	 	 	 	 	 </h1>
	 	 	 	 	 <hr style="width:50px;border:5px solid red" class="w3-round">
	 	 	 	 </div>
	 	 	 </div>

	 	 	 <div id="m_Content" class="w3-container m_Container">
	 	 	 </div>
	 	 </div>
	 	 <div id="m_Component" style="margin-top:90px;">
	 	 </div>
	 </div>
</body>
</html>