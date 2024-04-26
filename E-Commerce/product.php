<?php

require ('db.php');

function getProducts()
{
  $connection = DB::getInstance()->getConnection();
  $result = $connection->query("SELECT * FROM products");

  $products = [];
  while ($row = $result->fetch_assoc()) {
    array_push($products, $row);
  }

  return $products;
}

function getProductByCategory($category)
{
  $connection = DB::getInstance()->getConnection();
  $statement = $connection->prepare("SELECT * FROM products WHERE category = ?");
  $statement->bind_param("s", $category);
  $statement->execute();

  $result = $statement->get_result();

  $products = [];
  while ($row = $result->fetch_assoc()) {
    array_push($products, $row);
  }

  return $products;
}

if (array_key_exists('category', $_GET)) {
  $category = $_GET['category'];
  if ($category == 'all') {
    echo json_encode(getProducts());
    return;
  }

  $products = getProductByCategory($category);
  echo json_encode($products);
  return;
}


function getProductByName($product_name)
{
  $connection = DB::getInstance()->getConnection();
  $statement = $connection->prepare("SELECT * FROM products WHERE product_name LIKE ?");
  $search = "%" . $product_name . "%";
  $statement->bind_param("s", $search);
  $statement->execute();

  $result = $statement->get_result();

  $products = [];
  while ($row = $result->fetch_assoc()) {
    array_push($products, $row);
  }

  return $products;
}


if (array_key_exists('search', $_GET)) {
  $product_name = $_GET['search'];
  if ($product_name == '') {
    echo json_encode(getProducts());
    return;
  }

  // $products = getProductByName($product_name);
  echo json_encode(getProductByName($product_name));
  return;
}

function getProductById($productId)
{
  $connection = DB::getInstance()->getConnection();
  $statement = $connection->prepare("SELECT * FROM products WHERE product_id = ?");
  $statement->bind_param("s", $productId);
  $statement->execute();

  $result = $statement->get_result();

  if ($result->num_rows === 1) {
    return $result->fetch_assoc();
  }

  return null;
}

if (array_key_exists('productId', $_GET)) {
  $productId = $_GET['productId'];
  $product = getProductById($productId);
  echo json_encode($product);
}

function updateStock($productId, $updateQuantity)
{
  $connection = DB::getInstance()->getConnection();
  $statement = $connection->prepare("UPDATE products SET in_stock = ? WHERE product_id = ?");
  $statement->bind_param("is", $updateQuantity, $productId);
  $statement->execute();
  return null;
}

if (array_key_exists('productId', $_GET) && array_key_exists('updateQuantity', $_GET)) {
  $productId = $_GET['productId'];
  $updateQuantity = $_GET['updateQuantity'];
  updateStock($productId, $updateQuantity);
  echo "ok";
  return;
}








