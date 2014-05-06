<?php

function ridistribuisci_quantita_amici($key,$nq, &$msg){
global $db, $user,$a_hdr,$a_std,$a_alt;
// Ho la lista degli amici riferita all'articolo KEY
$qry ="SELECT
retegas_distribuzione_spesa.id_distribuzione,
retegas_distribuzione_spesa.id_riga_dettaglio_ordine,
retegas_distribuzione_spesa.qta_ord,
retegas_distribuzione_spesa.qta_arr,
retegas_distribuzione_spesa.id_amico
FROM
retegas_distribuzione_spesa
WHERE
retegas_distribuzione_spesa.id_riga_dettaglio_ordine =  '$key'
ORDER BY
retegas_distribuzione_spesa.id_distribuzione ASC";


// Adesso la popolo con la nuova quantit? partendo dall'ultima riga immessa;
// in realt? cancellando e ripopolando tutto ho sempre lo stesso utente penalizzato;

$result = $db->sql_query($qry);
    $totalrows = $db->sql_numrows($result);
    $rimasto=$nq;
    while ($row = mysql_fetch_array($result)){

        $a = $rimasto - $row['qta_ord'];
        $id_q = $row['id_distribuzione'];

        if($a>0){
            $q_a = $row['qta_ord'];
            $rimasto=$a;
        }else{
            $q_a = $rimasto;
            $rimasto=0;
        }

    // update
    $result2 = mysql_query("UPDATE retegas_distribuzione_spesa
                            SET retegas_distribuzione_spesa.qta_arr = '$q_a',
                                retegas_distribuzione_spesa.data_ins = NOW()
                            WHERE (retegas_distribuzione_spesa.id_distribuzione='$id_q');");

     if($row['qta_ord']<>$q_a){
        // le quantita' sono diverse, ricalcolo le assegnazioni sugli amici
        $msg .= "";
    $amico = $row["id_amico"];
        //echo r_t_l2("-------------$key AMICO $amico ID DETT:".$row['id_distribuzione']." ORD. = ".$row['qta_ord']." ARR. = ".$q_a,$a_alt);
    }else{
        //echo r_t_l2("-------------$key AMICO $amico ID DETT:".$row['id_distribuzione']." ORD. = ".$row['qta_ord']." ARR. = ".$q_a,$a_std);
    }

        //echo r_t_l2("OPERAZIONE CONCLUSA: TORNA INDIETRO",$a_hdr,"ordini_chiusi_dettaglio_codice.php?do=vis1&id_ord=$id_ord");


    // CICLO DI UPDATE
    }



}
function do_mod1($id_user,$id_ord,$box_q_ord,$box_q_arr,$box_art_id){

   global $user, $db,$a_hdr,$a_std;
    //echo "ORD = ".$id_ord." User:".$id_user."<br>";
    $r=0;
    $msg="";
    //echo r_t_l2("RISULTATO OPERAZIONE (provvisorio)",$a_hdr);


    while (list ($key,$val) = @each ($box_art_id)) { // PASSO LA LISTA DEGLI ARTICOLI
    //echo r_t_l2("KEY ".$key." ID :".$val." Q_ord: ".$box_q_ord[$key]." Q_Arr :".$box_q_arr[$key],$a_std);
    // PER OGNI ARTICOLO CREO UN RECORDSET CON GLI ORDINI FATTI SULL'ARTICOLO
    // DA CUI TRAGGO l'ID DETTAGLIO_ORDINE
    // POI FACCIO UN CICLO A RITROSO PARTENDO DAL TOTALE ARTICOLO ARRIVATO
    // E SCALANDO LE QUANTITA' PARTENDO DALL?ORDINE PIU' VECCHIO
    // GLI ORDINI PIU' RECENTI SE NON CI SONO ABBASTANZA ARTICOLI VENGONO PENALIZZATI
    $qry="SELECT
                retegas_dettaglio_ordini.id_dettaglio_ordini,
                retegas_dettaglio_ordini.data_inserimento,
                retegas_dettaglio_ordini.qta_ord
           FROM
                retegas_dettaglio_ordini
           WHERE
                retegas_dettaglio_ordini.id_ordine =  '$id_ord' AND
                retegas_dettaglio_ordini.id_articoli =  '$val'
           ORDER BY
                retegas_dettaglio_ordini.data_inserimento ASC";
    $result = $db->sql_query($qry);
    $totalrows = mysql_num_rows($result);
    $rimasto=$box_q_arr[$key];
    while ($row = mysql_fetch_array($result)){

        $a = $rimasto - $row['qta_ord'];
        $id_q = $row['id_dettaglio_ordini'];

        if($a>0){
            $q_a = $row['qta_ord'];
            $rimasto=$a;
        }else{
            $q_a = $rimasto;
            $rimasto=0;
        }

    // update
    $result2 = mysql_query("UPDATE retegas_dettaglio_ordini
                            SET retegas_dettaglio_ordini.qta_arr = '$q_a',
                                retegas_dettaglio_ordini.data_convalida = NOW()
                            WHERE (retegas_dettaglio_ordini.id_dettaglio_ordini='$id_q');");
    // CICLO DI UPDATE




    if($row['qta_ord']<>$q_a){
        // le quantita' sono diverse, ricalcolo le assegnazioni sugli amici

        //echo r_t_l2("ARTICOLO:".$row['id_dettaglio_ordini']." ORDINATI :".$row['qta_ord']." ARRIVATI: = ".$q_a,$a_hdr);



    }else{

        //OK
        //echo r_t_l2("MANCANZA ARTICOLI:".$row['id_dettaglio_ordini']." q_arr = ".$q_a,$a_std);
        //echo r_t_l2("ARTICOLO:".$row['id_dettaglio_ordini']." ORDINATI :".$row['qta_ord']." ARRIVATI: = ".$q_a,$a_std);
    }

    // RIPRISTINA ANCHE QUANTITA' INTERE
    ridistribuisci_quantita_amici($row['id_dettaglio_ordini'],$q_a,$msg);



    }// CICLO PER LE ASSEGNAZIONI



    }

    //echo r_t_l2("OPERAZIONE CONCLUSA: TORNA INDIETRO",$a_hdr,"ordini_chiusi_dettaglio_codice.php?do=vis1&id_ord=$id_ord");

    return "Modifiche effettuate";

}     // Modifica quantit? arrivata
function modifica_quantita_arrivate_form($ref_table,$ordine){
global $db,$v1,$v2,$v3,$v4,$v5;
      global $a_hdr,$a_std,$a_tot,$a_nto,$a_cnt;

      $valore = id_listino_from_id_ordine($ordine);

        $qry="SELECT
                retegas_dettaglio_ordini.id_articoli,
                Sum(retegas_dettaglio_ordini.qta_ord) AS tot_q_ord,
                retegas_articoli.codice,
                retegas_articoli.descrizione_articoli,
                Sum(retegas_dettaglio_ordini.qta_arr) AS tot_q_arr,
                retegas_articoli.u_misura,
                retegas_articoli.misura,
                retegas_articoli.articoli_unico,
                retegas_articoli.qta_minima,
                retegas_articoli.qta_scatola
                FROM
                retegas_dettaglio_ordini
                Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
                WHERE
                retegas_dettaglio_ordini.id_ordine =  '$ordine'
                GROUP BY
                retegas_dettaglio_ordini.id_articoli,
                retegas_articoli.codice,
                retegas_articoli.descrizione_articoli
                ORDER BY
                retegas_articoli.codice ASC";
        $result = $db->sql_query($qry);
        $totalrows = mysql_num_rows($result);



        //----------------nuovo form


        $output_html .="";
        $output_html .= "   <div class=\"rg_widget rg_widget_helper\">
                            <h3>Conferma articoli ordinati</h3>
                            <form method=\"POST\" action=\"rettifica_quantita_totali.php\" class=\"retegas_form\">
                            <table id=\"$ref_table\">
                            <thead>
                                <tr>
                                    <th>Univoco</th>
                                    <th>&nbsp</th>
                                    <th>Codice</th>
                                    <th>Descrizione</th>
                                    <th>Quantità ordinata</th>
                                    <th>Composizione<br>Scat / minimo</th>
                                    <th>Prenotate<br>Scat / Avanzo</th>
                                    <th>QUANTITA'<br>ARRIVATA</th>
                                    <th>Q giusta</th>
                                </tr>
                            </thead>
                            <tbody>";

        //$totale_ordine = valore_totale_ordine_qarr($ordine);
        $riga=0;
        $somma_amico = 0;

         while ($row = mysql_fetch_array($result)){



              $c0 = $row[0];
              $c1 = $row[1]; // ordinata
              $c2 = $row[2]; // codice
              $c3 = $row[3]; // Descrizione
              $c4 = $row[4]; // arrivata

              if($row["articoli_unico"]==1){
                  $univoco_class = "class =\"campo_alert\" ";
                  $univoco = "ARTICOLO UNIVOCO";
                  $style_univoco ="";
              }else{
                  $univoco_class ="";
                  $univoco = "";
                  $style_univoco ="";

              }

              unset($scatole_avanzo);
              unset($scatole_intere);
              unset($avanzo_articolo);

              $composizione_scatola = "(".round($row['qta_scatola'],2)." / ".round($row['qta_minima'],2).")";

              $scatole_intere = round(q_scatole_intere_articolo_ordine($ordine,$c0),2);
              $avanzo_articolo = round(q_articoli_avanzo_articolo_ordine($ordine,$c0),2);
              $scatole_avanzo = "( $scatole_intere / $avanzo_articolo )";

              if($avanzo_articolo > 0){
                $div_avanzo = '<div class="campo_alert">'.$scatole_avanzo.'</div>';
              }else{
                $div_avanzo = '<div class="campo_ok">'.$scatole_avanzo.'</div>';
              }
              $q_giusta = $row['qta_scatola'] * $scatole_intere;


              $misu = "(". $row['u_misura'] ." ". $row['misura'].")"; // misura
              $c3.= "<I> $misu</I>";

              $tag_articoli ="";
              $tag_field="<input type=\"text\" name=box_q_arr[] value=$c4 size=\"4\"><input type=\"hidden\" name=box_art_id[] value=$c0><input type=\"hidden\" name=box_q_ord[] value=$c1>";


            $output_html .="<tr>
                                <td $col_1 $univoco_class width=\"10%\">$univoco</td>
                                <td>&nbsp</td>
                                <td>$c2</td>
                                <td>$c3</td>
                                <td>$c1</td>
                                <td>$composizione_scatola </td>
                                <td>$div_avanzo</td>
                                <td>$tag_field</td>
                                <td><span class=\"small_link\">($q_giusta)</span></td>
                            </tr>
                             ";



          $riga++;
         }//end while


        $output_html .="<input type=\"hidden\" name=\"id_ordine\" value=\"$ordine\">
               <input type=\"hidden\" name=\"do\" value=\"do_mod\">
               </tbody>
               </table>

               <input class=\"destra\" type=\"submit\" value=\"Salva le nuove quantita'\">
               </form>
               </div>
            ";




  return $output_html;

}


// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");

include_once ("../ordini_renderer.php");


(int)$id_ordine;

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    pussa_via();
    exit;
}

if(ordine_inesistente($id_ordine)){
    pussa_via();
    exit;
}
//CONTROLLO SE L'ORDINE E' DI USER
//Se non sono almeno referente GAS allora non posso vedere nulla.
$mio_Stato = ordine_io_cosa_sono($id_ordine,_USER_ID);

//Se posso vedere tutti gli ordini
if(!(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini)){

    if ($mio_Stato<3){
        go("sommario",_USER_ID,"Questo ordine non mi compete");
    }

}


   //IF DO = do modifica
     if($do=="do_mod"){
         $msg .= do_mod1(_USER_ID,$id_ordine,$box_q_ord,$box_q_arr,$box_art_id);
     }


   // ISTANZIO un nuovo oggetto "retegas"

    $retegas = new sito;

    $retegas->posizione = "Rettifica totali articoli";
    //$retegas->help_page = "https://sites.google.com/site/retegasapwiki/come-fare-per/rettificare-i-quantitativi";


    // Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
    // Queste sono contenute in un array che ho chiamato HTML standard

    $retegas->sezioni = $retegas->html_standard;

    // Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento

    // Menu specifico per l'output

    $retegas->menu_sito = ordini_menu_all($id_ordine);

    // dico a retegas quali sono i fogli di stile che dovrà usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
    //$retegas->css[]  = "datetimepicker";

    // dico a retegas quali file esterni dovrà caricare
    $retegas->java_headers = array("rg");  // editor di testo

      // creo  gli scripts per la gestione dei menu

      $ref_table = "rettifiche";

      $retegas->java_scripts_header[] = java_accordion(null,1); // laterale
      $retegas->java_scripts_header[] = java_superfish();
      $retegas->java_scripts_header[]=  java_tablesorter($ref_table);
      // assegno l'eventuale messaggio da proporre



            // qui ci va la pagina vera e proria
      $retegas->content  =  schedina_ordine($id_ordine).
                            modifica_quantita_arrivate_form($ref_table,$id_ordine);

      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);



?>