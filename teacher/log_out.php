<?php
session_start();
unset($_SESSION['teacher_id']);
unset($_SESSION['student_id']);
unset($_SESSION['last_name']);
unset($_SESSION['first_name']);
unset($_SESSION['image_id']);
header('Location: log_in.php');
exit();
?>