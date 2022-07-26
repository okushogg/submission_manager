<?php
session_start();
unset($_SESSION['auth']);
header('Location: log_in.php');
exit();
?>