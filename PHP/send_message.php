<html lang="hu" xmlns="http://www.w3.org/1999/html">
<head>
    <link rel=stylesheet href="../CSS/registration.css"/>
</head>
<body>
<?php
session_start();

// adatbázis kapcsolódási adatok
$conn = oci_connect("ADMIN", "Asdyxc123456", "csetnet_high", 'AL32UTF8');
echo "<script>console.log('Sikeres kapcsolat!');</script>";
if (!$conn) {
    $m = oci_error();
    echo $m, "\n";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // felhasználó adatainak frissítése
    $sent_message = $_POST["sent_message"];

    if (!$sent_message) {
        header("Location: messaging.php");
    }

    $stmt = oci_parse($conn, "INSERT INTO UZENET VALUES(NULL, :sent_message, SYSDATE, :user_id, :messaging_partner)");
    oci_bind_by_name($stmt, ":sent_message", $sent_message);
    oci_bind_by_name($stmt, ":user_id", $_SESSION["id"]);
    oci_bind_by_name($stmt, ":messaging_partner", $_SESSION["messaging_partner_ID"]);

    if (!oci_execute($stmt)) {
        // hiba esetén átirányítás a hiba oldalra
        echo "<script>console.log('Error');</script>";
        exit;
    } else {
        echo "<script>console.log('Sikeres követés!');</script>";
        header("Location: messaging.php");

    }
}

?>