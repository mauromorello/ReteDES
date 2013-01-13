<?php

  
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//pulizia
if(!isset($id_ordine)){
    pussa_via();
}

$id_ordine = CAST_TO_INT($id_ordine);

//Se l'ordine non esiste
if(ordine_inesistente($id_ordine)){
    pussa_via();
}

//se non sono il referente ordine
if(id_referente_ordine_globale($id_ordine)<>_USER_ID){
    pussa_via();
}

if($do=="do_rettifica"){
  //print_r($box_valori);echo"<br>";
  //print_r($box_id_dettaglio);echo"<br>";
  //echo(mimmo_decode($id_utente_target));echo"<br>";
  //echo($id_ordine);echo"<br>";
      $r=0;
    $msg="";
    while (list ($key,$val) = @each ($box_id_dettaglio)) {        
        (float)$box_valori[$key]=($box_valori[$key]/$box_prezzi[$key]);
        //echo "KEY = $key, VAL = $val , Q_ARR = ".$box_q_arr[$key]." BOX importo = ".$box_imp[$key]." Box prezzo = ".$box_prezzo[$key]."<br>";    
    }    
    reset($box_id_dettaglio);
    
    while (list ($key,$val) = @each ($box_id_dettaglio)) { // PASSO LA LISTA DEGLI ARTICOLI
    
    $result2 = $db->sql_query("UPDATE retegas_dettaglio_ordini 
                            SET retegas_dettaglio_ordini.qta_arr = '$box_valori[$key]', 
                                retegas_dettaglio_ordini.data_convalida = NOW()
                            WHERE (retegas_dettaglio_ordini.id_dettaglio_ordini='$val');");
                    
    ridistribuisci_quantita_amici_1($val,$box_valori[$key],$msg);
    
    
    }
 
    $msg = "Rettifica effettuata;";
 
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Pagina nuova";

$r->javascripts[]='<script type="text/javascript">                
                        $(function() { 
                                 
                                  $(".tablesorter") 
                                    .tablesorter({ 
                                      theme : \'blue\',  
                                      cssChildRow: "tablesorter-childRow",
                                      widgets: ["zebra", "filter"], 
                                      widgetOptions: { 
                                        filter_childRows  : false, 
                                        filter_cssFilter  : \'tablesorter-filter\', 
                                        filter_startsWith : false, 
                                        filter_ignoreCase : true 
                                      } 
                                 
                                    }); 
                                 
                                  $(".tablesorter-childRow td").hide(); 
                                 

                                  $(".tablesorter").on(\'click\', ".toggle",function(){
                                    $(this).closest("tr").nextUntil("tr:not(.tablesorter-childRow)").find("td").toggle(); 
                                    return false; 
                                  });
                                });
</script>';
//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);  


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto

      $h .= " <div class=\"rg_widget rg_widget_helper\">
                <h3>Rettifica VALORI singoli articoli, per ogni utente.</h3>
                <table id=\"output_1\" class=\"tablesorter\" style=\"font-size:1.2em;\">
                    <thead>     
                        <tr> 
                            <th class=\"sinistra\">Utente</th>
                            <th class=\"sinistra\">Gas</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>Totale Netto</th> 
                        </tr>
                    <thead>
                    <tbody>";


       $col_5 = " class=\"destra\" ";             
                    
       $result = $db->sql_query("SELECT
                                    Sum(retegas_dettaglio_ordini.qta_arr * retegas_articoli.prezzo) as importo_totale,
                                    Sum(retegas_dettaglio_ordini.qta_arr) as somma_articoli,
                                    Count(retegas_dettaglio_ordini.id_articoli) as conto_articoli,
                                    maaking_users.fullname,
                                    maaking_users.userid,
                                    retegas_gas.id_gas,
                                    retegas_gas.descrizione_gas
                                    FROM
                                    retegas_dettaglio_ordini
                                    Inner Join maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
                                    Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
                                    Inner Join retegas_gas ON maaking_users.id_gas = retegas_gas.id_gas
                                    WHERE
                                    retegas_dettaglio_ordini.id_ordine =  '$id_ordine'
                                    GROUP BY
                                    retegas_dettaglio_ordini.id_utenti
                                    ORDER BY Sum(retegas_dettaglio_ordini.qta_arr * retegas_articoli.prezzo) DESC");


       $riga=0;  
         while ($row = $db->sql_fetchrow($result)){
         


              $id_ut = $row["userid"];
              $nome_ut = $row['fullname'];
              $gas_app = $row['descrizione_gas'];
              $id_gas_app = $row['id_gas'];
              $conto_articoli = $row['conto_articoli'];
              $somma_articoli = $row['somma_articoli'];
              $importo_totale = $row["importo_totale"];
              
              
              $totalone = $totalone+ $importo_totale;
              $totalone_articoli = $totalone_articoli + $somma_articoli;
              
              
              $somma_articoli = (float)$somma_articoli;
              
              $costo_trasporto = valore_costo_trasporto_ordine_user($id_ordine,$id_ut);
              $costo_gestione = valore_costo_gestione_ordine_user($id_ordine,$id_ut);
              $costo_mio_gas = valore_costo_mio_gas($id_ordine,$id_ut);
              $costo_maggiorazione = valore_costo_maggiorazione_mio_gas($id_ordine,$id_ut);
              $totale_lordo = $costo_trasporto +
                              $costo_gestione +
                              $costo_mio_gas +
                              $costo_maggiorazione +
                              $importo_totale;    
              $totale_pubblico = $importo_totale +
                                 $costo_gestione +
                                 $costo_trasporto;
                                 
              
              $importo_totale = _nf($importo_totale);
              $totale_lordo = _nf($totale_lordo);
              $totale_pubblico = _nf($totale_pubblico);
              $costo_trasporto = _nf($costo_trasporto);
              $costo_gestione = _nf($costo_gestione);
              $costo_maggiorazione = _nf($costo_maggiorazione);
              $costo_mio_gas = _nf($costo_mio_gas);
              $somma_articoli = round($somma_articoli,2);
              
              $h.= "<tr>";    
                $h.= "<td class=\"sinistra\"><a href=\"#\" class=\"toggle\">$nome_ut</a></td>"; 
                $h.= "<td class=\"sinistra\">$gas_app</td>";
                //$h.= "<td $col_5>$conto_articoli</td>";
                //$h.= "<td $col_5>$somma_articoli</td>";
                $h.= "<td colspan=\"1\">&nbsp</td>";
                $h.= "<td colspan=\"1\">&nbsp</td>";
                $h.= "<td class=\"destra\"><b>$importo_totale</b></td>";
              $h .="</tr>";
              
              
              // RIGA NASCOSTA
              $h .="<tr class=\"tablesorter-childRow\">";
              $h .="<td colspan=\"9\">";
              //ARTICOLI E FORM COME CHILDROWS
              //--------------------------------------------LISTA UTENTE
                unset($res_dett);
                $query = "SELECT * FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine' AND id_utenti='".$row["userid"]."';";
                $res_dett = $db->sql_query($query);
                $h .="<div class=\"rg_widget rg_widget_helper\">";
                $h .="<h4>$nome_ut, $gas_app, rettifica Quantità arrivata</h4>";
                $h .="<form method=\"POST\" action=\"\">";
                $h .="<table id=\"output_".$row["userid"]."\">
                                <thead>     
                                        <tr class=\"destra\"> 
                                            <th class=\"sinistra\">&nbsp;</th>
                                            <th class=\"sinistra\">Codice</th>
                                            <th class=\"sinistra\">Articolo</th>
                                            <th>Q.Ord</th>
                                            <th>Q.Arr.</th>
                                            <th>Prezzo</th>
                                            <th>Tot. Riga</th>
                                            <th>Nuovo Totale</th> 
                                        </tr>
                                    <thead>
                                    <tbody>";
                
                while ($row_d = mysql_fetch_array($res_dett)){
    
                    
                $riga++;

                unset($opz);
                                
                $misura = " (".db_val_q("id_articoli",$row_d["id_articoli"],"u_misura","retegas_articoli")." ".db_val_q("id_articoli",$row_d["id_articoli"],"misura","retegas_articoli").")";
                $prezzo = db_val_q("id_articoli",$row_d["id_articoli"],"prezzo","retegas_articoli");
                unset($alert_qta);
                if($row_d["qta_arr"]==0){
                            $alert_qta = "<br><div class=\"campo_alert\">ANNULLATA</div>";
                        }else if($row_d["qta_arr"]<>$row_d["qta_ord"]){
                            $alert_qta = "<br><div class=\"campo_alert\">MODIFICATA</div>";
                        }
                $qta_scatola = db_val_q("id_articoli",$row_d["id_articoli"],"qta_scatola","retegas_articoli");
                
                //UNICO
                if(db_val_q("id_articoli",$row_d["id_articoli"],"articoli_unico","retegas_articoli")==1){
                    $unico = " (".$row_d["id_articoli"].")";
                }
                
                
                $h .="<tr>";
                $h .="<td class=\"sinistra column_hide\"><span class=\"small_link\">".fullname_from_id($row_d["id_utenti"])."</span>".$alert_qta."</td>";
                $h .="<td class=\"sinistra\">".db_val_q("id_articoli",$row_d["id_articoli"],"codice","retegas_articoli").$unico."</td>";
                $h .="<td class=\"sinistra\">".db_val_q("id_articoli",$row_d["id_articoli"],"descrizione_articoli","retegas_articoli")."<span class=\"small_link\">".$misura."</span></td>";
                $h .="<td class=\"destra\">".($row_d["qta_ord"])."</td>";
                $h .="<td class=\"destra\"><b>".($row_d["qta_arr"])."</b></td>";
                $h .="<td class=\"destra\">$prezzo</td>";
                $h .="<td class=\"destra\"><b>".$prezzo*$row_d["qta_arr"]."</b></td>";
                $h .="<td class=\"destra\"><input type=\"text\" name=\"box_valori[]\" value=\"".($prezzo*$row_d["qta_arr"])."\" size=\"5\">
                                            <input type=\"hidden\" name=\"box_prezzi[]\" value=\"$prezzo\">
                                            <input type=\"hidden\" name=\"box_id_dettaglio[]\" value=\"".($row_d["id_dettaglio_ordini"])."\">".$alert_qta."</td>";
                $h .="<td class=\"destra\"></td>";
                //$h .="<td class=\"destra\">&nbsp;</td>";
                $h .="</tr>";
            }
         $h .="</tbody>";
         $h .="<tfoot>";
            $h .="<tr>";       
                $h .="<td colspan=\"9\" class=\"destra\">";
                $h .="<input type=\"hidden\" name=\"id_utente_target\" value=\"".mimmo_encode($row["userid"])."\">";
                $h .="<input type=\"hidden\" name=\"do\" value=\"do_rettifica\">";
                $h .="<input type=\"hidden\" name=\"id_ordine\" value=\"$id_ordine\">";
                $h .="";
                $h .="<input class=\"awesome green medium\" type=\"submit\" value=\"rettifica\">";
                $h .="</td>";
            $h .="</tr>";
        //--------------------------------------------LISTA UTENTE
        //------------------------------
         //FINE RIGA NASCOSTA
         
         
         //Fine tabella che contiene gli articoli
         $h .="</tfoot>";
         $h .="</table>";
         $h .="</form>";
         $h .='<script type="text/javascript">                
                                $(function() { 
                                         
                                          $("#output_'.$row["userid"].'") 
                                            .tablesorter({widgets: ["zebra"]
                                            }); 
                                        });
                                        </script>';
         $h .="</div>";
         $h .="</td>";
         $h .="</tr>";
         
         
         }//end while
         
         
         
         
         

         $totalone_articoli = number_format($totalone_articoli,2,",","");
         $totalone = number_format($totalone,2,",","");
         
         $h.= "</tbody>
               <tfoot>
                <tr class=\"total destra\">
                    <th>&nbsp;</th>
                    <th class=\"sinistra\">Somme:</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
               </tfoot>
               </table>";



//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);