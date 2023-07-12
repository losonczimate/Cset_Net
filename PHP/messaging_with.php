<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Cset.net</title>
    <link rel="stylesheet" type="text/css" href="../CSS/mainpage.css">
</head>
<body>
<div class="nav-buttons">
    <a href="profile.php">Profil</a>
    <a href="connection.php">Össz lekérdezés</a>
    <a href="mainpage.php">Főoldal</a>
    <a href="logout.php">Kijelentkezés</a>
    <a href="follow.php">Követés</a>
    <a href="followers.php">Ki követ</a>
    <a href="for_you.php">Neked</a>
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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // felhazsnáló kiválasztása akivel üzenetezés lesz
    $stmt = oci_parse($conn, "SELECT FELH_ID,FELH_NEV, FELH_EMAIL FROM Felhasznalo WHERE FELH_EMAIL=:email");
    $email = $_POST["email"];


    oci_bind_by_name($stmt, ":email", $email);


    if (oci_execute($stmt)) {
        $row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS);
        if ($row != false) {
            // sikeres email ellenőrzés

            $_SESSION["messaging_partner_ID"] = $row["FELH_ID"];
            $_SESSION["messaging_partner_email"] = $row["FELH_EMAIL"];
            $_SESSION["messaging_partner_nev"] = $row["FELH_NEV"];
            header("Location: messaging.php");

                exit();
            } else {
                // sikertelen jelszó ellenőrzés
                echo "Hibás email!";
            }
        } else {
            // sikertelen email ellenőrzés
            echo "Hibás email cím!";
        }
}
?>
</body>
