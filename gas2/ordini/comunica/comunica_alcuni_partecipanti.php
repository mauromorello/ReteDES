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

if(!(_USER_PERMISSIONS&perm::puo_postare_messaggi)){
    go("ordini_form",_USER_ID,"Il tuo GAS non ti autorizza a mandare mail massive","?id_ordine=$id_ordine");
    exit;
}


//---------------------------------------------------------SEND MAIL
if($do=="send_mail"){

       
        $qry=" SELECT 
        maaking_users.fullname,
        maaking_users.email,
        maaking_users.userid
        FROM
        maaking_users
        Inner Join retegas_dettaglio_ordini ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
        WHERE
        retegas_dettaglio_ordini.id_ordine =  '$id_ordine' 
        GROUP BY
        maaking_users.fullname,
        maaking_users.email,
        maaking_users.userid;
        ";
        $result = $db->sql_query($qry); 
        while ($row = mysql_fetch_array($result)){
        
            //echo "ART : $articolo =". $row[0] ." - ". $row[1]."<br />";
            
            if(in_array($row[2], $box_user)){
                
                if(!in_array($row[0], $verso_chi)){
                    $verso_chi[]=$row[0];
                    $mail_verso_chi[]=$row[1];
                    $lista_destinatari .= $row[0]."<br>";
                }
                
            }
            
        }
        
    
    
    
  

//MAIL
$da_chi = fullname_from_id(_USER_ID);
$mail_da_chi = id_user_mail(_USER_ID);
$descrizione_ordine = descrizione_ordine_from_id_ordine($id_ordine);
$soggetto = "["._SITE_NAME."] - [REFERENTE ORDINE] $da_chi per ordine $id_ordine ($descrizione_ordine)";
manda_mail_multipla_istantanea($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($msg_mail),"MAN",$id_ordine,_USER_ID,$msg_mail);
            
$msg="Mail correttamente inviata a : <br>$lista_destinatari";
//echo $msg;    
go("ordini_form",_USER_ID,$msg,"?id_ordine=$id_ordine");
exit;    
}
//---------------------------------------------------------SEND MAIL


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Mail a utenti selezionati in base agli articoli acquistati";

$r->javascripts_header[]=java_head_ckeditor();
$r->javascripts[]=java_tablesorter("output_1");
$r->javascripts[]='<script>
                        $("#output_1 input[type=checkbox]").live("change", function() {
                            $(this).closest("tr").toggleClass("selected");
                        }).filter(":checked").each(function() {
                            $(this).closest("tr").addClass("selected");
                        });
                    </script>';
//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto      -----------------------------------------------------------------------
$h .= " <div class=\"rg_widget rg_widget_helper\">
                <h3>Lista partecipanti</h3>
                <p>Seleziona cliccando sul box a sinistra solo gli utenti ai quali vuoi inviare la mail.</p>
                <form action=\"\" method=\"POST\">
                <table id=\"output_1\">
                    <thead>     
                        <tr class=\"destra\"> 
                            <th class=\"sinistra column_hide\">&nbsp;</th>
                            <th class=\"sinistra\">Utente</th>
                            <th class=\"sinistra\">Gas</th>
                            <th>Netto</th>
                            <th>Trasporto</th>
                            <th>Gestione</th>
                            <th>Lordo Pubblico</th>
                            <th>Costo GAS</th>
                            <th>% GAS</th>
                            <th>Lordo Privato</th> 
                        </tr>
                    <thead>
                    <tbody>";


       $col_5 = " class=\"destra\" ";             
                    
       $result = $db->sql_query("SELECT
                                    Sum(retegas_dettaglio_ordini.qta_arr * retegas_dettaglio_ordini.prz_dett_arr) as importo_totale,
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
                                    ORDER BY Sum(retegas_dettaglio_ordini.qta_arr * retegas_dettaglio_ordini.prz_dett_arr) DESC");


       $riga=0;  
         while ($row = $db->sql_fetchrow($result)){
         
              $opz="<input type=\"checkbox\" name=\"box_user[]\" value=\"".$row["userid"]."\" ";

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
              $h.= "<td class=\"sinistra column_hide\">$opz</td>";    
                $h.= "<td class=\"sinistra\">$nome_ut</td>"; 
                $h.= "<td class=\"sinistra\">$gas_app</td>";
                //$h.= "<td $col_5>$conto_articoli</td>";
                //$h.= "<td $col_5>$somma_articoli</td>";
                $h.= "<td $col_5>$importo_totale</td>";
                $h.= "<td $col_5>$costo_trasporto</td>";
                $h.= "<td $col_5>$costo_gestione</td>";
                $h.= "<td $col_5><b>$totale_pubblico</b></td>";
                $h.= "<td $col_5>$costo_mio_gas</td>";
                $h.= "<td $col_5>$costo_maggiorazione</td>";
                $h.= "<td $col_5><b>$totale_lordo</b></td>";
              $h .="</tr>";

         }//end while


         $totalone_articoli = number_format($totalone_articoli,2,",","");
         $totalone = number_format($totalone,2,",","");
         
         $netto_totale = (valore_totale_ordine_qarr($id_ordine));
         $trasporto_totale = (valore_trasporto($id_ordine,100));
         $gestione_totale = (valore_gestione($id_ordine,100));
         $pubblico_totale = _nf($netto_totale + $trasporto_totale+ $pubblico_totale);
         
         $netto_totale = _nf($netto_totale);
         $trasporto_totale = _nf($trasporto_totale);
         $gestione_totale = _nf($gestione_totale);
         
         $h.= "</tbody>
               <tfoot>
                <tr class=\"total destra\">
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th class=\"sinistra\">Somme:</th>
                    <th class=\"destra soldi\">$netto_totale</th>
                    <th class=\"destra soldi\">$trasporto_totale</th>
                    <th class=\"destra soldi\">$gestione_totale</th>
                    <th class=\"destra soldi\">$pubblico_totale</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
               </tfoot>
               </table>";
               $h .="<p></p>";
                $h .="<textarea class =\"ckeditor\" rows=\"5\" name=\"msg_mail\" cols=\"60\"></textarea>";
                $h .="<input type=\"hidden\" name=\"do\" value=\"send_mail\">";
                $h .="<input type=\"hidden\" name=\"id_ordine\" value=\"$id_ordine\">";
                $h .="<br><input type=\"submit\" name=\"submit\" value=\"Invia il messaggio\" class=\"awesome large green\">";
               $h .="</div>";
//-------------------------------------------------------------------------------------------





$h = schedina_ordine($id_ordine).$h;

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);   
