<?php
session_start();    
session_destroy();  
header("Location: profesional.php"); 
exit();
?>