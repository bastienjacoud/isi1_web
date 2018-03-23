<?php

function getDb()
{
    return new PDO("mysql:host=localhost;dbname=bdd;charset=utf8", "root", "",
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        
}

 function prepareChaineHtml($schaine)
 {    
     $text = htmlspecialchars($schaine);
     return $text;
 }
 
 function moisEnFrancais($schaine)
 {
     $lesMois = array(
         
         'January'   => 'Janvier',
         'February'  => 'Février',
         'March'     => 'Mars',
         'April'     => 'Avril',
         'May'       => 'Mai',
         'June'      => 'Juin',
         'July'      => 'Juillet',
         'August'    => 'Août',
         'September' => 'Septembre',
         'October'   => 'Octobre',
         'November'  => 'Novembre',
         'December'  => 'Décembre',      
     );
     
     return $lesMois[$schaine];
 }
 
 function getTabMoisDeAnnee()
 {
     $tabMois = array(
         
         '0'  => 'Janvier',
         '1'  => 'Février',
         '2'  => 'Mars',
         '3'  => 'Avril',
         '4'  => 'Mai',
         '5'  => 'Juin',
         '6'  => 'Juillet',
         '7'  => 'Août',
         '8'  => 'Septembre',
         '9'  => 'Octobre',
         '10' => 'Novembre',
         '11' => 'Décembre',      
     );
     
     return $tabMois;
 }
 
function getTabMoisDeAnnee_0()
 {
     $tabMois = array(
         
         '01'  => 'Janvier',
         '02'  => 'Février',
         '03'  => 'Mars',
         '04'  => 'Avril',
         '05'  => 'Mai',
         '06'  => 'Juin',
         '07'  => 'Juillet',
         '08'  => 'Août',
         '09'  => 'Septembre',
         '10'  => 'Octobre',
         '11' => 'Novembre',
         '12' => 'Décembre',      
     );
     
     return $tabMois;
 }
 
     
 
    


?>

