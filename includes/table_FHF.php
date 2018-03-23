<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    $pdo = getDb();
    if(isset($_GET['id_lhf']))
    {
        
        //Mise à jour de fichefrais
        try
        {
            $idVis = $_SESSION['id'];
            $mois = date('Ym');
            
            $req1="SELECT nbJustificatifs,montantValide FROM fichefrais WHERE idVisiteur = :id and mois = :m";
            $prep1 = $pdo->prepare($req1);
            $prep1->bindValue(':id', $idVis, PDO::PARAM_STR);
            $prep1->bindValue(':m', $mois, PDO::PARAM_STR);
            $prep1->execute();
            $res1=$prep1->fetchAll();
            $prep1->closeCursor();
            $prep1 = NULL;
            
            $req2="SELECT montant from lignefraishorsforfait WHERE id= :id";
            $prep2 = $pdo->prepare($req2);
            $prep2->bindValue(':id', $_GET['id_lhf'], PDO::PARAM_STR);
            $prep2->execute();
            $res2 = $prep2->fetchAll();
            $prep2->closeCursor();
            $prep2 = NULL;
            
            $nbJust = $res1[0][0] -1;
            $montant = $res1[0][1] - $res2[0][0];
            
            $req3 = "UPDATE fichefrais SET nbJustificatifs = '$nbJust', montantValide='$montant' WHERE idVisiteur = :id and mois = :m";
            $prep3=$pdo->prepare($req3);
            $prep3->bindValue(':id', $idVis, PDO::PARAM_STR);
            $prep3->bindValue(':m', $mois, PDO::PARAM_STR);
                
            $prep3->execute();
                
            $prep3->closeCursor();
            $prep3 = NULL;
            } catch (Exception $ex) 
            {
                $errorMsg = "Erreur !: " . $ex->getMessage() . "<br/>";
            }
        
        //supprssion de la ligne dans la table frais hors forfaits
        try
        {
            $req="DELETE FROM lignefraishorsforfait WHERE id= :id";
            $prep = $pdo->prepare($req);
            $prep->bindValue(':id', $_GET['id_lhf'], PDO::PARAM_STR);
            $prep->execute();
            $prep->closeCursor();
            $prep = NULL;
        } catch (Exception $ex) {
            $errorMsg = "Erreur !: " . $ex->getMessage() . "<br/>";
        }
    }

?>
<div class="col-sm-12">
    <h2 class='titre_section'>Récapitulatif des frais hors forfait du mois <?=moisEnFrancais(date("F")); ?> <span class="glyphicon glyphicon-align-justify"></span> </h2>
    <form class="col-sm-offset-2 col-sm-8" action="saisiefichefrais.php">
    <table class="table table-hover">
        <thead>
            <tr class="bg-info">
                <th>Libellé</th>
                <th>Date</th>
                <th>Montant</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>   
            <?php
            $pdo=  getDb();
            $req = "select libelle,date,montant,id from lignefraishorsforfait where idVisiteur =:login and mois =:mois";
            
            $prep = $pdo->prepare($req);
            $prep->bindValue(':login', $_SESSION['id'], PDO::PARAM_STR);
            $prep->bindValue(':mois', date("Ym"), PDO::PARAM_STR);

            $prep->execute();

            $tabLignes = $prep->fetchAll();
            
            foreach($tabLignes as $tabLignes)
            {
            ?>
            
            <!-- Modal -->
            <div id="avert<?=$tabLignes['id']?>" class="modal fade" role="dialog">
              <div class="modal-dialog modal-sm">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Avertissement</h4>
                  </div>
                  <div class="modal-body">
                    <p>Voulez-vous vraiment supprimer l'enregistrement ?</p>
                  </div>
                  <div class="modal-footer">
                    <a href="saisiefichefrais.php?id_lhf=<?=$tabLignes['id']?>" class="btn btn-danger">OUI</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">NON</button>
                  </div>
                </div>

              </div>
            </div>
        
            <tr>
                <td class="col-sm-2">
                    <?=$tabLignes['libelle']?>
                </td>
                <td class="col-sm-2 col-sm-offset-4">
                    <?=$tabLignes['date']?>
                </td>
                
                    <?php
                    if($tabLignes['montant']>=100)
                    {?>
                        <td class="rouge col-sm-2 col-sm-offset-6">
                            <?=$tabLignes['montant'];?>
                        </td>
                    <?php
                    }
                    else
                    {
                    ?>
                        <td class="col-sm-2 col-sm-offset-6">
                            <?=$tabLignes['montant'];?>
                        </td>
                    <?php
                    }
                    ?>
                <td class="col-sm-2 col-sm-offset-8">
                    <!-- Trigger the modal with a button -->
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#avert<?=$tabLignes['id']?>">Supprimer</button>
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</form>
</div>
