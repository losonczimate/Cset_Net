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
    $unfollowed_id = $_POST["felh_id"];

    header("Location: follow.php");

    if (!$unfollowed_id or $unfollowed_id==3) {
        header("Location: follow.php");
    }

    $stmt = oci_parse($conn, "DELETE FROM KOVETES_ID WHERE KI = :user_id AND KIT = :unfollowed_id");
    oci_bind_by_name($stmt, ":user_id", $_SESSION["id"]);
    oci_bind_by_name($stmt, ":unfollowed_id", $unfollowed_id);

    if (!oci_execute($stmt)) {
        // hiba esetén átirányítás a hiba oldalra
        echo "<script>console.log('Error');</script>";
        exit;
    } else {
        echo "<script>console.log('Sikeres követés!');</script>";
        header("Location: follow.php");

    }
}

?>