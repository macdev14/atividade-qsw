<?php
   

    if (!isset($_SESSION['id'])) {
        header("Location: redirecionar.php");
        exit();
    } 
?>
