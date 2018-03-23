<!DOCTYPE html>

<html>
    <?php 
        require_once 'includes/head.php';
        session_start();
        require_once 'includes/functions.php';
    ?>
    
    
    <body> 
        
        <header>
         <?php
                require_once 'includes/navBar.php';
         ?>
        </header>
        
        <div>
            <?php
                require_once 'includes/form_FF.php';
            ?>
        </div>
        <br>
        <div>
            <?php
                require_once 'includes/form_saisie_FHF.php';
            ?>
        </div>
        <br><br>
        <div>
            <?php
                require_once 'includes/table_FHF.php';
            ?>
        </div>
            
        <footer>
            <?php
                require_once 'includes/gestion-erreur.php';
                require_once 'includes/footer.php';
            ?>
        </footer>
            
    </body>   
    
</html>
      