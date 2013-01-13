<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


if(isset($id_utente)){
    (int)$id_utente = mimmo_decode($id_utente);
    if(!(_USER_PERMISSIONS & perm::puo_gestire_la_cassa)){
        unset($do);
        $msg = "Non hai i permessi necessari.";
        go("sommario",_USER_ID,$msg);
    }

}else{
    $id_utente=_USER_ID;
        //COntrollo permessi
    if(!(_USER_USA_CASSA)){
        unset($do);
        $msg = "Non hai abilitato la cassa.";
        go("sommario",_USER_ID,$msg);
    }


}

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){

    pussa_via();
    exit;     
} 



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::gas;
//Assegno il titolo che compare nella barra delle info
$r->title = "Movimenti User Raggruppati";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale[] = gas_menu_gestisci_cassa();

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
    $result = $db->sql_query("SELECT    id_ordine, 
                                        COUNT(`id_cassa_utenti`) as conto_mov,
                                        segno,
                                        SUM(`importo`) as somma_mov 
                                        FROM `retegas_cassa_utenti` 
                                        WHERE id_utente = '$id_utente' 
                                        GROUP BY `id_ordine`
                                        ORDER BY id_ordine DESC; ");
    $totalrows = $db->sql_numrows($result);     
    $fullname = fullname_from_id($id_utente);


    $h .= " <div class=\"rg_widget rg_widget_helper\">
            <h3>Utente $fullname, situazione movimenti individuali raggruppati per ordine al ".date("d/m/Y H:i")."</h3>
            <table id=\"output_1\">
         <thead>
         <tr>
        <th>&nbsp;</th>          
        <th>N.</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>Credito</th>
        <th>Debito</th>
        <th>Descrizione</th>
        <th>Ordine</th>
        <th>Cassiere</th>
        <th>REG</th>
        <th>CON</th> 
        </tr>
        </thead>
        <tbody>";

       $riga=0;  

         while ($row = mysql_fetch_array($result)){
         $riga++;
         $ordine_op = $row["id_ordine"];
         if($ordine_op==0){
             $ordine_op="";
             $descrizione_op="";
         }else{
          $descrizione_op = descrizione_ordine_from_id_ordine($row["id_ordine"]).", di ".fullname_from_id(id_referente_ordine_globale($row["id_ordine"]));            
         }
         
         if($row["segno"]=="+"){
             $somma_credito = $somma_credito + $row["somma_mov"];
             $credito_op = _nf($row["somma_mov"]);
             $debito_op = "&nbsp";
         }else{
             $somma_debito = $somma_debito + $row["somma_mov"];
             $debito_op = _nf($row["somma_mov"]);
             $credito_op = "&nbsp";
         }
         
            $opz = "<a class=\"awesome silver small\" href=\"".$RG_addr["cassa_movimenti_ord_ut"]."?id_ordine=".$row["id_ordine"]."\">M</a>";

         if(!is_printable_from_id_ord($row["id_ordine"])){
             //$REG = "SI<br><span class=\"small_link\">".conv_datetime_from_db($row["data_registrato"])."</span>";
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_grigio"].'"  style="height:16px; width:16px;vertical_align:middle;border=0;">';
         }else{
             //$REG = "NO";
             $pal = '<IMG SRC="'.$RG_addr["img_pallino_verde"].'"  style="height:16px; width:16px;vertical_align:middle;border=0;">';
         }
         
         if($row["id_ordine"]==0){
            $pal = '<IMG SRC="'.$RG_addr["img_pallino_verde"].'"  style="height:16px; width:16px;vertical_align:middle;border=0;">';

         }
         
         
            $h.= "
            <tr>
            <td $col_1>$pal</td>
            <td $col_1>".$row["conto_mov"]."</td> 
            <td $col_1>$opz</td>
            <td $col_1>$tipo_op</td>
            <td class=\"destra\">$credito_op</td>
            <td class=\"destra\">$debito_op</td>
            <td $col_1>$descrizione_op</td>
            <td $col_1>$ordine_op</td>
            <td $col_1>$cassiere_op</td>
            <td $col_1>$REG</td>
            <td $col_1>$CON</td>   
            </tr>";

         }//end while


         $h.= "
         </tbody>
         <tfoot>
             <tr class=\"total\">
                <th class=\"sinistra\">Totale</th>          
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th class=\"destra\">"._nf($somma_credito)."</th>
                <th class=\"destra\">"._nf($somma_debito)."</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
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
unset($r)   
?>