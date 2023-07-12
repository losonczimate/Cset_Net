<html lang="hu" xmlns="http://www.w3.org/1999/html">
<head>
    <link rel=stylesheet href="../CSS/mainpage.css"/>
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
    $komment = $_POST["komment"];
    $bejegyzes_id = $_POST["bejegyzes_id"];

    if (!$komment) {
        header("Location: mainpage.php");
    }

    $stmt = oci_parse($conn, "INSERT INTO KOMMENTEK VALUES (NULL, :kommentelo_id, :bejegyzes_id, SYSDATE, :szoveg)");
    oci_bind_by_name($stmt, ":kommentelo_id", $_SESSION["id"]);
    oci_bind_by_name($stmt, ":bejegyzes_id", $bejegyzes_id);
    oci_bind_by_name($stmt, ":szoveg", $komment);

    if (!oci_execute($stmt)) {
        // hiba esetén átirányítás a hiba oldalra
        echo "<script>console.log('Error');</script>";
        exit;
    } else {
        echo "<script>console.log('Sikeres követés!');</script>";
        if(isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }


    }
}

?>