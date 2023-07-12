<html lang="hu" xmlns="http://www.w3.org/1999/html">
<head>
    <link rel=stylesheet href="../CSS/registration.css"/>
</head>
<body>
<?php

// adatbázis kapcsolódási adatok
$conn = oci_connect("ADMIN", "Asdyxc123456", "csetnet_high", 'AL32UTF8');
echo "<script>console.log('Sikeres kapcsolat!');</script>";
if (!$conn) {
    $m = oci_error();
    echo $m, "\n";
    exit;
}


// adatbázis kapcsolódási adatok
$conn = oci_connect("ADMIN", "Asdyxc123456", "csetnet_high", 'AL32UTF8');
if (!$conn) {
    $m = oci_error();
    echo $m, "\n";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //lekérjük az id maximum értékét, majd növeljük 1-el
    $sql_max_id = "SELECT MAX(felh_id) AS max_id FROM felhasznalo";
    $stid_max_id = oci_parse($conn, $sql_max_id);
    oci_execute($stid_max_id);
    $max_id_row = oci_fetch_array($stid_max_id, OCI_ASSOC);
    $max_id = $max_id_row['MAX_ID'];
    // Beállítjuk az új id értéket
    $new_id = $max_id + 1;

    // felhasználó regisztrálása
    $stmt = oci_parse($conn, "INSERT INTO Felhasznalo (FELH_ID, FELH_NEV, FELH_EMAIL, FELH_JELSZO, FELH_TELSZAM) VALUES (:id, :name, :email, :password, :phone)");


    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $phone = $_POST["phone"];

    oci_bind_by_name($stmt, ":id", $new_id);
    oci_bind_by_name($stmt, ":name", $name);
    oci_bind_by_name($stmt, ":email", $email);
    oci_bind_by_name($stmt, ":password", $password);
    oci_bind_by_name($stmt, ":phone", $phone);

    if (!oci_execute($stmt)) {
        // hiba esetén átirányítás a hiba oldalra
        echo "<script>console.log('Error');</script>";
        exit;
    } else {
        echo "<script>console.log('Sikeres regisztráció!');</script>";
        header("Location: login.php");
    }
}

?>


<div class="regcontainer">
    <form method="post" action="registration.php">
        <h2 class="registration-heading">Regisztráció</h2>
        <label for="name">Név:</label><br>
        <input type="text" id="name" name="name" required><br>

        <label for="email">E-mail cím:</label><br>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Jelszó:</label><br>
        <input type="password" id="password" name="password" required><br>

        <label for="phone">Telefonszám:</label><br>
        <input type="tel" id="phone" name="phone" required><br>

        <input type="submit" value="Regisztráció">
        <h2><a href="login.php">Már van fiókom</a></h2>
    </form>
</div>