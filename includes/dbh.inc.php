<?php


// php file to connect to database


$dbServername = "174.104.116.122";
$dbServernamelocal = "localhost";
$dbServername1 = "131.123.35.145";
$dbServername2 = "131.123.35.146";
$dbUsername = "root";
$dbPassword = "llkwzcapstone";
$dbName = "attendance";

$conn = mysqli_connect($dbServernameLocal, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    
    
        
        header("Location: ../ServerDown.php");
        exit();
    

    
}
