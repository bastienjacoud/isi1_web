<nav class="navbar navbar-default">
    <div class="container-fluid">   
        <ul class="nav navbar-nav">
            <li class="navbar-brand">GSB Company</li>
            <li class="navbar-text"><img width="40" src ="img/gsb1.png"></li>
            <li><a class="navbar-brand" href="accueil.php">Accueil</a></li>
            <li><a class="navbar-brand" href="saisiefichefrais.php">Saisie des frais médicaux</a></li>
            <li><a class="navbar-brand" href="#">Validation fiches de frais</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <?php 
            if(isset($_SESSION['nom']))
            {?>
                <li class="navbar-brand"><span class="glyphicon glyphicon-user"></span> Bonjour <?=$_SESSION['nom']?></li>
                <li><a class="navbar-brand" href="deconnexion.php"><span class = "glyphicon glyphicon-log-out"></span>  Se Déconnecter</a></li>
            <?php
            }
            else
            {?>
                <li><a class="navbar-brand" href="seConnecter.php"><span class = "glyphicon glyphicon-log-in"></span>  Se Connecter</a></li>
            <?php
            }
            ?>
         </ul>
    </div>
</nav>

