<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="css/checkoutstyle.css">
</head>

<body onload="updatePrice();">
    <div class="name" style="padding-left: 20px; padding-top: 20px">
        UTS Mart
    </div>
    </header>
    <h2>Checkout Page</h2>
    <div class="container">
        <div class="row">
            <label for="firstname">First Name</label>
            <input type="text" id="first-name">
        </div>

        <div class="row">
            <label for="secondname">Second Name</label>
            <input type="text" id="second-name">
        </div>
        <div class="row">
            <label for="street">Street</label>
            <input type="text" id="street">
        </div>
        <div class="row">
            <label for="city">City/Suburb</label>
            <input type="text" id="city">
        </div>
        <div class="row">
            <label for="state">State</label>
            <select id="state" name="state">
                <option value="" disabled selected>Select State/Territory</option>
                <option value="NSW">New South Wales</option>
                <option value="VIC">Victoria</option>
                <option value="QLD">Queensland</option>
                <option value="WA">Western Australia</option>
                <option value="SA">South Australia</option>
                <option value="TAS">Tasmania</option>
                <option value="ACT">Australian Capital Territory</option>
                <option value="NT">Northern Territory</option>
                <option value="Others">Others</option>
            </select>
        </div>
        <div class="row">
            <label for="email">E-mail</label>
            <input type="text" id="email">
        </div>

        <div class="total">
            <div class="total-title">Total</div>
            <div class="total-price" id="total-price">$0</div>
        </div>
    </div>
    <div class="row">
        <button onclick="validateForm()">Make Purchase</button>
    </div>
    <button class="back-button" onclick="window.location.href='index.php'">Go Back</button>

    <script>
        const CART_KEY = 'CART_KEY';

        async function clearCart() {
            localStorage.setItem(CART_KEY, "");
            document.getElementById("cart-content").innerHTML = "";
            document.getElementById("total-price").innerHTML = "$0";
            updateCart();
        }

        async function validateForm() {
            var firstName = document.getElementById("first-name").value;
            var secondName = document.getElementById("second-name").value;
            var street = document.getElementById("street").value;
            var city = document.getElementById("city").value;
            var state = document.getElementById("state").value;
            var email = document.getElementById("email").value;

            if (firstName === '' || secondName === '' || street === '' || city === '' || state === '' || email === '') {
                alert("Please fill in all fields.");
                return false;
            }

            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            //Not allow customers to proceed with checkout
            var isAvailable = await checkItemAvailability();
            if (!isAvailable) {
                alert("Some items are unavailable or insufficient. Please review your order.");
                window.location.replace('./index.php');
                return false;
            }
            // When success
            callUpdateStock();
            clearCart();
            alert("Purchase Sucess! A confirmation email has been sent to you.");
            window.location.replace('./index.php');
            return false;
        }


        async function callUpdateStock() {
            const cartString = localStorage.getItem(CART_KEY);
            const cart = JSON.parse(cartString);
            var updateQuantity, productId;

            for (const productId in cart) {
                const quantity = cart[productId];
                const url1 = `product.php?productId=${productId}`;
                try {
                    const response = await fetch(url1);

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    const product = await response.json();
                    updateQuantity = product.in_stock - quantity;

                    const url2 = `product.php?productId=${productId}&updateQuantity=${updateQuantity}`;

                    const updateResponse = await fetch(url2);

                    if (!updateResponse.ok) {
                        throw new Error(`HTTP error! Status: ${updateResponse.status}`);
                    }

                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            }

        }


        async function updatePrice() {
            const cartString = localStorage.getItem(CART_KEY);
            const cart = JSON.parse(cartString);
            var totalPrice = 0;
            document.getElementById("total-price").innerHTML = "0";

            for (const productId in cart) {
                console.log(productId);
                const quantity = cart[productId];
                const url = `product.php?productId=${productId}`;

                try {
                    const response = await fetch(url);

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    const product = await response.json();

                    totalPrice += product.unit_price * quantity;
                    totalPrice = Math.round(totalPrice * 100) / 100;
                    document.getElementById("total-price").innerHTML = "$" + totalPrice;
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            }
        }

        async function checkItemAvailability() {
            const cartString = localStorage.getItem(CART_KEY);
            const cart = JSON.parse(cartString);

            for (const productId in cart) {
                const quantity = cart[productId];
                const url = `product.php?productId=${productId}`;

                try {
                    const response = await fetch(url);

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    const product = await response.json();

                    if (quantity > product.in_stock) {
                        return false;
                    }

                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            }
            return true;
        }
    </script>
</body>

</html>