<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Cset.net</title>
    <link rel="stylesheet" type="text/css" href="../CSS/groups.css">
</head>
<body>
<div class="nav-buttons">
    <a href="profile.php">Profil</a>
    <a href="groups.php">Csoportok</a>
    <a href="followers.php">Ki követ</a>
    <a href="mainpage.php">Főoldal</a>
    <a href="for_you.php">Neked</a>
    <a href="followers.php">Követők</a>
    <a href="logout.php">Kijelentkezés</a>
</div>

    <form class="group-form" method="post" action="groups.php">
        <label for="groupname">Csoport neve:</label>
        <input type="text" id="groupname" class="group-name" name="groupname">
        <label for="groupdesc">Csoport leírása:</label>
        <input type="text" id="groupdesc" class="groupdescription" name="groupdesc">
        <button type="submit">Létrehozás</button>
    </form>
<?php
session_start();
// Create connection to Oracle
$conn = oci_connect("ADMIN", "Asdyxc123456", "csetnet_high", 'AL32UTF8');
if (!$conn) {
    $m = oci_error();
    echo $m, "\n";
    exit;
}

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    // Csoportok lekérdezése az adatbázisból
    $sql = "SELECT c.*, COUNT(ct.felh_id) AS user_count FROM Csoport c LEFT JOIN Csoporttagok ct ON c.csoport_id = ct.csoport_id GROUP BY c.csoport_id, c.csoport_neve, c.csoport_leiras ORDER BY c.CSOPORT_ID ASC";
    $stmt = oci_parse($conn, $sql);
    oci_execute($stmt);

    // Csoportok listázása divekben
    echo "<div class='group-list'>";
    while ($row = oci_fetch_assoc($stmt)) {
        echo "<div class='group-item'>";
        echo "<h2 class='group-name'>".$row['CSOPORT_NEVE']."</h2>";
        echo "<h3 class='group-description'>".$row['CSOPORT_LEIRAS']."</h3>";
        echo "<p class='group-members'>Felhasználók száma: ".$row['USER_COUNT']."</p>";

        // Ellenőrizni kell, hogy a felhasználó már csatlakozott-e a csoportba
        $sql2 = "SELECT * FROM Csoporttagok WHERE csoport_id = ".$row['CSOPORT_ID']." AND felh_id = ".$user_id;
        $stmt2 = oci_parse($conn, $sql2);
        oci_execute($stmt2);
        $already_joined = oci_fetch_assoc($stmt2);

        if ($already_joined) {
            // Ha már csatlakozott a felhasználó, akkor kilépés gombot jelenítünk meg
            echo "<button class='leave-group' data-group-id='".$row['CSOPORT_ID']."'>Kilépés</button>";
        } else {
            // Ha még nem csatlakozott a felhasználó, akkor csatlakozás gombot jelenítünk meg
            echo "<button class='join-group' data-group-id='".$row['CSOPORT_ID']."'>Csatlakozás</button>";
        }

        echo "</div>";
    }
    echo "</div>";

    oci_free_statement($stmt);
    oci_free_statement($stmt2);

} else {
    echo "A csoportok megjelenítéséhez be kell jelentkeznie.";
}
// A form adatainak lekérdezése
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groupname = $_POST['groupname'];
    $groupdesc = $_POST['groupdesc'];

    $sql_max_id_group = "SELECT MAX(CSOPORT_ID) AS max_id FROM CSOPORT";
    $stid_max_id_group = oci_parse($conn, $sql_max_id_group);
    oci_execute($stid_max_id_group);
    $max_id_row_group = oci_fetch_array($stid_max_id_group, OCI_ASSOC);
    $max_id_row_group = $max_id_row_group['MAX_ID'];

    $max_id_row_group = $max_id_row_group + 1;

    // Létrehozunk egy Oracle Prepared Statement-et
    $stid = oci_parse($conn, "INSERT INTO CSOPORT (CSOPORT_ID, CSOPORT_NEVE, CSOPORT_LEIRAS) VALUES (:groupid,:groupname, :groupdesc)");

    // A Prepared Statement paramétereinek beállítása a form adataival
    oci_bind_by_name($stid, ':groupname', $groupname);
    oci_bind_by_name($stid, ':groupdesc', $groupdesc);
    oci_bind_by_name($stid, ':groupid', $max_id_row_group);

    // A Prepared Statement futtatása
    $result = oci_execute($stid);

    if (!$result) {
        $e = oci_error($stid);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    // Az Oracle kapcsolat lezárása
    oci_free_statement($stid);
    oci_free_statement($stid_max_id_group);
    oci_close($conn);
}
?>

