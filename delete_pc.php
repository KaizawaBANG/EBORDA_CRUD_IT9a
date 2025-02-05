<?php
session_start();
include "db.php";

$id = $_GET["id"];
$conn->query("DELETE FROM pcs WHERE id = $id");
header("Location: dashboard.php");
exit();
?>
