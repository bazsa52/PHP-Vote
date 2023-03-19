<?php
$serverName="127.0.0.1";
$dBUsername="root";
$dBPassword="";
$dBName="Poll";

try {
    $conn = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName);
} catch (\Throwable $th) {
    die("Hiba történt a szerverrel történő kommunikáció során. Kérem próbálja megismételni kérését később! Amennyiben a probléma továbbra is fennáll kérem forduljon a rendszergazdához!");
}

if(!$conn){
    die("Hiba történt a szerverrel történő kommunikáció során. Kérem próbálja megismételni kérését később! Amennyiben a probléma továbbra is fennáll kérem forduljon a rendszergazdához!");
}
