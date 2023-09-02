<?php
session_start();
include 'db.php';
include 'functions.php';  
global $base_url;
logoutUser($conn);  
$_SESSION['toast_message'] = "Logout Successful";

// Redirect to the login page after logging out
header("Location: " . $base_url . "login.php");
exit();
?>