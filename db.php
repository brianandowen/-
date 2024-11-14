<?php

$servername = "localhost";

$dbname = "profile";

$dbUsername = "root";

$dbPassword = "";


$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbname);

if (!$conn) {
    die("連線失敗: " . mysqli_connect_error());
} else {
    echo "資料庫連線成功！";
}


?>
