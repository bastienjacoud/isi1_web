<!DOCTYPE html>
<html></html>
<?php
    
    require_once 'includes/head.php';
    
    session_start();

    session_unset();
    echo 'Vous avez été déconnecté, nous allons vous rediriger vers la page d\'accueil. ';
    echo header("refresh:0; url= accueil.php ");
    
?>

</html>