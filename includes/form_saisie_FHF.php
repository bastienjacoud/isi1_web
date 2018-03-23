
<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    if(isset($_POST['btnFHF']))
    {
        try 
        {
            $pdo= getDb();
            
            $idVis = $_SESSION['id'];
            $mois = date('Ym');
            $libelle = $_POST['libelle'];
            $montant = $_POST['montant'];
            
            $req = "select count(*),nbJustificatifs,montantValide from fichefrais where idVisiteur = :id and mois = :m";
            $prep = $pdo->prepare($req);
            $prep->bindValue(':id', $idVis, PDO::PARAM_STR);
            $prep->bindValue(':m', $mois, PDO::PARAM_STR);
            
            $prep->execute();
            
            $rep = $prep->fetchAll();
            $prep->closeCursor();
            $prep = NULL;

            
            if($rep[0][0] == 0)
            {
                
                $req1 = "INSERT INTO fichefrais VALUES ('$idVis', '$mois', '1', '$montant', CURDATE(), 'CR')";
                
                $prep1 = $pdo->prepare($req1);
                
                $prep1->execute();
                 
                $prep1->closeCursor();
                $prep1 = NULL;
                
            }
            else
            {
                $montantValide = $montant + $rep[0][2];
                $nbJustificatifs = $rep[0][1] + 1;
                
                $req3 = "UPDATE fichefrais SET nbJustificatifs = '$nbJustificatifs', montantValide='$montantValide' WHERE idVisiteur = :id and mois = :m";
                $prep3=$pdo->prepare($req3);
                $prep3->bindValue(':id', $idVis, PDO::PARAM_STR);
                $prep3->bindValue(':m', $mois, PDO::PARAM_STR);
                
                $prep3->execute();
                
                $prep3->closeCursor();
                $prep3 = NULL;
            }
            
            
            $req2 = "INSERT INTO lignefraishorsforfait VALUES ('NULL', '$idVis', '$mois', '$libelle', CURDATE(), '$montant') ";
            $prep2 = $pdo->prepare($req2);
            $prep2->execute();
            
            $prep2->closeCursor();
            $prep2 = NULL;
            
        } 
        catch (Exception $ex) 
        {
            $errorMsg = "Erreur !: " . $ex->getMessage() . "<br/>";
        }

    }
?>
<div class="col-sm-12">
    <h2 class='titre_section'>Saisie des frais hors forfaits <span class="glyphicon glyphicon-pencil"></span></h2>
    <form class ="form-horizontal" method ="post" action ="saisiefichefrais.php">  
    <table class="col-sm-8 col-sm-offset-2">
        <tr>
            <td class="col-sm-2">
                <label for="libelle">Libellé :</label>
            </td>
               
            <td class="col-sm-offset-4 col-sm-6">
                <textarea class="form-control" name="libelle" id="libelle" placeholder="Libellé"></textarea>
            </td>
        </tr>
        
        <tr>
            <td class="col-sm-2">
                <label for="montant">Montant : </label>
            </td>
                
            <td class="col-sm-offset-4 col-sm-6">
                <input class="form-control" name="montant" type='number' id="montant" placeholder="0">
            </td>
        </tr>
        
        <tr>
            <td class="col-sm-2"></td>
            <td class="col-sm-offset-4 col-sm-6">
                <button type="submit" name="btnFHF" class="btn btn-default">Enregistrer</button>
            </td>
        </tr>
    </table>
    </form> 
</div>



