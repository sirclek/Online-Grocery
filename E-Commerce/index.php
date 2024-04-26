<!DOCTYPE html>
<html lang="en">

<?php
session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frozen Food Items</title>
    <link rel="stylesheet" href="css/categorystyle.css">
    <link rel="stylesheet" href="css/headerstyle.css">
    <link rel="stylesheet" href="css/productstyle.css">
    <link rel="stylesheet" href="css/cartstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        * {
            font-family: sans-serif;
        }

        body {
            padding-top: 100px;
            background-color: #f4f4f4;
        }
    </style>
</head>

<body onload="showProducts('all')">
    <header class="header">
        <div class="header-content">
            <div class="header-left">
                <div class="name">
                    UTS Mart
                </div>
                <div class="dropdown">
                    <button class="dropbtn">Browse Categories</button>
                    <div class="dropdown-content">
                        <a href="#" onclick="showProducts('all')">All Categories</a>
                        <a href="#" onclick="showProducts('Frozen')">Frozen</a>
                        <a href="#" onclick="showProducts('Fresh')">Fresh</a>
                        <a href="#" onclick="showProducts('Beverages')">Beverages</a>
                        <a href="#" onclick="showProducts('Home')">Home</a>
                        <a href="#" onclick="showProducts('Pet-Food')">Pet-Food</a>
                    </div>
                </div>
            </div>
            <div class="header-mid">
                <input type="text" id="searchbar" class="searchbar" placeholder="Search products">
                <button class="search-logo-box" onclick="showProductsSearch()">
                    <img src="images/search.png" class="search-logo">
                </button>
            </div>

            <div class="header-right">
                <a href="index.php" class="top-menu">Home</a>
                <i class='bx bx-shopping-bag' id="carticon"></i>
            </div>
        </div>
    </header>
    <div class="product-grid" id="product-grid">
        <!-- Products will be displayed here -->
    </div>

    <div class="cart">
        <h2 class="cart-title">Your Cart</h2>
        <div class="cart-content" id="cart-content">
        </div>
        <div class="total">
            <div class="total-title">Total</div>
            <div class="total-price" id="total-price">$0</div>
        </div>
        <button class="clear-button" onclick="clearCart()">Clear Cart</button>
        <button type="button" class="buy-button" id="buy-button" onclick="buyButtonClick()">Buy Now</button>

        <i class='bx bx-x' id="close-cart"></i>
    </div>

    <script src="script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showProducts('all');
        });
    </script>

</body>

</html>