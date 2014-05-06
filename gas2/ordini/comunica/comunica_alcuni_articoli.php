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


//Per ogni articolo       
foreach ($box_art as $articolo)
    {
        //Per ogni articolo la lista destinatari
        $qry=" SELECT
        maaking_users.fullname,
        maaking_users.email
        FROM
        maaking_users
        Inner Join retegas_dettaglio_ordini ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
        WHERE
        retegas_dettaglio_ordini.id_ordine =  '$id_ordine' AND
        retegas_dettaglio_ordini.id_articoli = '$articolo'
        GROUP BY
        maaking_users.fullname,
        maaking_users.email;
        ";
        $result = $db->sql_query($qry); 
        while ($row = mysql_fetch_array($result)){
        
            //echo "ART : $articolo =". $row[0] ." - ". $row[1]."<br />";
            
            if(!in_array($row[0], $verso_chi)){
                $verso_chi[]=$row[0];
                $lista_destinatari .= $row[0]."<br>";
            }
            if(!in_array($row[1], $mail_verso_chi)){
                $mail_verso_chi[]=$row[1];
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

//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto      -----------------------------------------------------------------------
$query = "SELECT
                    count(retegas_dettaglio_ordini.id_utenti) AS c_user,
                    Sum(retegas_dettaglio_ordini.qta_ord) AS t_q_ord,
                    Sum(retegas_dettaglio_ordini.qta_arr) AS t_q_arr,
                    retegas_articoli.id_articoli,
                    retegas_articoli.id_listini,
                    retegas_articoli.codice,
                    retegas_articoli.u_misura,
                    retegas_articoli.misura,
                    retegas_articoli.descrizione_articoli,
                    retegas_articoli.qta_scatola,
                    retegas_articoli.prezzo,
                    retegas_articoli.ingombro,
                    retegas_articoli.qta_minima,
                    retegas_articoli.qta_multiplo,
                    retegas_articoli.articoli_note
                    FROM
                    retegas_dettaglio_ordini
                    Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
                    Inner Join maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
                    WHERE
                    retegas_dettaglio_ordini.id_ordine =  '$id_ordine' 
                    GROUP BY
                    retegas_articoli.codice
                    ORDER BY
                    retegas_articoli.codice ASC
                    ";
$res = $db->sql_query($query);



$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= $alert;
$h .= "<form action=\"\" method=\"POST\">";
$h .= "<table>";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th class=\"sinistra column_hide\">&nbsp;</th>";
    $h .="<th class=\"sinistra\">Utenti</th>";
    $h .="<th class=\"sinistra\">Codice</th>";
    $h .="<th class=\"sinistra\">Descrizione</th>";
    $h .="<th class=\"centro\">QO/QA</th>";
    $h .="<th class=\"centro\">SC/AV</th>";
    $h .="<th class=\"destra\">Prezzo</th>";
    $h .="<th class=\"destra\">Tot Riga</th>";
    $h .="<th class=\"destra\">Costi</th>";
    $h .="<th class=\"destra\">Totale</th>";
    $h .="</tr>";
$h .= "</thead>";
$h .= "<tbody>";
while ($row = mysql_fetch_array($res)){
    
    $riga++;
    
    if(is_integer($riga / 2)){
        $cl  ="class=\"odd\"";
    }else{
        $cl = "";
    }
    
    
    unset($opz);
    
    $opz="<input type=\"checkbox\" name=\"box_art[]\" value=\"".$row["id_articoli"]."\" ";
    
    $sqla = "SELECT * FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine' AND id_articoli='".$row["id_articoli"]."';";
    $resa = $db->sql_query($sqla);
    unset($ut);
    while ($rowa = mysql_fetch_array($resa)){
        $ut .= fullname_from_id($rowa["id_utenti"]).", ";
    }
    
    
    $misura = " (". $row["u_misura"]." ".$row["misura"].")";
    
    unset($alert_qta);
    unset($qta);
    $qta = $row["t_q_arr"];
    if($qta==0){
                $alert_qta = "<div class=\"campo_alert\">ANNULLATA</div>";
                $qta="";
            }else if($qta<>$row["t_q_ord"]){
                $alert_qta = "<div class=\"campo_alert\">MODIFICATA</div>";
                
            }
    
    if($row["qta_scatola"]>0){
    $scatole = floor($row["t_q_arr"] / $row["qta_scatola"]);
    $avanzo = (($row["t_q_arr"]) % ($row["qta_scatola"]));
    }else{
    $scatole =0;
    $avanzo = $row["t_q_arr"];    
    }
    $avanzo = calcola_avanzo($row["t_q_arr"],$row["qta_scatola"]);
    
    $h .="<tr $cl>";
    $h .="<td class=\"sinistra column_hide\">$opz</td>";
    $h .="<td class=\"sinistra\">".$row["c_user"].", <span class=\"small_link\">$ut</span></td>";
    $h .="<td class=\"sinistra\">".$row["codice"]."</td>";
    $h .="<td class=\"sinistra\">".$row["descrizione_articoli"].$misura."</td>";
    $h .="<td class=\"centro\">".round($row["t_q_ord"],2)." / ".round($qta,2).$alert_qta."</td>";
    $h .="<td class=\"centro\">$scatole / $avanzo</td>";
    $h .="<td class=\"destra\">"._nf($row["prezzo"])."</td>";
    $h .="<td class=\"destra\">"._nf($row["t_q_arr"]*$row["prezzo"])."</td>";
    $h .="<td class=\"destra\">&nbsp;</td>";
    $h .="<td class=\"destra\">&nbsp;</td>";
    $h .="</tr>";
}
$h .="</tbody>";
$h .= "<tfoot>";

$costo_trasporto = valore_trasporto($id_ordine,100);
if($costo_trasporto>0){
    $h .="<tr class=\"costo\">";
    $h .="<td class=\"sinistra column_hide\">&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"sinistra\">Costo Trasporto</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"destra\">"._nf($costo_trasporto)."</td>";
    $h .="<td>&nbsp;</td>";
    $h .="</tr>";
}

$costo_gestione = valore_gestione($id_ordine,100);
if($costo_gestione>0){
    $h .="<tr class=\"costo\">";
    $h .="<td class=\"sinistra column_hide\">&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"sinistra\">Costo gestione</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td>&nbsp;</td>";
    $h .="<td class=\"destra\">"._nf($costo_gestione)."</td>";
    $h .="<td>&nbsp;</td>";
    $h .="</tr>";
}

//TOTALE VERSO GLI ESTERNI
$netto = valore_totale_ordine_qarr($id_ordine);
$costi_esterni = $costo_trasporto + $costo_gestione;
    $h .="<tr class=\"total\">";
    $h .="<th class=\"column_hide\">&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th class=\"sinistra\">Totale pubblico:</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th>&nbsp;</th>";
    $h .="<th class=\"destra\">"._nf($netto)."</th>";
    $h .="<th class=\"destra\">"._nf($costi_esterni)."</th>";
    $h .="<th class=\"destra\">"._nf($netto+$costi_esterni)."</th>";
    $h .="</tr>";

    
$h .= "</tfoot>";



$h .="</table>";
$h .="<p></p>";
$h .="<textarea class =\"ckeditor\" rows=\"5\" name=\"msg_mail\" cols=\"60\"></textarea>";
$h .="<input type=\"hidden\" name=\"do\" value=\"send_mail\">";
$h .="<input type=\"hidden\" name=\"id_ordine\" value=\"$id_ordine\">";
$h .="<br><input type=\"submit\" name=\"submit\" value=\"Invia il messaggio\" class=\"awesome large green\">";
$h .="</form>";
$h .="</div>";
//-------------------------------------------------------------------------------------------





$h = schedina_ordine($id_ordine).$h;

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);   
