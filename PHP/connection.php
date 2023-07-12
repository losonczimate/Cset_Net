<?php
session_start();
if (!isset($_SESSION["loggedin"])) {
    $_SESSION["loggedin"] = FALSE;
}
?>

<html lang="hu" xmlns="http://www.w3.org/1999/html">
<head>
    <title>Cset.net</title>
    <link rel=stylesheet type="text/css" href="../CSS/mystyle.css"/>
</head>
<body>
<?php
// Create connection to Oracle
$conn = oci_connect("ADMIN", "Asdyxc123456", "csetnet_high", 'AL32UTF8');
if (!$conn) {
    $m = oci_error();
    echo $m, "\n";
    exit;
}
echo '<div class="nav-buttons">
    <a href="profile.php">Profil</a>
    <a href="groups.php">Csoportok</a>
    <a href="followers.php">Ki követ</a>
    <a href="mainpage.php">Főoldal</a>
    <a href="for_you.php">Neked</a>
    <a href="followers.php">Követők</a>
    <a href="logout.php">Kijelentkezés</a>
      </div>';


echo '<h2>Felhasználók</h2>';
$stid = oci_parse($conn, 'SELECT * FROM Felhasznalo ORDER BY FELH_ID ASC');

oci_execute($stid);

$nfields = oci_num_fields($stid);

echo '<div class="table-wrapper">';
echo '<table  class="fl-table"> <thead>';
echo '<tr>';
for ($i = 1; $i <= $nfields; $i++) {
    $field = oci_field_name($stid, $i);
    echo '<th>' . $field . '</th>';
}
echo '</tr></thead>';

oci_execute($stid);

while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
    echo '<tbody><tr>';
    foreach ($row as $item) {
        echo '<td>' . $item . '</td>';
    }
    echo '</tr></tbody>';
}

echo '</table>';
echo '</div>';

echo '<h2>Fényképek</h2>';
$stid2 = oci_parse($conn, 'SELECT * FROM Fenykep ORDER BY kep_id ASC');
oci_execute($stid2);

$nfields2 = oci_num_fields($stid2);

echo '<div class="table-wrapper">';
echo '<table class="fl-table"> <thead>';
echo '<tr>';
for ($i = 1; $i <= $nfields2; $i++) {
    $field = oci_field_name($stid2, $i);
    echo '<th>' . $field . '</th>';
}
echo '</tr></thead>';

//// -- ujra vegrehajtom a lekerdezest, es kiiratom a sorokat
oci_execute($stid2);

while ($row = oci_fetch_array($stid2, OCI_ASSOC + OCI_RETURN_NULLS)) {
    echo '<tbody><tr>';
    foreach ($row as $item) {
        echo '<td>' . $item . '</td>';
    }
    echo '</tr></tbody>';
}
echo '</table>';
echo '</div>';

echo '<h2>Bejegyzések</h2>';
$stid3 = oci_parse($conn, 'SELECT * FROM Bejegyzes');
oci_execute($stid3);

$nfields3 = oci_num_fields($stid3);

echo '<div class="table-wrapper">';
echo '<table class="fl-table"> <thead>';
echo '<tr>';
for ($i = 1; $i <= $nfields3; $i++) {
    $field = oci_field_name($stid3, $i);
    echo '<th>' . $field . '</th>';
}
echo '</tr></thead>';

//// -- ujra vegrehajtom a lekerdezest, es kiiratom a sorokat
oci_execute($stid3);

while ($row = oci_fetch_array($stid3, OCI_ASSOC + OCI_RETURN_NULLS)) {
    echo '<tbody><tr>';
    foreach ($row as $item) {
        echo '<td>' . $item . '</td>';
    }
    echo '</tr></tbody>';
}
echo '</table>';
echo '</div>';

//4. tábla

echo '<h2>Kommentek</h2>';
$stid4 = oci_parse($conn, 'SELECT * FROM Kommentek');
oci_execute($stid4);

$nfields4 = oci_num_fields($stid4);

echo '<div class="table-wrapper">';
echo '<table class="fl-table"> <thead>';
echo '<tr>';
for ($i = 1; $i <= $nfields4; $i++) {
    $field = oci_field_name($stid4, $i);
    echo '<th>' . $field . '</th>';
}
echo '</tr></thead>';

//// -- ujra vegrehajtom a lekerdezest, es kiiratom a sorokat
oci_execute($stid4);

while ($row = oci_fetch_array($stid4, OCI_ASSOC + OCI_RETURN_NULLS)) {
    echo '<tbody><tr>';
    foreach ($row as $item) {
        echo '<td>' . $item . '</td>';
    }
    echo '</tr></tbody>';
}
echo '</table>';
echo '</div>';

echo '<h2>Csoportok</h2>';
$stid5 = oci_parse($conn, 'SELECT * FROM Csoport');
oci_execute($stid5);

$nfields5 = oci_num_fields($stid5);

echo '<div class="table-wrapper">';
echo '<table class="fl-table"> <thead>';
echo '<tr>';
for ($i = 1; $i <= $nfields5; $i++) {
    $field = oci_field_name($stid5, $i);
    echo '<th>' . $field . '</th>';
}
echo '</tr></thead>';

//// -- ujra vegrehajtom a lekerdezest, es kiiratom a sorokat
oci_execute($stid5);

while ($row = oci_fetch_array($stid5, OCI_ASSOC + OCI_RETURN_NULLS)) {
    echo '<tbody><tr>';
    foreach ($row as $item) {
        echo '<td>' . $item . '</td>';
    }
    echo '</tr></tbody>';
}
echo '</table>';
echo '</div>';

echo '<h2>Csoporttagok</h2>';
$stid8 = oci_parse($conn, 'SELECT * FROM Csoporttagok');
oci_execute($stid8);

$nfields8 = oci_num_fields($stid8);

echo '<div class="table-wrapper">';
echo '<table class="fl-table"> <thead>';
echo '<tr>';
for ($i = 1; $i <= $nfields8; $i++) {
    $field = oci_field_name($stid8, $i);
    echo '<th>' . $field . '</th>';
}
echo '</tr></thead>';

//// -- ujra vegrehajtom a lekerdezest, es kiiratom a sorokat
oci_execute($stid8);

while ($row = oci_fetch_array($stid8, OCI_ASSOC + OCI_RETURN_NULLS)) {
    echo '<tbody><tr>';
    foreach ($row as $item) {
        echo '<td>' . $item . '</td>';
    }
    echo '</tr></tbody>';
}
echo '</table>';
echo '</div>';


echo '<h2>Uzenet</h2>';
$stid6 = oci_parse($conn, 'SELECT * FROM Uzenet');
oci_execute($stid6);

$nfields6 = oci_num_fields($stid6);

echo '<div class="table-wrapper">';
echo '<table class="fl-table"> <thead>';
echo '<tr>';
for ($i = 1; $i <= $nfields6; $i++) {
    $field = oci_field_name($stid6, $i);
    echo '<th>' . $field . '</th>';
}
echo '</tr></thead>';

//// -- ujra vegrehajtom a lekerdezest, es kiiratom a sorokat
oci_execute($stid6);

while ($row = oci_fetch_array($stid6, OCI_ASSOC + OCI_RETURN_NULLS)) {
    echo '<tbody><tr>';
    foreach ($row as $item) {
        echo '<td>' . $item . '</td>';
    }
    echo '</tr></tbody>';
}
echo '</table>';
echo '</div>';


// 7. tábla
echo '<h2>Követések</h2>';
$stid7 = oci_parse($conn, 'SELECT * FROM KOVETES_ID');
oci_execute($stid7);

$nfields7 = oci_num_fields($stid7);

echo '<div class="table-wrapper">';
echo '<table class="fl-table"> <thead>';
echo '<tr>';
for ($i = 1; $i <= $nfields7; $i++) {
    $field = oci_field_name($stid7, $i);
    echo '<th>' . $field . '</th>';
}
echo '</tr></thead>';


//// -- ujra vegrehajtom a lekerdezest, es kiiratom a sorokat
oci_execute($stid7);
while ($row = oci_fetch_array($stid7, OCI_ASSOC + OCI_RETURN_NULLS)) {
    echo '<tbody><tr>';
    foreach ($row as $item) {
        echo '<td>' . $item . '</td>';
    }

    echo '</tr></tbody>';
}


echo '</table>';
echo '</div>';

// Close the Oracle connection
oci_close($conn);
?>

</body>
</html>