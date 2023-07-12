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
    $new_pw = $_POST["password"];
    $new_phone = $_POST["phone"];

    $phone = $_SESSION["phone"];
    $password = $_SESSION["password"];
    $email = $_SESSION["email"];
    if (!$new_pw) {
        $new_pw = $password;
    }
    if (!$new_phone) {
        $new_phone = $phone;
    }

    $stmt = oci_parse($conn, "UPDATE Felhasznalo SET FELH_JELSZO=:new_pw, FELH_TELSZAM=:new_phone WHERE felh_email = :email");
    oci_bind_by_name($stmt, ":password", $password);
    oci_bind_by_name($stmt, ":email", $email);
    oci_bind_by_name($stmt, ":new_phone", $new_phone);
    oci_bind_by_name($stmt, ":new_pw", $new_pw);

    if (!oci_execute($stmt)) {
        // hiba esetén átirányítás a hiba oldalra
        echo "<script>console.log('Error');</script>";
        exit;
    } else {
        $_SESSION["phone"] = $new_phone;
        $_SESSION["password"] = $new_pw;
        echo "<script>console.log('Sikeres frissítés!');</script>";
        header("Location: profile.php");

    }
}

?>

