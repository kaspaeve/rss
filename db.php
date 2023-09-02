<?php
$servername = "localhost";
$dbname = "rss";
$dbuser = "rss_user";
$dbpass = "e#XwGvRG3F%36hyQwKQd";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbuser, $dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
