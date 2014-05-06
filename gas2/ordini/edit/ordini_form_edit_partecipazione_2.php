<?php
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if(!posso_gestire_ordine_full($id_ordine,_USER_ID)){
        go("ordini_form",_USER_ID,"Questa operazione ti è preclusa.","?id_ordine=$id_ordine");
        exit;
}
    
if(ordine_inesistente($id_ordine)){
        pussa_via();
        exit;
    }

if($do=="mod"){
    // controllo tutti i dati
        if(!isset($box)){
            //SE mnon c'è il box vuol dire che sono tutti cancellati,
            //tolgo tutte le referenze tranne la propria
            $result_delete = $db->sql_query("DELETE FROM retegas_referenze WHERE id_ordine_referenze='$id_ordine' AND id_gas_referenze <>'"._USER_ID_GAS."';");       
            
            
        }
        

        if($e_total==0){

        //PASSO TUTTI I GAS
        $result = $db->sql_query("SELECT * FROM retegas_gas ORDER BY id_gas ASC;");             
         while ($row = $db->sql_fetchrow($result)){
        
             //capisco il suo livello all'interno dell'ordine
              $level = gas_partecipa_ordine($id_ordine,$row["id_gas"]);
              $val = $row["id_gas"];
              
               switch ($level){

                    
                    //0 = NON C'?
                        //se c'? nella lista BOX allora lo aggiungo
                   case "0":
                        if(in_array($val,$box)){
                              
                              $res_gas = $db->sql_query("SELECT * FROM retegas_gas WHERE id_gas='$val'");
                              $row_gas = $db->sql_fetchrow($res_gas);

                              $result_add = $db->sql_query("INSERT INTO retegas_referenze (id_ordine_referenze, id_utente_referenze, id_gas_referenze, note_referenza, maggiorazione_percentuale_referenza) "
                              ." VALUES ('$id_ordine', '0', '$val', '".$row_gas["comunicazione_referenti"]."', '".$row_gas["maggiorazione_ordini"]."');");                        
                              if(!$result_add){$err++;}
                        }
                        break;
                    
                    //1 = C'? gi?
                        //se manca nella lista box allora lo cancello
                    
                   case "1":
                        
                        if(!in_array($val,$box)){    
                             $result_delete = $db->sql_query("DELETE FROM retegas_referenze WHERE id_ordine_referenze='$id_ordine' AND id_gas_referenze ='$val' LIMIT 1;");
                             if(!$result_delete){$err++;}
                        }
                        break;
                       //2 = C'? gi? e ha gi? il referente
                       //nun se ne fa nulla 

                     }    //switch
            
         }//while
        

             
             if($err==0){$msg="OK,<br>";}else{$msg="ERRORE QUERY";}
                                 
             log_me($id_ordine,$id_user,"ORD","MOD","Modificati partecipazioni ",0,"");
             $msg .="modificata partecipazione";
             
        }else{

            unset($do);    
            $msg .= "Controlla i dati e riprova<br>";
        }
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Pagina nuova";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("gas_table");
$r->javascripts[]= java_qtip(".retegas_form h5[title]");

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
if($msg){$r->messaggio=$msg;}
//Contenuto
(int)$id_ordine;
        
        //TABELLA GAS PARTECIPANTI


        $my_gas_lat = db_val_q("id_gas",_USER_ID_GAS,"gas_gc_lat","retegas_gas");
        $my_gas_lng = db_val_q("id_gas",_USER_ID_GAS,"gas_gc_lng","retegas_gas");

        $h_table ="
        <div id=\"gas_table_container\" class=\"rg_widget rg_widget_helper\" style=\"height:18em;overflow-y:auto\">
        <table id=\"gas_table\" >
        <thead>
            <th>GAS</th>
            <th>DES</th>
            <th>Utenti attivi attualmente / Utenti totali</th>
            <th data-sorter=\"numeric\" data-sortinitialorder=\"asc\">Distanza</th>
            <th>&nbsp;</th>
        </thead>
        <tbody>
        ";

        $result = $db->sql_query("SELECT * FROM retegas_gas;");             
        while ($row = $db->sql_fetchrow($result)){
            $riga++;
            $gas = $row["descrizione_gas"];
            $id_gas = $row["id_gas"];
            $id_des = $row["id_des"];
            $ute = gas_n_user_Act($id_gas);
            $utot = gas_n_user($id_gas);
            $des_nome = db_val_q("id_des",$row["id_des"],"des_descrizione","retegas_des");
            $other_gas_lat = db_val_q("id_gas",$id_gas,"gas_gc_lat","retegas_gas");
            $other_gas_lng = db_val_q("id_gas",$id_gas,"gas_gc_lng","retegas_gas");
            $distanza = round(getDistanceBetweenPointsNew($my_gas_lat,$my_gas_lng,$other_gas_lat,$other_gas_lng),1);
            
            $level_part = gas_partecipa_ordine($id_ordine,$id_gas);
            $hidden = " type = \"checkbox\" ";
            
            if($level_part>0){
               $box_selected = " checked=\"yes\" "; 
            }else{
               $box_selected = "";  
            }
            
            if($level_part>1){
               $box_imp = "NON MODIFICABILE";
               $hidden = " type = \"hidden\" "; 
            }else{
               $box_imp = "";  
            }
            
            if ($id_des>0){ //non deve essere IL DES DI SERVIZIO
            
                if (_USER_ID_GAS<>$id_gas){ // IL DES DIVERSO DAL PROPRIO 
                    
                    //$gas_ext_perm = leggi_permessi_gas($id_gas);
                    $gas_ext_perm = read_option_gas_text_new($id_gas,"_GAS_PUO_PART_ORD_EST");
                    
                    if($gas_ext_perm=="SI"){
                        $condizione = "<input $hidden name=box[] value=\"$id_gas\" ".$box_selected."> $box_imp";
                    }else{
                        $condizione = "Condivisione ordine non possibile";
                    }
                    $h_table .="
                    <tr>
                    <td>$gas</td>
                    <td>$des_nome</td>
                    <td>$ute / $utot</td >
                    <td>$distanza Km</td>
                    <td>$condizione</td>
                    </tr>
                    ";
                    
                }
            }    
        }

        $h_table .="</tbody></table>
        </div>
        "; 

        //CONTROLLO SE IL GAS PUO' PROPORRE ORDINI AGLI ALTRI
        if(read_option_gas_text_new(_USER_ID_GAS,"_GAS_PUO_COND_ORD_EST")=="NO"){
            $h_table = "<strong style=\"text-align:center;\">Il tuo GAS non può momentaneamente condividere ordini con gli altri</strong>"; 
        }

        
        $h = '<div class="rg_widget rg_widget_helper">
        <h3>Modifica la partecipazione esterna a questo ordine</h3>
    
        <form class="retegas_form" name="mod_desc" method="POST" action="">
        
        <div>
        <h4>1</h4>
        <label for="gas_partecipanti">Seleziona gli altri GAS che vuoi far partecipare a questo ordine :</label>
        <h5 title="'.$help_gas_partecipanti.'">Inf.</h5>
        '.$h_table.'
        </div>
  
        <div>
        <h4>2</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Salva le modifiche" align="center" >
        <input type="hidden" name="id_ordine" value="'.$id_ordine.'">
        <input type="hidden" name="do" value="mod">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div>
  

        </form>

        </div>';

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>