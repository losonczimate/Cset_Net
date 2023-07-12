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
    <a href="messaging.php">üzenetküldés</a>
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


// lekérjük a felhasználót az adatbázisból
$stmt = oci_parse($conn, "SELECT felh_nev, felh_email, felh_telszam FROM Felhasznalo WHERE felh_email = :email");
oci_bind_by_name($stmt, ':email', $_SESSION["email"]);

oci_execute($stmt);



// kiírjuk az eredményt, egy külön div-be helyezve
echo '<div>';
while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
    echo '<div class="userdata">';
    echo "<script>console.log('Sikeres!');</script>";
    echo '<p>Felhasználó neve: '.$row["FELH_NEV"].'</p>';
    $_SESSION["username"] = $row["FELH_NEV"];
    echo '<p>E-mail címe: '.$row["FELH_EMAIL"].'</p>';
    $_SESSION["phone"] = $row["FELH_TELSZAM"];
    echo '<p>Telefonszáma: '.$row["FELH_TELSZAM"].'</p>';
    echo '</div>';
}
echo '</div>';
?>

<form action="upload.php" method="post" enctype="multipart/form-data">
    <label>Select pictures to upload:</label>
    <input type="file" name="files[]" multiple><br><br>
    <input type="submit" value="Upload">
</form>



<div class="update_container">

    <form method="post" class="update_header" action="updateuser.php">
        <h2>Frissítés</h2>
        <label for="password">Jelszó:</label><br>
        <input type="text" id="password" name="password"><br>

        <label for="phone">Telefonszám:</label><br>
        <input type="tel" id="phone" name="phone"><br>


        <input type="submit" value="Frissítés" class="update_button">
    </form>
</div>
<?php
$post = array();
while ($post_row = oci_fetch_array($stid_select_posts, OCI_ASSOC)) {
    array_push($post, $post_row);
}
usort($post, function ($a, $b) {
    return strcmp($b['BEJEGYZES_IDOPONT'], $a['BEJEGYZES_IDOPONT']);
});
foreach ($post as $post_row) {
    $bejegyzes_id = $post_row['BEJEGYZES_ID'];
    $bejegyzes_idopont = $post_row['BEJEGYZES_IDOPONT'];
    $bejegyzes_leiras = $post_row['BEJEGYZES_LEIRAS'];
    $kep_url = $post_row['KEP_URL'];
    $kep_leiras = $post_row['KEP_LEIRAS'];
    $felh_nev = $post_row['FELH_NEV'];
    ?>
    <div class="bejegyzes">
        <div class="bejegyzes-felhasznalo"><?= $felh_nev ?></div>
        <div class="bejegyzes-idopont"><?= $bejegyzes_idopont ?></div>
        <?php if ($kep_url) { ?>
            <img class="bejegyzes-kep" src="<?= $kep_url ?>">
        <?php } ?>
        <div class="bejegyzes-leiras"><?= $bejegyzes_leiras ?></div>


        <?php
        // A kommentek lekérdezése az adott bejegyzéshez
        $sql = "SELECT K.KOMMENT_ID, F.FELH_NEV, TO_CHAR(K.idopont, 'YYYY.MM.DD. HH24:MI') as IDOPONT, K.SZOVEG FROM KOMMENTEK K INNER JOIN FELHASZNALO F ON K.KOMMENTELO_ID = F.FELH_ID WHERE K.BEJEGYZES_ID = '$bejegyzes_id'";
        $stid = oci_parse($conn, $sql);
        oci_execute($stid);

        // A kommentek megjelenítése
        if (oci_fetch($stid)) {
            echo "<div class='kommentek'>";
            oci_execute($stid);
            while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
                $komment_id = $row["KOMMENT_ID"];
                $szerzo = $row["FELH_NEV"];
                $datum = $row["IDOPONT"];
                $szoveg = $row["SZOVEG"];

                echo "<div class='komment'>";
                echo "<div class='komment-felhasznalo'>$szerzo,$datum</div>";
                echo "<div class='komment-szoveg'>$szoveg</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<div class='nincs-komment'>Nincsenek kommentek ehhez a bejegyzéshez.</div>";
        }

        oci_free_statement($stid);
        oci_close($conn);
        ?>

        <!--komment bevitel-->
        <div>

            <form method="post" action="write_comment.php">
                <label for="komment">Kommentelj:</label>
                <input type="text" name="komment"><br>
                <input type="hidden" value="<?= $bejegyzes_id?>" name="bejegyzes_id" />
                <input type="submit" value="küldés">
            </form>
        </div>
    </div>

<?php } ?>
</body>
<footer class="footer">

    <p>All right reserved 2023.</p>

</footer>