<?php
    require_once 'includes/functions.php';
    if(isset($_POST['login']) && isset($_POST['pwd']))
    {
        try
        {
            $pdo = getDb();
            
            $req = "select count(*),prenom,id from visiteur where login =:log and mdp=:pwd ";
            $prep = $pdo->prepare($req);
            $prep->bindValue(':log', $_POST['login'], PDO::PARAM_STR);
            $prep->bindValue(':pwd', $_POST['pwd'], PDO::PARAM_STR);

            $prep->execute();

            $rep = $prep->fetchAll();

            if($rep[0][0] == 1)
            {
                $successMsg = 'Connexion réussie. Vous allez être redirigés vers la page de saisie des frais médicaux.';
                $formAction = 'saisiefichefrais.php';
                $_SESSION['nom'] = $rep[0][1];
                $_SESSION['id'] = $rep[0][2];
                echo header("refresh:2; url= $formAction ");
            }
            else
            {
                $errorMsg = 'Utilisateur inconnu ou mot de passe incorrect.';
            }

            //Clore la requête préparée
            $prep->closeCursor();
            $prep = NULL;
        } 
        catch (Exception $ex) 
        {
             $errorMsg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
        }
   
    }
  
    if(! isset($successMsg))
    {
?>

<form class ="form-horizontal formLogin col-sm-4 col-sm-offset-4" role ="form" method ="post" action ="<?=$formAction ?>" >
    <table id ="formulaire_login">
        <tr class="form-group">
            <td>
                <label for="login">Login : </label>
            </td>
            <td>
                <input type="login"  placeholder="Login" class="form-control" id="login" name="login">
            </td>
        </tr>

        <tr class="form-group">
            <td>
                <label for="pwd">Password : </label>
            </td>
            <td>
                <input type="password"  placeholder="Password" class="form-control" id="pwd" name="pwd">
            </td>
        </tr>

        <tr class="form-group">
            <td></td>
            <td>
                <label><input type="checkbox"> Remember me</label>
                <!-- Remember me à faire (optionnel -->
            </td>
        </tr>

        <tr class ="form-group">
            <td></td>
            <td>
                <button type="submit" class="btn btn-default">Submit</button>
            </td>
        </tr>
    </table>
</form>

<?php
    }
?>



