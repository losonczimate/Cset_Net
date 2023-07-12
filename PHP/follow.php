<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Cset.net</title>
    <link rel="stylesheet" type="text/css" href="../CSS/profile.css">
    <style>
        #main-box {
            display: flex;
            justify-content: center;
            border: solid #9bfff8;
            border-radius: 8px;
        }
        #side-box {
            display: flex;
            justify-content: flex-end;
        }
    </style>
</head>
<body>
<div class="nav-buttons">
    <a href="profile.php">Profil</a>
    <a href="mainpage.php">Főoldal</a>
    <a href="logout.php">Kijelentkezés</a>
    <a href="follow.php">Követés</a>
    <a href="followers.php">Ki követ</a>
    <a href="for_you.php">Neked</a>
    <a href="messaging.php">Üzenetküldés</a>
</div>

<div id="main-box">

<div style="background-color: #cfe2f3; text-align: center; padding: 10px; border-radius: 10px;">
    <h2 style="color: #007bff; font-family: Arial, sans-serif; font-size: 24px;">Őt követed:</h2>
</div>

<div style="background-color: #cfe2f3; text-align: center; padding: 10px; border-radius: 10px;" class="update_container">
    <form method="post" action="follow_user.php" class="update_header">
        <label for="follow_him" style="color: #007bff; font-family: Arial, sans-serif; font-size: 18px;">Követem őt:</label><br>
        <input type="text" id="follow_him" name="follow_him" style="padding: 5px; border: none; border-radius: 5px; background-color: #fff;"><br>

        <input type="submit" value="Add" class="update_button" style="background-color: #007bff; color: #fff; border: none; border-radius: 5px; padding: 8px 16px; font-family: Arial, sans-serif; font-size: 18px; cursor: pointer;">
    </form>
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

// lekérjük az összes felhasználót az adatbázisból akit követ az aktuális felhasználó
$stmt = oci_parse($conn, "SELECT KIT, FELH_NEV, FELH_EMAIL, FELH_ID FROM KOVETES_ID, FELHASZNALO WHERE KI = :user_id AND FELH_ID = KOVETES_ID.KIT");
oci_bind_by_name($stmt, ':user_id', $_SESSION["id"]);

oci_execute($stmt);



// kiírjuk az eredményt, minden felhasználót egy külön div-be helyezve
echo '<div class="container">';
while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
    echo '<div class="userdata item">';
    echo "<script>console.log('Sikeres!');</script>";
    echo '<p>'.$row["FELH_NEV"].'</p>';
    echo '<p>'.$row["FELH_EMAIL"].'</p>';
    echo '<form action="unfollow.php" method="post">';
    echo '<input type="hidden" name="felh_id" value="'.$row["FELH_ID"].'" />';
    echo '<button type="submit">Unfollow</button>';
    echo '</form>';

    $_SESSION["username"] = $row["FELH_NEV"];
    echo '</div>';
}
echo '</div></div><div id="side-box">';

echo '<div style="background-color: #cfe2f3; text-align: center; padding: 10px; border-radius: 10px;">';
echo '    <h2 style="color: #007bff; font-family: Arial, sans-serif; font-size: 24px;">Még nem követed:</h2>';
echo '</div>';





// lekérjük az összes felhasználót akiket követhetne a felhasználó az alapján hogy kommentelt a bejegyzésükre de még nem követi őket
$stmt = oci_parse($conn, "SELECT COUNT(*) as KOMMENTEK_SZAMA, BEJEGYZES.FELH_ID, FELHASZNALO.FELH_EMAIL, FELHASZNALO.FELH_NEV FROM KOMMENTEK, BEJEGYZES, FELHASZNALO
WHERE BEJEGYZES.BEJEGYZES_ID = KOMMENTEK.BEJEGYZES_ID
AND KOMMENTEK.KOMMENTELO_ID = :user_id
AND BEJEGYZES.FELH_ID = FELHASZNALO.FELH_ID
AND BEJEGYZES.FELH_ID NOT IN (SELECT KIT FROM KOVETES_ID WHERE KI = :user_id)
AND BEJEGYZES.FELH_ID NOT IN (:user_id)
GROUP BY BEJEGYZES.FELH_ID, FELHASZNALO.FELH_EMAIL, FELHASZNALO.FELH_NEV");
oci_bind_by_name($stmt, ':user_id', $_SESSION["id"]);

oci_execute($stmt);
// kiírjuk az eredményt, minden felhasználót egy külön div-be helyezve
echo '<div class="container">';
while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
    echo '<div class="userdata item">';
    echo "<script>console.log('Sikeres!');</script>";
    echo '<p>'.$row["FELH_NEV"].'</p>';
    echo '<p>'.$row["FELH_EMAIL"].'</p>';

    echo '<form action="follow_user.php" method="post">';
    echo '<input type="hidden" id="follow_him" name="follow_him" value="'.$row["FELH_EMAIL"].'" />';
    echo '<button type="submit">Követés</button>';
    echo '</form>';

    // $_SESSION["username"] = $row["FELH_NEV"];
    echo '</div>';
}
echo '</div></div>';


// Adatbáziskapcsolat lezárása
oci_close($conn);
?>
</body>
<footer class="footer">

    <p>All right reserved 2023.</p>

</footer>