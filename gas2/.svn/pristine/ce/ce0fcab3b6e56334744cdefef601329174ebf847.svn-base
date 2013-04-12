<?php   $_FUNCTION_LOADER=array("mobile",
                                "ordini",
                                "ordini_valori",
                                "gas",
                                "listini",
                                "ditte",
                                "tipologie",
                                "geocoding");

include_once ("../rend.php");
include_once ("../jqm.class.php");


//Controllo su login
if(!_USER_LOGGED_IN){   
    go("sommario_mobile");       
}

if(!(_USER_PERMISSIONS & perm::puo_partecipare_ordini)){
    go("sommario_mobile");              
}

if(!isset($id_ordine)){
    go("sommario_mobile"); 
}
(int)$id_ordine;


//Capire se è un ordine partecipabile
if($do=="do_referente"){
    if($accetto=="si"){
        $result=$db->sql_query("UPDATE retegas_referenze SET retegas_referenze.id_utente_referenze = '"._USER_ID."'
                                WHERE (((retegas_referenze.id_ordine_referenze)='$id_ordine') AND ((retegas_referenze.id_gas_referenze)='"._USER_ID_GAS."'));");
        
        $da_chi = fullname_from_id(_USER_ID);
        $mail_da_chi = email_from_id(_USER_ID);
           
    }else{
        $msg = "Per diventare Referente del tuo GAS devi prima accettare le condizioni di partecipazione, spuntando la casellina di accettazione";
        unset($do);
     } 
}



if(ordine_partecipabile($id_ordine)){
    if (id_referente_ordine_proprio_gas($id_ordine,id_gas_user(_USER_ID))==0){            
            //DIVENTA REFERENTE
            
            $cont = $msg.' 
                        <form action="'.$RG_addr["m_ordini_partecipa"].'" method="post" data-ajax="false">
                        <div data-role="fieldcontain">
                            <fieldset data-role="controlgroup">
                               <legend>Accetto di diventare referente di questo ordine per il MIO gas. Questo vuol dire che dovrò occuparmi di raccogliere gli ordini che mi perverranno dagli iscritti al mio GAS, e dovrò gestire il recupero della merce ed i pagamenti che verranno effettuati al gestore principale dell\'ordine. Cliccando la casella qui sotto mi impegno a rispettare gli impegni presi.</legend>
                               <input type="checkbox" name="accetto" id="accetto" value="si"/>
                               <label for="accetto">Accetto</label>
                            </fieldset>
                        </div>
                              <input type="hidden" name="id_ordine" id="do" value="'.$id_ordine.'"/>
                              <input type="hidden" name="do" id="do" value="do_referente"/>
                              <input type="submit" name="submit" id="submit" value="Procedi"/>  
                              <a href="'.$RG_addr["m_ordini_scheda"].'?id_ordine='.$id_ordine.'" data-role="button">No, Grazie</a>
                    </form>';
    }else{
    
    //----------------------------------------------------------PARTECIPA
    
    $id_listini = id_listino_from_id_ordine($id_ordine);
    $l = new jqm_list();
    $l->jqm_list_attrib[] = " data-inset=\"true\" ";
    
    $query = "SELECT * FROM retegas_articoli WHERE id_listini='$id_listini';";
    $res = $db->sql_query($query);
    while ($row = mysql_fetch_array($res)){
    $val_mio = valore_netto_arr_articolo_ordine_user($row["id_articoli"],$id_ordine,_USER_ID);
    if($val_mio>0){
        $in_ordine = "<h6>In Ordine: $val_mio Eu.</h6>";
        $split="";
        $dt = "data-theme=\"e\" ";
    }else{
        $in_ordine = "";
        $split="";
        $dt="";
    }
        
    $l->jqm_list_items[]="
    <li $dt>
        <a  href=\"".$RG_addr["m_ordini_partecipa_art"]."?id_ordine=$id_ordine&id_articolo=".$row["id_articoli"]."\">
            <h6>".$row["codice"]." - ".$row["descrizione_articoli"]."</h6>
            $in_ordine
            <p>".$row["u_misura"]." ".$row["misura"]." x Eu. ".$row["prezzo"]."</p> 
        </a>
    </li>";   
    
    
    }    
    $cont = $l->jqm_list_render();
    unset($l);
            
        
        
        
        
             
    }
}else{
        $cont = "Ordine non partecipabile";
}       
                
//Nuovo oggetto Jquery MObile
$j = new jqm(load_jqm_param());

//-------------------------------------------------------PAG 1                                
//Nuova pagina con relativi parametri
$p = new jqm_page(load_page_param());
//Negli attributi assegno un ID
$p->jqm_page_attrib="id=\"scheda_ordine\"";
$p->jqm_footer_hide= true;
//Assegno la navbar

$n = new jqm_navbar(load_scheda_navbar(null,$id_ordine));
$n->jqm_navbar_set_item_attrib(1,"class=\"ui-btn-active ui-state-persist\"");
$p->jqm_header_navbar=$n->jqm_render_navbar();
//Assegno i contenuti
$p->jqm_page_content =  schedona_ordine_mobile($id_ordine,_USER_ID).
                        "<h3>Partecipa</h3>".
                        $cont;
//Creo la pagina
$j->jqm_pages[]=$p->jqm_render_page();
unset($p);

//-------------------------------------------------------PAG 1


//Mia spesa
//----------------------------------------------------------PARTECIPA
    
    
    $l = new jqm_list();
    $l->jqm_list_attrib[] = " data-inset=\"true\" ";
    
    $query = "SELECT * FROM retegas_articoli WHERE id_listini='$id_listini';";
    $res = $db->sql_query($query);
    while ($row = mysql_fetch_array($res)){
    $val_mio = valore_netto_arr_articolo_ordine_user($row["id_articoli"],$id_ordine,_USER_ID);
    if($val_mio>0){
        
        $split="";
        $dt = "data-theme=\"e\" ";
        $l->jqm_list_items[]="
        <li $dt>
            <a  href=\"".$RG_addr["m_ordini_partecipa_art"]."?id_ordine=$id_ordine&id_articolo=".$row["id_articoli"]."\">
                <h6>".$row["codice"]." - ".$row["descrizione_articoli"]."</h6>
                <p>".$row["u_misura"]." ".$row["misura"]." x Eu. ".$row["prezzo"]."</p> 
                <h4>In Ordine: $val_mio Eu.</h4>
            </a>
        </li>";
        
        
    }else{
        
        $split="";
        $dt="";
    }
        
    
    }    
    $cont_2 = $l->jqm_list_render();
    unset($l);



//-------------------------------------------------------PAG 2                                
//Nuova pagina con relativi parametri
$p = new jqm_page(load_page_param());
//Negli attributi assegno un ID
$p->jqm_page_attrib="id=\"mia_spesa\"";
$p->jqm_footer_hide= true;
//Assegno la navbar

$n = new jqm_navbar(load_scheda_navbar(null,$id_ordine));
$n->jqm_navbar_set_item_attrib(2,"class=\"ui-btn-active ui-state-persist\"");
$p->jqm_header_navbar=$n->jqm_render_navbar();
//Assegno i contenuti
$p->jqm_page_content = schedona_ordine_mobile($id_ordine,_USER_ID).
                       "<h3>Articoli ordinati</h3>".
                       $cont_2;
//Creo la pagina
$j->jqm_pages[]=$p->jqm_render_page();
unset($p);

//-------------------------------------------------------PAG 2



//La visualizzo
echo $j->jqm_render();
unset($j);  
?>