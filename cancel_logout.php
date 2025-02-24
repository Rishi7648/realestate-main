<?php
session_start();

// Redirect the user back to the previous page or dashboard
header("Location: index.php"); // Replace with the appropriate page
exit();
?>