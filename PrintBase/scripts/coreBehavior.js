var properties = {};
var propertiesCount = 0;
var shoppingCart = {};

$(document).ready(function () {
    $("#m_CheckoutForm").hide();
	 CreateCategories();

	 //Turn Title into 'Home' button
	 $("#m_Title").click(function () {
         $("#m_CheckoutForm").hide();
		  RemoveContent();
		  properties = [];
		  CreateCategories();
	 });

    $("#m_CheckoutButton").click(function (){
        RemoveContent();
        $("#m_Catalogue").hide();
        CreateShoppingCart();
        $("#m_CheckoutForm").show();
        DisplayTab(currentTab); // Display the current tab
    });

	 //Search bar magic
	 $("#m_Search").on("change paste keyup", function () {
		  $.ajax({
				type: 'POST',
				url: "PHP/getComponentsByName.php",
				data: { "componentName": $("#m_Search").val() },
				datatype: "json",
				cache: false,
				beforeSend: function () {
					 RemoveContent();
					 $("#m_SideMenu").append(document.createElement("p").appendChild(document.createTextNode("Search by name does not support filters")));
				},
				success: function (result) {
					 try {
						  var parsedData = $.parseJSON(result);
						  $.each(parsedData, function (index, m_object) {
								//Components
								CreateInteractableImage(m_object.Name, m_object.imagePath, function () { CreateComponent(m_object.id) });
						  });
					 }
					 catch (err) {
						  $("#m_Content").append(document.createElement("p").appendChild(document.createTextNode("No Components Found")));
					 }
				}
		  });
	 });
});

//Displays Categories as clickable images and as Side Menu buttons
function CreateCategories() {
	 $.ajax({
		  type: 'GET',
		  url: "PHP/getCategories.php",
		  datatype: "json",
		  cache: false,
		  beforeSend: function () {
				RemoveContent();
				$("#m_Catalogue").show();
				$("#m_Search").val("");
		  },
		  success: function (result) {
				try {
					 var parsedData = $.parseJSON(result);
					 $.each(parsedData, function (index, m_object) {
						  //Side Menu
						  var p1 = document.createElement("p");
						  p1.className = "w3-bar-item w3-button w3-hover-white";
						  p1.appendChild(document.createTextNode(m_object.name));

						  var li1 = document.createElement("li");
						  li1.appendChild(p1);

						  p1.onclick = function () {
								CreateComponents(m_object.name);
						  };

						  $("#m_SideMenu").append(li1);

						  //Categories
						  CreateInteractableImage(m_object.name, m_object.imagePath, function () { CreateComponents(m_object.name) });
					 });

					 $(".m_Header").text("Categories");
				}
				catch (err) {
					 $("#m_SideMenu").append(document.createElement("p").appendChild(document.createTextNode("No Categories Found")));
					 $("#m_Content").append(document.createElement("p").appendChild(document.createTextNode("No Categories Found")));
				}
		  }
	 });
}

//Clean up function
function RemoveContent() {
	 $("#m_Content").empty();
	 $("#m_SideMenu").empty();
	 $(".m_Header").text("");
	 $("#m_Component").empty();
	 properties = {};
}

//Displays components as clickable images and their filters in a side menu
function CreateComponents(categoryName) {
	 $.ajax({
		  type: 'POST',
		  url: "PHP/getComponents.php",
		  data: { "categoryName": categoryName },
		  datatype: "json",
		  cache: false,
		  beforeSend: function () {
				RemoveContent();
				$("#m_Search").val("");
		  },
		  success: function (result) {
				try {
					 var parsedData = $.parseJSON(result);

					 //Side Menu
					 var propertyNames = Object.getOwnPropertyNames(parsedData[0]);
					 for (var i = 6; i < propertyNames.length; i++) {
						  var p1 = document.createElement("p");
						  p1.className = "w3-bar-item";
						  p1.appendChild(document.createTextNode(propertyNames[i]));

						  var li1 = document.createElement("li");
						  li1.appendChild(p1);

						  CreateProperties(propertyNames[i], li1, categoryName)

						  $("#m_SideMenu").append(li1);
					 };

					 $.each(parsedData, function (index, m_object) {
						  //Components
						  CreateInteractableImage(m_object.Name, m_object.imagePath, function () { CreateComponent(m_object.id) });
					 });

					 $(".m_Header").text(categoryName);
				}
				catch (err) {
					 $("#m_SideMenu").append(document.createElement("p").appendChild(document.createTextNode("No Components Found")));
					 $("#m_Content").append(document.createElement("p").appendChild(document.createTextNode("No Components Found")));
				}
		  }
	 });
}

//Creates clickable image
function CreateInteractableImage(Name, imagePath, m_onClick) {
	 var div3 = document.createElement("div");
	 div3.className = "desc";
	 div3.appendChild(document.createTextNode(Name));

	 var img1 = document.createElement("img");
	 img1.src = imagePath;
	 img1.alt = Name;

	 var div2 = document.createElement("div");
	 div2.className = "gallery";
	 div2.appendChild(div3);
	 div2.appendChild(img1);

	 var div1 = document.createElement("div");
	 div1.className = "responsive";
	 div1.appendChild(div2);

	 div1.onclick = function () {
		  m_onClick();
	 };

	 $("#m_Content").append(div1);
}

function CreateProperties(propertyName, parent, categoryName) {
	 var ul1 = document.createElement("ul");
	 $.ajax({
		  type: 'POST',
		  url: "PHP/getProperty.php",
		  data: { "propertyName": propertyName },
		  datatype: "json",
		  cache: false,
		  success: function (result) {
				try {
					 var parsedData = $.parseJSON(result);

					 //Side Menu
					 $.each(parsedData, function (index, property) {
					      var checkboxDiv = document.createElement("div");
					      checkboxDiv.className="propertyCheckbox";
						  var propertyLabel = document.createElement("p");
						  propertyLabel.style="display:inline-block;margin-left:5px";
                          propertyLabel.appendChild(document.createTextNode(property));
						  var propertyCheckbox = document.createElement("input");
						  propertyCheckbox.id=property;
						  propertyCheckbox.type = "checkbox";
                          propertyCheckbox.value="None";
                          propertyCheckbox.name="check";
                          propertyCheckbox.checked="";
						  propertyCheckbox.onchange = function () {
								if (propertyCheckbox.checked) {
									 if (!(propertyName in properties)) properties[propertyName] = [];
									 properties[propertyName].push(property);
									 propertiesCount++;
								}
								else {
									 properties[propertyName].splice($.inArray(property, properties[propertyName]), 1);
									 propertiesCount--;
									 if (properties[propertyName].length == 0) {
										  delete properties[propertyName];
									 }
								}

								if (propertiesCount > 0) {
									 FilterComponents(categoryName);
								}
								else {
									 CreateComponents(categoryName);
								}
						  }
						  var propertyCheckmark = document.createElement("label");
                         propertyCheckmark.setAttribute("for", property);
                         checkboxDiv.appendChild(propertyCheckbox);
                         checkboxDiv.appendChild(propertyCheckmark);
                         var propertyDiv = document.createElement("div");
                         propertyDiv.appendChild(checkboxDiv);
                         propertyDiv.appendChild(propertyLabel);
                         ul1.appendChild(propertyDiv);
					 });
				}
				catch (err) {
					 ul1.appendChild(document.createElement("p").appendChild(document.createTextNode("No Component Properties Found")));
				}
		  }
	 });
	 parent.appendChild(ul1);
}

//Display components based on selected filters
function FilterComponents(categoryName) {
	 $.ajax({
		  type: 'POST',
		  url: "PHP/filterComponents.php",
		  data: { "categoryName": categoryName, "properties": JSON.stringify(properties) },
		  datatype: "json",
		  cache: false,
		  beforeSend: function () {
				$("#m_Content").empty();
		  },
		  success: function (result) {
				var parsedData = $.parseJSON(result);
				if (parsedData == "") {
					 $("#m_Content").append(document.createElement("p").appendChild(document.createTextNode("No Components Found")));
					 return 0;
				}

				//Components
				$.each(parsedData, function (index, m_object) {
					 var div3 = document.createElement("div");
					 div3.className = "desc";
					 div3.appendChild(document.createTextNode(m_object.Name));

					 var img1 = document.createElement("img");
					 img1.src = m_object.imagePath;
					 img1.alt = m_object.Name;

					 var div2 = document.createElement("div");
					 div2.className = "gallery";
					 div2.appendChild(div3);
					 div2.appendChild(img1);

					 var div1 = document.createElement("div");
					 div1.className = "responsive";
					 div1.appendChild(div2);

					 div1.onclick = function () {
						  CreateComponent(m_object.id);
					 };

					 $("#m_Content").append(div1);
				});
		  }
	 });
}

function CreateShoppingCart() {
    $("#m_ProductName").empty();
    $("#m_ProductQuantity").empty();
    $("#m_ProductPrice").empty();
    $("#m_ProductRemoval").empty();
    $("#m_ProductID").empty();

    var totalPrice = 0.0;

    var index = 0;
    $.each(shoppingCart, function (productID, productQuantity) {
        $.ajax({
            type: 'POST',
            url: "PHP/getComponentByID.php",
            data: {"componentID": productID},
            datatype: "json",
            cache: false,
            success: function (result) {
                var parsedData = $.parseJSON(result);

                var hiddenProductID = document.createElement("input");
                hiddenProductID.name= "productID[]";
                hiddenProductID.type="hidden";
                hiddenProductID.value=productID;
                $("#m_ProductID").append(hiddenProductID);

                var productNameDisplay = document.createElement("p");
                productNameDisplay.style="margin-bottom:10px";
                productNameDisplay.appendChild(document.createTextNode(parsedData.Name));
                productNameDisplay.onclick = function () {
                    $("#m_CheckoutForm").hide();
                    CreateComponent(productID);
                };
                $("#m_ProductName").append(productNameDisplay);

                var productQuantityDisplay = document.createElement("input");
                productQuantityDisplay.name = "productQuantity[]";
                productQuantityDisplay.min = "1";
                productQuantityDisplay.max = parsedData.In_Stock;
                productQuantityDisplay.type = "number";
                productQuantityDisplay.value = productQuantity;
                productQuantityDisplay.style = "width:80px;margin-bottom:10px";
                productQuantityDisplay.onchange = function ()
                {
                    var previousValue = shoppingCart[productID];
                    shoppingCart[productID] = productQuantityDisplay.value;
                    productPrice.innerText = (parsedData.Price * shoppingCart[productID]).toString() + '€';
                    totalPrice = Number(totalPrice) + Number(parsedData.Price * (shoppingCart[productID] - previousValue));
                    totalPrice = totalPrice.toFixed(2);
                    $("#m_TotalPrice").text("Total Price:   " + totalPrice + '€');
                }
                $("#m_ProductQuantity").append(productQuantityDisplay);
                $("#m_ProductQuantity").append(document.createElement("br"));

                var productPrice = document.createElement("p");
                productPrice.style="margin-bottom:10px";
                productPrice.appendChild(document.createTextNode((parsedData.Price * shoppingCart[productID]).toString() + '€'));
                $("#m_ProductPrice").append(productPrice);

                var removalButton = document.createElement("img");
                removalButton.src="images/CloseButton.jpg";
                removalButton.style="width:35px;height:35px;margin-bottom:5px;margin-left:au;margin-right:auto;display:block";
                removalButton.className="mButton";
                removalButton.onclick = function ()
                {
                    totalPrice = Number(totalPrice) - Number(parsedData.Price * shoppingCart[productID]);
                    totalPrice = totalPrice.toFixed(2);
                    $("#m_TotalPrice").text("Total Price:   " + totalPrice + '€');
                    delete(shoppingCart[productID]);
                    productNameDisplay.remove();
                    productQuantityDisplay.remove();
                    productPrice.remove();
                    removalButton.remove();
                }
                $("#m_ProductRemoval").append(removalButton);

                totalPrice = Number(totalPrice) + Number(parsedData.Price * shoppingCart[productID]);
                totalPrice = Number(totalPrice).toFixed(2);

                $("#m_TotalPrice").text("Total Price:   " + totalPrice + '€');
                index++;
            }
        });
    });
    //
}

//Replaces default window layout with component window
function CreateComponent(componentID) {
	 $.ajax({
		  type: 'POST',
		  url: "PHP/getComponentByID.php",
		  data: { "componentID": componentID },
		  datatype: "json",
		  cache: false,
		  beforeSend: function () {
				RemoveContent();
				$("#m_Catalogue").hide();
		  },
		  success: function (result) {
				var parsedData = $.parseJSON(result);

				var h1 = document.createElement("h1");
				h1.appendChild(document.createTextNode(parsedData.Name));

				var price = document.createElement("h3");
				price.appendChild(document.createTextNode("Price: " + parsedData.Price + '€'));

				var div2 = document.createElement("div");
				div2.style = "float:left;padding-left:80px;height:80%";
				div2.appendChild(h1);
				div2.appendChild(price);

				var inStock = document.createElement("h3");
				inStock.appendChild(document.createTextNode("In Stock: " + parsedData.In_Stock));
				div2.appendChild(inStock);
				div2.appendChild(document.createElement("br"));

				var keys = Object.getOwnPropertyNames(parsedData);
				var values = Object.values(parsedData);
				for (var i = 6; i < keys.length; i++) {
					 var p1 = document.createElement("p");
					 p1.appendChild(document.createTextNode(keys[i] + ": " + values[i]));
					 div2.appendChild(p1);
				};

				var checkout_button = document.createElement("img");
                checkout_button.style = "width:150px;height:100px;";

                if(!(componentID in shoppingCart)) {
                    checkout_button.className = "w3-button";
                    checkout_button.src = "images/cart.png";

                    checkout_button.onclick = function () {
                        shoppingCart[componentID] = 1;
                        checkout_button.className = "";
                        checkout_button.src = "images/confirmed.png";
                        checkout_button.onclick = null;
                    };
                }
                else
                {
                    checkout_button.src = "images/confirmed.png";
                }

				var img1 = document.createElement("img");
				img1.style = "float:left;max-width:700px;max-height:600px;width:100%;height:100%";
				img1.src = parsedData.imagePath;

				div2.appendChild(checkout_button);

				var div1 = document.createElement("div");
				div1.className="responsive";
				div1.className = "w3-large w3-container";
				div1.style = "margin-top:80px";
				div1.appendChild(img1);
				div1.appendChild(div2);

				var description = document.createElement("footer");
				description.className = "w3-container w3-theme w3-padding";
				description.style = "width:100%;height:20%;margin-top:10px;background-color:lightslategrey";
				description.appendChild(document.createTextNode(parsedData.Description));

				var m_Component = $("#m_Component").get(0);
				m_Component.appendChild(div1);
				m_Component.appendChild(description);
		  }
	 });
}
	 //Checkout Form Related-------------------------------------------
    var currentTab = 0; // Current tab is set to be the first tab (0)

    function DisplayTab(n) {
        // This function will display the specified tab of the form ...
        var x = document.getElementsByClassName("tab");
        x[n].style.display = "block";
        // ... and fix the Previous/Next buttons:
        if (n == 0) {
            $("#m_CheckoutPrevBtn").css("display", "none");
        } else {
            $("#m_CheckoutPrevBtn").css("display", "inline");
        }
        if (n == (x.length - 1)) {
            $("#m_CheckoutNextBtn").text("Submit");
        } else {
            $("#m_CheckoutNextBtn").text("Next");
        }
        // ... and run a function that displays the correct step indicator:
        fixStepIndicator(n)
    }

    //1 = next, -1 = previous
    function TabSequence(n) {
        // This function will figure out which tab to display
        var x = document.getElementsByClassName("tab");
        // Exit the function if any field in the current tab is invalid:
        if (n == 1 && !validateForm()) return false;
        // Hide the current tab:
        x[currentTab].style.display = "none";
        // Increase or decrease the current tab by 1:
        currentTab = currentTab + n;
        // if you have reached the end of the form... :
        if (currentTab >= x.length) {
            //...the form gets submitted:
            $("#m_CheckoutForm").submit();
            //document.getElementById("m_CheckoutForm").submit();
            return false;
        }
        // Otherwise, display the correct tab:
        DisplayTab(currentTab);
    }

    function validateForm() {
        // This function deals with validation of the form fields
        var x, y, i, valid = true;
        x = document.getElementsByClassName("tab");
        y = x[currentTab].getElementsByTagName("input");
        // A loop that checks every input field in the current tab:
        for (i = 0; i < y.length; i++) {
            // If a field is empty...
            if (y[i].value == "") {
                // add an "invalid" class to the field:
                y[i].className += " invalid";
                // and set the current valid status to false:
                valid = false;
            }
        }
        // If the valid status is true, mark the step as finished and valid:
        if (valid) {
            document.getElementsByClassName("step")[currentTab].className += " finish";
        }
        return valid; // return the valid status
    }

    function fixStepIndicator(n) {
        // This function removes the "active" class of all steps...
        var i, x = document.getElementsByClassName("step");
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        //... and adds the "active" class to the current step:
        x[n].className += " active";
    }

    function updateSummaryField(fieldID, fieldValue)
	{

		document.getElementById(fieldID).value = fieldValue;
	}
    //Checkout Form Related-------------------------------------------