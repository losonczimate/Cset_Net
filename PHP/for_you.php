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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lekérdezzük a legnagyobb id értéket a fenykep táblából
    $sql_max_id_kep = "SELECT NVL(MAX(kep_id), 0) AS max_id FROM Fenykep";
    $stid_max_id_kep = oci_parse($conn, $sql_max_id_kep);
    oci_execute($stid_max_id_kep);
    $max_id_row_kep = oci_fetch_array($stid_max_id_kep, OCI_ASSOC);
    $max_id_kep = $max_id_row_kep['MAX_ID'];

    $id_kep = $max_id_kep + 1;
    $url = $_POST['kepurl'];
    $leiras = $_POST['keptext'];

    $sql_insert_kep = "INSERT INTO Fenykep (kep_id, kep_url, kep_leiras) VALUES (:kep_id, :kep_url, :kep_leiras)";
    $stid_insert_kep = oci_parse($conn, $sql_insert_kep);
    oci_bind_by_name($stid_insert_kep, ':kep_id', $id_kep);
    oci_bind_by_name($stid_insert_kep, ':kep_url', $url);
    oci_bind_by_name($stid_insert_kep, ':kep_leiras', $leiras);

    if (oci_execute($stid_insert_kep)) {

        // Lekérdezzük a legnagyobb id értéket a bejegyzes táblából
        $sql_max_id_post = "SELECT MAX(bejegyzes_id) AS max_id FROM Bejegyzes";
        $stid_max_id_post = oci_parse($conn, $sql_max_id_post);
        oci_execute($stid_max_id_post);
        $max_id_row_post = oci_fetch_array($stid_max_id_post, OCI_ASSOC);
        $max_id_post = $max_id_row_post['MAX_ID'];

        $id_kep = $max_id_post + 1;
        $leiras1 = $leiras;
        $idopont = date('Y-m-d H:i');
        $csoport_id = null;

        $sql_insert_post = "INSERT INTO Bejegyzes (bejegyzes_id, bejegyzes_idopont, bejegyzes_leiras, fenykep_id, felh_id, csoport_id) 
                            VALUES (:bejegyzes_id, TO_DATE(:bejegyzes_idopont, 'YYYY-MM-DD HH24:MI'), :bejegyzes_leiras, :fenykep_id, :felh_id, :csoport_id)";
        $stid_insert_post = oci_parse($conn, $sql_insert_post);
        oci_bind_by_name($stid_insert_post, ':bejegyzes_id', $id_kep);
        oci_bind_by_name($stid_insert_post, ':bejegyzes_leiras', $leiras1);
        oci_bind_by_name($stid_insert_post, ':bejegyzes_idopont', $idopont);
        oci_bind_by_name($stid_insert_post, ':felh_id', $_SESSION['id']);
        oci_bind_by_name($stid_insert_post, ':fenykep_id', $id_kep);
        oci_bind_by_name($stid_insert_post, ':csoport_id', $csoport_id);
        if (oci_execute($stid_insert_post)) {
            echo "Sikeres posztolás!";
            header("Location: mainpage.php");
            exit;
        }
    }
}

$sql_select_posts = "SELECT B.bejegyzes_id,
TO_CHAR(B.bejegyzes_idopont, 'YYYY.MM.DD. HH24:MI') AS bejegyzes_idopont,
B.bejegyzes_leiras,
F.kep_url,
F.kep_leiras,
U.felh_nev
FROM Bejegyzes B
JOIN Felhasznalo U ON B.felh_id = U.felh_id
JOIN KOVETES_ID K ON U.felh_id = K.KIT
LEFT JOIN Fenykep F ON B.fenykep_id = F.kep_id
WHERE K.KI = ".$_SESSION["id"]." ORDER BY B.bejegyzes_idopont DESC";
$stid_select_posts = oci_parse($conn, $sql_select_posts);
//oci_bind_by_name($sql_select_posts, ':user_id', $_SESSION["id"]);

oci_execute($stid_select_posts);
?>
<div class="feed">
    <h1>Bejegyzés létrehozása</h1>

    <form method="post" class="feedform" action="mainpage.php">
        <label for="kepurl">Kép url:</label>
        <textarea id="kepurl" name="kepurl" placeholder="Kép url"></textarea>
        <label for="keptext">Kép leírás:</label>
        <textarea id="keptext" name="keptext" placeholder="Kép leírása"></textarea>
        <input type="submit" class="submit_button" name="submit" value="Hozzáadás">
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
