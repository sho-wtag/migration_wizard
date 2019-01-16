<?php
//progress.php
session_start();

$arr = array();
$arr["progress"] = $_SESSION['progress'];
$arr["total"] = $_SESSION['total'];
echo json_encode($arr);
?>