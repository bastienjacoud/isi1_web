<!DOCTYPE html>

<html>
    <?php require_once 'includes/head.php';
    
        session_start();
    ?>
    
    <body>
        <div class='container'>
            
            <?php
                require_once 'includes/navBar.php';
            ?>
            
            <?php
                $formAction = "seConnecter.php";
                
                require_once 'includes/form_login.php';
            ?>
            
            <br>
            <br>
            
            <?php
                require_once 'includes/gestion-erreur.php';
                require_once 'includes/footer.php';
            ?>
        
        </div>
    </body>
</html>
      