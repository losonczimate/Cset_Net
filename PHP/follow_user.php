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
    $newly_followed = $_POST["follow_him"];
    echo "<script>console.log('follow!!!');</script>";

    if (!$newly_followed) {
        header("Location: follow.php");
    }

    // a biztonság kedvéért
    // lehet h többször van ugyan az az email és ez nem megoldás de most ilyen, kiszedek egy id-t azzal az email címmel...
    $stmt_for_id = oci_parse($conn, "SELECT FELH_ID FROM FELHASZNALO WHERE FELH_EMAIL = :followed_email");
    oci_bind_by_name($stmt_for_id, ":followed_email", $newly_followed);

    oci_execute($stmt_for_id);

    $row = oci_fetch_array($stmt_for_id, OCI_ASSOC + OCI_RETURN_NULLS);

    $newly_followed_id = $row["FELH_ID"];

    if (!$newly_followed_id) {

        header("Location: follow.php");
    }

    $stmt = oci_parse($conn, "INSERT INTO KOVETES_ID VALUES (:user_id, :followed_id)");
    oci_bind_by_name($stmt, ":user_id", $_SESSION["id"]);
    oci_bind_by_name($stmt, ":followed_id", $newly_followed_id);

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