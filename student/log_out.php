<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['last_name']);
unset($_SESSION['first_name']);
unset($_SESSION['image_id']);
unset($_SESSION['class_id']);
header('Location: log_in.php');
exit();
?>