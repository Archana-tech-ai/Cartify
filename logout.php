<?php
session_start();
session_destroy();
header("Location: index.php");
exit;
?>
<link rel="stylesheet" href="assets/css/style.css">