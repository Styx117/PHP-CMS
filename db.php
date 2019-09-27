<?php
require('errorLog.php');
	// Storing in the database
$servername = "localhost";
$username = "root";
$password = "";

	// Create connection
$conn = new mysqli($servername, $username, $password, "cmssy2");

	// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} 

?>