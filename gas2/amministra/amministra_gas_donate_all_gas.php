<?php


   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_gestire_retegas)){
     pussa_via();
}

if($do=="do_importo"){
   if(!isset($id_gas)){
       pussa_via();
       die();
   }
   $query = "SELECT * from maaking_users WHERE id_gas='$id_gas' AND isactive='1'";
   $res = $db->sql_query($query);
   $num_utenti = $db->sql_numrows($res);
   $importo_singolo = round(CAST_TO_FLOAT($importo/$num_utenti,0),2);
   $log = "DONAZIONE GAS IMPORTO TOTALE : $importo Eu;<br>";
   $log .= "GAS : $id_gas<br>";
   if (valuta_valida($importo_singolo)){
   
            while ($row = $db->sql_fetchrow($res)){
                
                $log.=  $row["userid"]." - ". $row["fullname"].": $importo_singolo Eu.<br>";
                write_option_decimal($row["userid"],"DONATE",$importo_singolo);
             }  
   }
   log_me(0,_USER_ID,"DON","GAS","Donazione GAS $id_gas",0,$log);
   $msg = "Fatto";
   
    
    
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Donate All gas";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = amministra_menu_completo();

//Assegno le due tabelle a tablesorter
$r->javascripts_header[]=java_head_select2();
$r->javascripts[] = ' <script>
                            $(document).ready(function() { $("#lista_gas").select2(); });
                      </script>';

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
if(isset($msg)){$r->messaggio=$msg;}

//GAS SELECT




//Contenuto
$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Donazione effettuata da tutto il gas</h3>";
$h .= "<form class=\"retegas_form ui-corner-all\">";
$h .= '<div>
        
        <label for="id_gas">Gas Donante</label>
        <select name= "id_gas" id="lista_gas" style="width:30em;">';
        
        $result = $db->sql_query("SELECT * FROM retegas_gas ORDER BY id_gas ASC");
        $totalrows = $db->sql_numrows($result);
        $h .= "<option value=\"-1\">Selezionare GAS</option>";
        while ($row = $db->sql_fetchrow($result)){
                $idgas = $row['id_gas'];
                $descrizionegas = $row['descrizione_gas'];
                if ($idgas==$id_gas){$agg=" selected ";}else{$agg=null;}
        $h .= "<option value=\"".$idgas ."\" $agg>".$descrizionegas ."  </option>";   
         }//end while
        $h .='</select>
        
        </div>';
$h .= '<div>
        <label for="importo">Importo</label>
        <input name="importo" value="'.$importo.'" size="20"></input>
        </div>';
$h .= '<div>
        
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Assegna importo" align="center" >
        <input type="hidden" name="do" value="do_importo">
        </div>';        
$h .= "</form>";
$h .= "</div>"; 

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>