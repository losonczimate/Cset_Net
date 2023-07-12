<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Cset.net</title>
    <link rel="stylesheet" type="text/css" href="../CSS/profile.css">
</head>
<body>
<div class="nav-buttons">
    <a href="profile.php">Profil</a>
    <a href="mainpage.php">Főoldal</a>
    <a href="logout.php">Kijelentkezés</a>
    <a href="follow.php">Követés</a>
    <a href="followers.php">Ki követ</a>
    <a href="for_you.php">Neked</a>
    <a href="messaging.php">Üzenetküldés</a>
</div>
<div style="background-color: #cfe2f3; text-align: center; padding: 10px; border-radius: 10px;">
    <h2 style="color: #007bff; font-family: Arial, sans-serif; font-size: 24px;">They follow you:</h2>
</div>


<?php
session_start();

// adatbázis kapcsolódási adatok
$conn = oci_connect("ADMIN", "Asdyxc123456", "csetnet_high", 'AL32UTF8');
if (!$conn) {
    $m = oci_error();
    echo $m, "\n";
    exit;
}

echo "<script>console.log('Sikeres kapcsolat!');</script>";

// lekérjük az összes felhasználót az adatbázisból akit követ az aktuális felhasználó
$stmt = oci_parse($conn, "SELECT KI, FELH_NEV, FELH_EMAIL FROM KOVETES_ID, FELHASZNALO WHERE KIT = :user_id AND FELH_ID = KOVETES_ID.KI");
oci_bind_by_name($stmt, ':user_id', $_SESSION["id"]);

oci_execute($stmt);



// kiírjuk az eredményt, minden felhasználót egy külön div-be helyezve
echo '<div class="container">';
while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
    echo '<div class="userdata item">';
    echo "<script>console.log('Sikeres!');</script>";
    echo '<p>'.$row["FELH_NEV"].'</p>';
    echo '<p>'.$row["FELH_EMAIL"].'</p>';
    $_SESSION["username"] = $row["FELH_NEV"];
    echo '</div>';
}
echo '</div></div>';



// Adatbáziskapcsolat lezárása
oci_close($conn);
?>
</body>
<footer class="footer">

    <p>All right reserved 2023.</p>

</footer>