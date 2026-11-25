<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'sql210.infinityfree.com';
$user = 'if0_43519519';
$pass = 'FIStVfJ3YgLiw';
$db   = 'if0_43519519_kostku';

echo "<h3>Testing MySQL Connection...</h3>";
echo "Host: $host<br>";
echo "User: $user<br>";
echo "DB: $db<br><hr>";

$conn = @mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    echo "<b style='color:red;'>GAGAL KONEK:</b> " . mysqli_connect_error();
    echo "<br>Error Code: " . mysqli_connect_errno();
} else {
    echo "<b style='color:green;'>BERHASIL KONEK KE DATABASE INFINITYFREE!</b>";
}
