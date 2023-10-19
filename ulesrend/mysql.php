<?php
$servername = "localhost";
$username = "lacko";
$password = "qfFqqa4mSm83aqad";
$dbname = "ulesrend";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
$conn->set_charset("utf8");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
/*
try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }*/
?>