<?php

$conn = mysqli_connect("localhost", "root", "", "gorev_proje");

if (!$conn) {
    die("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
}

?>