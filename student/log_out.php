<?php
session_start();
unset($_SESSION['auth']['student_id']);
unset($_SESSION['auth']['teacher_id']);
unset($_SESSION['auth']['last_name']);
unset($_SESSION['auth']['first_name']);
unset($_SESSION['auth']['student_image_id']);
unset($_SESSION['auth']['is_login']);
header('Location: log_in.php');
exit();
