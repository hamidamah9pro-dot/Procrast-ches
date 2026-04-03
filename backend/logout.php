<?php
session_start();

$user = $_SESSION['user_id'];
$_SESSION = array();

header("location: ../frontend/index.html");
exit;
?>