<?php
$database = "your_database_name";
$username = "your_database_username";
$password = "your_database_password";

$conn = oci_connect($username, $password, $database);

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
    $file_name = $_FILES['files']['name'][$key];
    $file_size = $_FILES['files']['size'][$key];
    $file_type = $_FILES['files']['type'][$key];
    $file_content = file_get_contents($tmp_name);

    $query = "INSERT INTO pictures (file_name, file_size, file_type, file_content) VALUES (:file_name, :file_size, :file_type, :file_content)";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ":file_name", $file_name);
    oci_bind_by_name($stmt, ":file_size", $file_size);
    oci_bind_by_name($stmt, ":file_type", $file_type);
    oci_bind_by_name($stmt, ":file_content", $file_content, -1, OCI_B_BLOB);
    oci_execute($stmt, OCI_DEFAULT);
}

oci_commit($conn);

oci_free_statement($stmt);
oci_close($conn);

echo "Files uploaded successfully.";
?>
