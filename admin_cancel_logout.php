<?php
session_start();

// Redirect the admin back to the admin panel
header("Location: admin.php"); // Replace with the appropriate admin panel page
exit();
?>