<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['last_name']);
unset($_SESSION['first_name']);
header('Location: teacher/log_in.php');
exit();
?>