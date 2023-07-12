<!DOCTYPE html>
<html lang="hu">
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>



    </script>
    <meta charset="UTF-8">
    <title>Cset.net</title>
    <link rel="stylesheet" type="text/css" href="../CSS/mainpage.css">
    <style>
        .fixed-form {
            position: fixed;
            top: 80px;
            left: 20px;
            width: 220px;
            padding: 10px;
            background-color: #ffe5e5;
            border-bottom: 2px solid #ccc;
            z-index: 999;
            border-radius: 5px;
        }
        .container {

            margin: 0 auto;
            padding: 20px;
        }

        .textbox {
            box-sizing: border-box;
            width: 80%;
            padding: 10px;
            margin-bottom: 20px;
            border: 2px solid #ccc;
            border-radius: 10px;
            background-color: #fff;
        }

        .textbox.left {
            float: left;
            margin-right: 5%;
        }

        .textbox.right {
            float: right;
            background-color: blue;
            color: #fff;
            margin-left: 5%;
        }

        .clear {
            clear: both;
        }
        .bottom-form {
            margin-top: 50px;
            padding: 10px;
            background-color: #fff;
            border: 2px solid #ccc;
            border-radius: 10px;
        }

    </style>
</head>
<body onload="scrollToBottom();">
<div class="nav-buttons">
    <a href="profile.php">Profil</a>
    <a href="mainpage.php">Főoldal</a>
    <a href="logout.php">Kijelentkezés</a>
    <a href="follow.php">Követés</a>
    <a href="followers.php">Ki követ</a>
    <a href="for_you.php">Neked</a>
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



// lebegő doboz hogy kivel akarsz beszélni
echo "<div id='demo' class='fixed-form'>";
echo "<form method='post' action='messaging_with.php'>";
echo "<label for='input'>";
if ($_SESSION["messaging_partner_ID"]){
    echo "Most vele beszélsz:";
    echo $_SESSION["messaging_partner_nev"];
    echo "<br>";
}
echo "Vele szeretnék beszélni:</label>";
echo "<input type='text' id='email' name='email'>";
echo "<button type='submit'>Mehet</button>";
echo "</form>";
echo "</div>";




if ($_SESSION["messaging_partner_ID"]) {



    // az uzenetek lekérése a két felhasználó között
    $user_id = $_SESSION["id"];
    $partner_id = $_SESSION["messaging_partner_ID"];

    $stmt = oci_parse($conn, "SELECT UZENET_ID, TARTALOM, KULDES_IDEJE, KULDO FROM UZENET WHERE (KULDO=:user_id AND FOGADO=:partner_id) OR (KULDO=:partner_id AND FOGADO=:user_id) ORDER BY uzenet.uzenet_id");
    oci_bind_by_name($stmt, ':user_id', $user_id);
    oci_bind_by_name($stmt, ':partner_id', $partner_id);

    oci_execute($stmt);



    echo "<div class='container'>";


    $num_boxes = 10; // Change this to the number of text boxes you want
    for ($i = 1; $row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS); $i++) {
        if ($row["KULDO"] == $_SESSION["id"]) {
            echo '<div class="textbox right">'.$_SESSION["username"].':<br>'.$row["TARTALOM"].' <br>'.$row["KULDES_IDEJE"].'</div>';
            echo '<div class="clear"></div>';
        } else {
            $_SESSION["last_message_recieved_id"] = $row["UZENET_ID"];

            echo '<div class="textbox left">'.$_SESSION["messaging_partner_nev"].':<br>'.$row["TARTALOM"].' <br>'.$row["KULDES_IDEJE"].'</div>';
            echo '<div class="clear"></div>';
        }
    }




    echo "<div class='clear'></div>";
    echo "<div class='bottom-form'>";
    echo "    <form method='post', action='send_message.php'>";
    echo "        <label for='input2'>Üzenet:</label>";
    echo "        <input type='text' id='sent_message' name='sent_message'>";
    echo "        <button type='submit'>Küldés</button>";
    echo "    </form>";
    echo "</div>";
    echo "<div class='clear'><br><br></div>";
    echo "<div class='clear'></div>";

    echo "</div>";

}




// Adatbáziskapcsolat lezárása
oci_close($conn);


?>


<script>
    function scrollToBottom() {
        $(document).ready(function(){
            $("html,body").scrollTop(document.body.scrollHeight);
        });
    }

</script>


</body>
<footer class="footer">

    <p>All right reserved 2023.</p>

</footer>