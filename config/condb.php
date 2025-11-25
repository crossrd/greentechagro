<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
  $options = array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
  $conn = new PDO("mysql:host=$servername;dbname=greent_techdb;charset=utf8mb4", $username, $password, $options);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected successfully"; 
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>