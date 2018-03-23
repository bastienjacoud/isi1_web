
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    $pdo= getDb();
    // Récupération des valeurs de la table frais forfait (pour réaliser le formulaire)
    try 
    {
        $req = "SELECT id,libelle,montant from fraisforfait";
        $prep=$pdo->prepare($req);
        $prep->execute();
        $rep = $prep->fetchAll();
        $prep->closeCursor();
        $prep = NULL;   
    } 
    catch (Exception $ex) 
    {
        $errorMsg = "Erreur !: " . $ex->getMessage() . "<br/>";
    }
    //Si soumission du bouton enregistrer
    if(isset($_POST['btnFF']))
    {
        $idVis = $_SESSION['id'];
        $mois = date('Ym');
        //Vérification d'existence d'une fiche de frais + si existence, récupération du nombre actuel de justificatifs et du montant des frais
        try
        {
            $req2 = "select count(*),nbJustificatifs,montantValide from fichefrais where idVisiteur = :id and mois = :m";
            $prep2 = $pdo->prepare($req2);
            $prep2->bindValue(':id', $idVis, PDO::PARAM_STR);
            $prep2->bindValue(':m', $mois, PDO::PARAM_STR);

            $prep2->execute();

            $rep2 = $prep2->fetchAll();
            $prep2->closeCursor();
            $prep2 = NULL;
        } catch (Exception $ex) {
            $errorMsg = "Erreur !: " . $ex->getMessage() . "<br/>";
        }

        $montant = 0; // Montant de tous les forfaits achetés (pour la saisie actuelle du formulaire)
        $nbJust=0; // Nombre de forfaits achetés (pour la saisie actuelle du formulaire)
        //On récupère le montant et le nombre de justificatifs à rajouter ou insérer dans la fiche de frais.
        foreach($_POST as $idforfait => $qte)
        {
            if($qte > 0)
            {
                try 
                {
                    $req3="SELECT montant from fraisforfait where id = :id";
                    $prep3 = $pdo->prepare($req3);
                    $prep3->bindValue(':id', $idforfait, PDO::PARAM_STR);
                    $prep3->execute();
                    $rep3 = $prep3->fetchAll();
                    $prep3->closeCursor();
                    $prep3 = NULL;
                } catch (Exception $ex) {
                    $errorMsg = "Erreur !: " . $ex->getMessage() . "<br/>";
                }
                

                $montant = $montant + $rep3[0][0]*$qte;
                $nbJust += $qte;
            }
                
        }
        
        //S'il n'y a pas de fiche de frais de créée , on la crée
        if($rep2[0][0] == 0)
        {
            
            try
            {
                $req4 = "INSERT INTO fichefrais VALUES ('$idVis', '$mois', '$nbJust', '$montant', CURDATE(), 'CR')";
                $prep4 = $pdo->prepare($req4);
                $prep4->execute();
                $prep4->closeCursor();
                $prep4 = NULL;
            } catch (Exception $ex) {
                $errorMsg = "Erreur !: " . $ex->getMessage() . "<br/>";
            }                 
        }
        //Sinon on met à jour la fiche existente
        else
        {           
            $montant += $rep2[0][2];
            $nbJust += $rep2[0][1];
            
            try
            {
                $req3 = "UPDATE fichefrais SET nbJustificatifs = '$nbJust', montantValide='$montant' WHERE idVisiteur = :id and mois = :m";
                $prep3=$pdo->prepare($req3);
                $prep3->bindValue(':id', $idVis, PDO::PARAM_STR);
                $prep3->bindValue(':m', $mois, PDO::PARAM_STR);

                $prep3->execute();

                $prep3->closeCursor();
                $prep3 = NULL;
            } catch (Exception $ex) {
                $errorMsg = "Erreur !: " . $ex->getMessage() . "<br/>";
            }            
        }
        
        
        //On ajoute ou modifie autant de lignes que nécéssaires selon les forfaits achetés (dans la table lignefraisforfait
        foreach($_POST as $idforfait => $qte)
        {
            // Si la quantité n'est pas nulle (si on a commandé un forfait).
            if($qte > 0)
            {
                // On regarde si une ligne existe ou non ( savoir si on modifie ou crée une ligne)
                try 
                {
                    $req="select count(*),quantite from lignefraisforfait where idVisiteur= :idVis and mois= :m and idFraisForfait= :idF";
                    $prep=$pdo->prepare($req);
                    $prep->bindValue(':idVis', $idVis, PDO::PARAM_STR);
                    $prep->bindValue(':m', $mois, PDO::PARAM_STR);
                    $prep->bindValue(':idF', $idforfait, PDO::PARAM_STR);
                    $prep->execute();
                    
                    $res=$prep->fetchAll();
                    $prep->closeCursor();
                    $prep =NULL;
                    
                } 
                catch (Exception $ex) 
                {
                    $errorMsg = "Erreur !: " . $ex->getMessage() . "<br/>";
                }
                
                // Si aucune ligne existe pour ce mois, cet utilisateur et ce forfait, on insère la ligne
                if($res[0][0] == 0)
                {
                    try
                    {
                        $req="INSERT INTO lignefraisforfait VALUES ('$idVis', '$mois', '$idforfait', '$qte')";
                        $prep=$pdo->prepare($req);
                        $prep->execute();

                        $prep->closeCursor();
                        $prep = NULL;
                    } 
                    catch (Exception $ex) 
                    {
                        $errorMsg = "Erreur !: " . $ex->getMessage() . "<br/>";
                    }
                }
                // Sinon, on modifie la ligne
                else
                {
                    $qte += $res[0][1];
                    
                    try
                    {
                        $req="UPDATE lignefraisforfait SET quantite = '$qte' where idVisiteur= :idVis and mois= :m and idFraisForfait= :idF ";
                        $prep=$pdo->prepare($req);
                        $prep->bindValue(':idVis', $idVis, PDO::PARAM_STR);
                        $prep->bindValue(':m', $mois, PDO::PARAM_STR);
                        $prep->bindValue(':idF', $idforfait, PDO::PARAM_STR);
                        $prep->execute();

                        $prep->closeCursor();
                        $prep =NULL;
                    } 
                    catch (Exception $ex) 
                    {
                        $errorMsg = "Erreur !: " . $ex->getMessage() . "<br/>";
                    }   
                }            
            }      
        }
    }
?>

<div class="col-sm-12">
    <h2 class='titre_section'>Saisie des frais forfaitaires <span class="glyphicon glyphicon-pencil"></span></h2>
    <form class="form-horizontal" method="post" action="saisiefichefrais.php">      
        <table class="col-sm-8 col-sm-offset-2">
            <?php
            foreach($rep as $rep)
            { ?>
            <tr>
                <td class="col-sm-2">
                    <label for="<?= $rep['id']?>" ><?=$rep['libelle'] . ' :'?> </label>
                </td>
                <td class="col-sm-offset-4 col-sm-3">
                    <input class="form-control" name="<?=$rep['id']?>" type='number' placeholder="0">
                </td>
                <td class="col-sm-offset-6 col-sm-3">
                    <input class="form-control" value="<?=$rep['montant'] ?>" disabled>
                </td>
            </tr>
            <?php
            } ?>
            
            <tr>
                <td class="col-sm-2"></td>
                <td class="col-sm-offset-4 col-sm-3">
                    <button type="submit" name="btnFF" class="btn btn-default">Enregistrer</button>
                </td>
                <td class="col-sm-offset-6 col-sm-3"></td>
            </tr>
        </table>
    </form>
</div>

