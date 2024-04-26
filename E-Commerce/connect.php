<?php
// Database connection details
$user = 'root';
$pass = '';
$db = 'assignment1v6';
$host = 'localhost';

// Attempt to establish a connection to the database
$connection = mysqli_connect($host, $user, $pass, $db);

// Check if connection was successful
if (!$connection) {
    die("Unable to connect to the database: " . mysqli_connect_error());
}

$category = $_GET['category'];

// Query to fetch frozen food products
$query = "SELECT * FROM products";
if ($category != 'All') {
    $query .= " WHERE category = '$category'";
}

// Execute the query
$result = mysqli_query($connection, $query);

// Check if query execution was successful
if (!$result) {
    die("Error executing the query: " . mysqli_error($connection));
}

// Prepare the HTML markup for each product
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    array_push($products, $row);
}

// Close the database connection
mysqli_close($connection);

// Return the HTML markup for the frozen food products
echo json_encode($products);
