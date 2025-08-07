<?php
require_once '../function/init.php';
require_once '../function/db.php';

if (count($_POST)) {
   $productId = intval($_POST['productId']);
   $username = strip_tags($_POST['username']);
   $phone = strip_tags($_POST['phone']);
   $price = floatval($_POST['price']);
   $address = strip_tags($_POST['address']);

   $username = $mysqli->real_escape_string($username);
   $phone = $mysqli->real_escape_string($phone);
   $address = $mysqli->real_escape_string($address);

   $mysqli->query("INSERT INTO orders SET username ='$username', phone ='$phone', address ='$address', productId = $productId, price = $price, createdAt = '" . date('Y-m-d H:i:s') . "'");
   header('Location: /success.php');
   exit;
}
