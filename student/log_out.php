<?php
session_start();
unset($_SESSION['student_id']);
unset($_SESSION['teacher_id']);
unset($_SESSION['last_name']);
unset($_SESSION['first_name']);
unset($_SESSION['student_image_id']);
unset($_SESSION['class_id']);
unset($_SESSION['this_year_grade']);
unset($_SESSION['this_year_class']);
header('Location: log_in.php');
exit();
