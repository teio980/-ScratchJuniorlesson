<?php
session_start();  
session_destroy(); 
header("Location: ../bkindex.php"); 
exit();
?>