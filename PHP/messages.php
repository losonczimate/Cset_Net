<html lang="hu" xmlns="http://www.w3.org/1999/html">
<head>
    <title>Cset.net</title>
    <link rel=stylesheet type="text/css" href="../CSS/login.css"/>
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
$query = "SELECT * FROM UZENET,FELHASZNALO WHERE KULDO = FELH_ID  ORDER BY KULDES_IDEJE DESC";
$statement = oci_parse($conn, $query);
oci_execute($statement);

// Adatok kiírása div-ekbe
while ($row = oci_fetch_array($statement, OCI_ASSOC)) {
    echo '<div>Küldő: '.$row['FELH_NEV'].'</div>';
    echo '<div>Üzenet: '.$row['TARTALOM'].'</div>';
}

// Adatbázis kapcsolat bezárása
oci_free_statement($statement);
oci_close($conn);
?>
