<?php
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../articoli/articoli_renderer.php");
include_once ("../ordini_renderer.php");
include_once ("../../retegas.class.php");

// http://retegas.altervista.org/gas2/ordini/modifica_qta/ordini_modifica_assegnazione.php?id_articolo=10745&id_ordine=450
// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if (!(_USER_PERMISSIONS & perm::puo_partecipare_ordini)){
     go("sommario",_USER_ID,"Non puoi partecipare agli ordini. Contatta il tuo referente GAS.");
}

if (ordine_io_cosa_sono($id_ordine,_USER_ID)<2){
    go("sommario",_USER_ID,"Questo ordine non mi compete");
}

//controllo l'articolo
if(!isset($id_articolo)){
      go("sommario",_USER_ID,"Non è stato selezionato l'articolo");
}

//controllo l'ordine
if(!isset($id_ordine)){
      go("sommario",_USER_ID,"Non è stato selezionato l'ordine");
}




  if($do=="del"){
    
    $sql = "DELETE FROM retegas_dettaglio_ordini WHERE id_dettaglio_ordini='$n_riga' LIMIT 1";
    $res = $db->sql_query($sql);
    $sql = "DELETE FROM retegas_distribuzione_spesa WHERE id_riga_dettaglio_ordine='$n_riga';";
    $res = $db->sql_query($sql);
    $msg .="Riga eliminata";
    if(_USER_USA_CASSA){
        cassa_update_ordine_utente($id_ordine,_USER_ID);    
    }    
}

if($do=="do_add"){
    

    
    //Casto il valore
    $valore_da_inserire = CAST_TO_FLOAT($valore_da_inserire,0);

    //CONTROLLO LA CASSA
    if(_USER_USA_CASSA){
         $log .= "Utente che usa la cassa<br>";
         
         $valore_aggiunta = $valore_da_inserire * articolo_suo_prezzo($id_articolo);

         $vo = CAST_TO_FLOAT($valore_aggiunta,0) -  valore_totale_mio_ordine($id_ordine,_USER_ID);
         
         // Aggiungo la percentuale prestabilita al valore del mio ordine
         $vo =  round((($vo/100)* _GAS_COPERTURA_CASSA ) + $vo);
         
         $vc = cassa_utente_tutti_movimenti(_USER_ID);
         
         //se il credito non basta
         if(($vc-$vo)< _GAS_CASSA_MIN_LEVEL){
             $log .= "Credito insuff.<br>";
             $msg  = "Credito insufficiente per questo acquisto;<br>
                        Ricorda che è contemplata una percentuale del "._GAS_COPERTURA_CASSA."% di spese accessorie che vanno a sommarsi all'importo dell'ordine.<br>
                        Vi è inoltre una soglia minima di "._GAS_CASSA_MIN_LEVEL." Eu. (decisa dal tuo GAS) sotto la quale non si può ordinare.<br>
                        I totali effettivi saranno modificati o confermati ad ordine chiuso dal gestore o dal cassiere.";   
             $dove_vai = "ordini_mod_uni_new";
             go($dove_vai,_USER_ID,$msg,"?id_ordine=$id_ordine&id_articolo=$id_articolo");
         }
         
     
     
     }
    
    
    //SE E' > 0
    if($valore_da_inserire>0){
                    //echo "$valore_da_inserire > 0 (Val da ins)<br>";
                    //SE L'importo di amico ? un multiplo giusto
                    $msg .= "Articolo $id_articolo (".articolo_sua_descrizione($id_articolo).") :<br>";

                    
                    $q_min = round(articolo_sua_qmin($id_articolo),4);
                    if(is_multiplo($q_min,$valore_da_inserire)){
                        //echo "E' multiplo corretto<br>";
                        //Dall'importo - Qmin fino a 0
                        for($i=$valore_da_inserire;$i>0;$i=$i-$q_min){
                            //echo "Sono FOR i = $i : $q_min<br>";
                            //echo "INSERISCO $q_min a $id_amico <br>";
                            //INSERISCO DETTAGLIO con Qmin
                            $code = CAST_TO_INT(random_string(6,"1234567890"));
                            $log .= "Articolo $id_articolo Univoco<br>";
                            //LA QUANTITA' VECCHIA E' ZERO, DEVO INSERIRE L'ARTICOLO NUOVO
                            $query_inserimento_articolo = "INSERT INTO retegas_dettaglio_ordini ( 
                                                            id_utenti,
                                                            id_articoli,             
                                                            data_inserimento,
                                                            id_stati,
                                                            qta_ord,
                                                            id_amico,
                                                            id_ordine,
                                                            qta_arr) 
                                                            VALUES (
                                                                '"._USER_ID."',
                                                                '$id_articolo',
                                                                NOW(),
                                                                '$code',
                                                                '$q_min',
                                                                '0',
                                                                '$id_ordine',
                                                                '$q_min'
                                                                );";
                            $result = $db->sql_query($query_inserimento_articolo);
                            $log .= "Inserito DETTAGLIO<br>";
                            //NON SI SA MAI
                            usleep(200);
                            $mail_necessaria = "SI";
                            // scopro qual'? l'ultimo ID inserito (RIGA Dettaglio_ordine)

                            $n_riga = n_riga_ordini_from_code($id_ordine,$id_articolo,_USER_ID,$code);

                            //INSERISCO DISTRIBUZIONE Qmin con id_amico
                            $log .= "INSERISCO : NRIGA =$n_riga AMICO=$id_amico,  $q_min UNICA  $id_articolo<br />";
                            $msg .= "a ".amici_nome_di_amico($id_amico). ": n. $q_min <br>";
                            //SCRIVO ASSEGNAZIONE
                            $query_distribuzione_spesa = "INSERT INTO retegas_distribuzione_spesa ( 
                                                         id_riga_dettaglio_ordine,
                                                         id_amico,             
                                                         qta_ord,
                                                         qta_arr,
                                                         data_ins,
                                                         id_articoli,
                                                         id_user,
                                                         id_ordine) 
                                                         VALUES (
                                                                    '$n_riga',
                                                                    '$id_amico',
                                                                    '$q_min',
                                                                    '$q_min',
                                                                     NOW(),
                                                                    '$id_articolo',
                                                                    '"._USER_ID."',
                                                                    '$id_ordine'
                                                                    );";
                            $res_2 = $db->sql_query($query_distribuzione_spesa);
                            usleep(200);
                    
                    
                    
                        //Fine ciclo su importo
                        }
                    //Fine se ? multiplo
                    }else{
                        $msg .= "E' stata trovata una quantità non corretta e non è stata inserita.<br>";
                    }
                //Fine se ? maggiore di 0
                    
                    //SE USER USA CASSA
                    if(_USER_USA_CASSA){
                        cassa_update_ordine_utente($id_ordine,_USER_ID);    
                    }
                    
                    //SE NON C'E' NULLA DA INSERIRWE
                    }else{
                        $msg .= "Non è stato inserito nulla.<br>";
                    }
        

    
    
    
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 2;
//Assegno il titolo che compare nella barra delle info
$r->title = "Modifica assegnazione articoli univoci";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale men? orizzontale dovr?? essere associato alla pagina.
//$r->menu_orizzontale = ordini_menu_completo($user,$id_ordine);

    $r->menu_orizzontale = ordini_menu_all($id_ordine);

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}

//Creo la pagina dell'aggiunta
$r->javascripts[]=java_tablesorter("asse");

$h .="<table id=\"asse\">";
$h .="<thead>";
$h .="<tr>";
$h .="<th>Codice</th>";
$h .="<th>Articolo</th>";
$h .="<th>Quantità ord.</th>";
$h .="<th>Quantità arr.</th>";
$h .="<th>Assegnatari</th>";
$h .="<th>Operazioni</th>";
$h .="</tr>";
$h .="</thead>";
$h .="<tbody>";
$sql = "SELECT * FROM retegas_dettaglio_ordini WHERE id_articoli='$id_articolo'
        AND id_ordine='$id_ordine'
        AND id_utenti='"._USER_ID."';";
$res = $db->sql_query($sql);

//AGGIUNTA
if(stato_from_id_ord($id_ordine)==2){
    $salva = "<input type=\"submit\" class=\"awesome green\" value=\"Salva\">";
    
    $aggiungi = "<input type=\"text\" name=\"valore_da_inserire\" value=\"0\" size=\"4\">";
    
    
    $a_chi = "<select name=\"id_amico\">";
    $a_chi .= "<option value=\"0\">Me stesso</option>";
    
    $sql2 = "SELECT * FROM retegas_amici WHERE is_visible=1 
            AND status=1 
            AND id_referente='"._USER_ID."' 
            ORDER BY nome ASC;";
            
            $res2 = $db->sql_query($sql2);
    while ($row2 = mysql_fetch_array($res2)){
          $a_chi .= "<option value=\"".$row2["id_amici"]."\">".$row2["nome"]."</option>";
    }
    
    $a_chi .= "</select>";
    
    $hidden = "<input type=\"hidden\" name=\"do\" value=\"do_add\">
                <input type=\"hidden\" name=\"id_articolo\" value=\"$id_articolo\">
                <input type=\"hidden\" name=\"id_ordine\" value=\"$id_ordine\">
                <input type=\"hidden\" name=\"n_riga\" value=\"$n_riga\">";
    
    $h .="<tr>";
    $h .="<form>";
    $h .="<td>".articolo_suo_codice($id_articolo)."</td>";
    $h .="<td>".articolo_sua_descrizione($id_articolo)."</td>";
    $h .="<td>$aggiungi</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>$a_chi</td>";
    $h .="<td>$salva $hidden</td>";
    $h .="</form>";
    $h .="</tr>";
}

while ($row = mysql_fetch_array($res)){
    
    $n_riga = $row["id_dettaglio_ordini"];
    $mod = "<a class=\"awesome small yellow\" href=\"".$RG_addr["ordini_mod_uni_new_form"]."?id_ordine=$id_ordine&id_articolo=$id_articolo&n_riga=".$row["id_dettaglio_ordini"]."\">M</a>";    
    $del = "<a class=\"awesome small black\" href=\"".$RG_addr["ordini_mod_uni_new"]."?id_ordine=$id_ordine&id_articolo=$id_articolo&n_riga=".$row["id_dettaglio_ordini"]."&do=del\">E</a>";
    
    $h .="<tr>";
    $h .="<td>".articolo_suo_codice($row["id_articoli"])." - (".$row["id_dettaglio_ordini"].")</td>";
    $h .="<td>".articolo_sua_descrizione($row["id_articoli"])."</td>";
    $h .="<td>".round($row["qta_ord"],4)."</td>";
    $h .="<td>".round($row["qta_arr"],4)."</td>";
    $h .="<td>".lista_assegnatari_articolo_dettaglio($n_riga)."</td>";
    $h .="<td>$mod $del</td>";
    $h .="</tr>";
}

$h .="</tbody>";
$h .="";
$h .="";
$h .="</table>";


$totale_articolo_attuale = round(n_articoli_arrivati_da_user($id_ordine,$id_articolo,_USER_ID),4);

$is ="<p>NB : Bla bla bla</p>";
$istruzioni = rg_toggable("NOTE IMPORTANTI","nimp",$is);


$r->contenuto =     schedina_ordine($id_ordine).
                    schedina_articolo($id_articolo)
                    .$istruzioni.
                    "<p>Scegliere quale tra questi articoli vuoi dividere tra i tuoi amici. (o riassegnare in toto).<br>
                    Per aggiungerne invece usa la prima riga della tabella;<br>
                    Per eliminare usa il pulsantino nero \"Elimina\"<br>
                    Dopo che hai confermato vai nella pagine dei report per controllare l'esattezza dell'operazione eseguita.</p>"
                    ."<div class=\"rg_widget rg_widget_helper\">
                    <h3>Assegnazione attuale, per un totale di $totale_articolo_attuale articoli.</h3>"
                    .$h
                    ."</div>";
echo $r->create_retegas();

//Distruggo l'oggetto r    
unset($r)   
?>