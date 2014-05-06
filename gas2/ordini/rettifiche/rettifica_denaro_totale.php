<?php

function ridistribuisci_quantita_amici_denaro_user($key,$nq,&$msg){
    //echo "---- Ridistribuisco $key con $nq <br>";

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
    retegas_distribuzione_spesa.id_amico DESC";


// Adesso la popolo con la nuova quantit? partendo dall'ultima riga immessa;
// in realt? cancellando e ripopolando tutto ho sempre lo stesso utente penalizzato;

    $result = $db->sql_query($qry);
    $totalrows = mysql_num_rows($result);
    $rimasto = $nq;
    $i = 0;
    while ($row = mysql_fetch_array($result)){

        $i++;
        //Echo "------------->Ciclo n.$i<br>";

        $a = $rimasto - $row['qta_ord'];
        $id_q = $row['id_distribuzione'];

        if($a>0){
            //Echo "------------->Rimasto - Qord > 0 <br>";
            $q_a = $row['qta_ord'];
            $rimasto=$a;

            // se ? l'ultima riga allora aggiungo un po' di roba
            if($i==$totalrows){

                 $q_a = $rimasto + $row['qta_ord'];
                 $rimasto=0;
                 //Echo "------------->Ultima riga; qa= (rimasto + qord) $q_a <br>";
            }

        }else{

            //Echo "------------->URimasto - Qord = 0 <br>";
            $q_a = $rimasto;
            $rimasto=0;
        }


    //Echo "------------->INSERISCO $q_a in $id_q<br>";
    // update
    $result2 = mysql_query("UPDATE retegas_distribuzione_spesa
                            SET retegas_distribuzione_spesa.qta_arr = '$q_a',
                                retegas_distribuzione_spesa.data_ins = NOW()
                            WHERE (retegas_distribuzione_spesa.id_distribuzione='$id_q');");



    // CICLO DI UPDATE
    }



}

// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
//
include_once ("../ordini_renderer.php");

if(isset($id)){
    $id_ordine = $id;
}
(int)$id_ordine;

// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

    // estraggo dal cookie le informazioni su chi ? che sta vedendo la pagina
    $cookie_read     =explode("|", base64_decode($user));
    $id_user  =  $cookie_read[0];
    $usr =       $cookie_read[1];

    // e poi scopro di che gas ? l'user
    $id_gas = id_gas_user($id_user);

}else{
    pussa_via();
    exit;
}

   //CONTROLLO SE L'ORDINE E' DI USER
   //Se posso vedere tutti gli ordini
   //Se non sono almeno referente GAS allora non posso vedere nulla.
    $mio_Stato = ordine_io_cosa_sono($id_ordine,_USER_ID);
    if(!(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini)){

        if ($mio_Stato<3){
            go("sommario",_USER_ID,"Questo ordine non mi compete");
        }

        //Se sono referente gas controllo di vedere il MIO gas
        if ($mio_Stato==3){
            if($id_gas<>_USER_ID_GAS){
                go("sommario",_USER_ID,"Solo il referente ordine può vedere tutti i gas.");
            }
        }
    }


   //IF DO = RETT
   if($do=="do_rett_tot"){

      if(is_empty($nuovo_totale)){
              c1_go_away("?q=no_permission");
              exit;
      }

      $nuovo_totale = floatval(trim(str_replace(array(",","€"),array(".",""),$nuovo_totale)));

      if(!valuta_valida($nuovo_totale)){
              c1_go_away("?q=no_permission");
              exit;

      }

      $vo = CAST_TO_FLOAT(valore_totale_ordine($id_ordine));
      $vn = $nuovo_totale;

      $rapporto = $vn / $vo;

      //PASSO TUTTI GLI UTENTI CHE HANNO PARTECIPATO ALL'ORDINE



      //passo il dettaglio ordini dell'utente e cambio le quantit? arrivate in base al rapporto
      $sql = "SELECT * FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine';";
      $res = $db->sql_query($sql);

      while ($row = mysql_fetch_array($res)){

            $n_quantita_arrivata = $row["qta_ord"] * $rapporto;

            //echo "-------> Id dett =".$row["id_dettaglio_ordini"]." Q_ord =".$row["qta_ord"]."  Q.Arr old =".$row["qta_arr"]."  Nuova Q_arr  = ".($row["qta_ord"] * $rapporto)."<br>";

            //modifico dettaglio spesa
            $modifica_dettaglio = $db->sql_query("UPDATE retegas_dettaglio_ordini
                                        SET retegas_dettaglio_ordini.qta_arr = '$n_quantita_arrivata',
                                        retegas_dettaglio_ordini.data_convalida = NOW()
                                        WHERE (retegas_dettaglio_ordini.id_dettaglio_ordini='".$row["id_dettaglio_ordini"]."')
                                        LIMIT 1;");
            //modifico distribuzione spesa
             ridistribuisci_quantita_amici_denaro_user($row["id_dettaglio_ordini"],$n_quantita_arrivata,&$msg);


            //AGGIORNO IL TOTALE NETTO DI CASSA PER OGNI USER
            cassa_update_ordine_utente($id_ordine,$row["id_utenti"]);


     }
     go("ordini_form",$id_user,"Importi inseriti correttamente","?id=$id_ordine");



   }


   // ISTANZIO un nuovo oggetto "retegas"

    $retegas = new sito;

    $retegas->posizione = "Rettifica totale ordine";
    //$retegas->help_page = "https://sites.google.com/site/retegasapwiki/come-fare-per/rettificare-i-quantitativi";


    // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard

    $retegas->sezioni = $retegas->html_standard;

    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento

    // Menu specifico per l'output

    $retegas->menu_sito = array_merge(ordini_menu_all($id_ordine),$retegas->menu_sito);

    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
    //$retegas->css[]  = "datetimepicker";

    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");  // editor di testo

      // creo  gli scripts per la gestione dei menu

      $ref_table = "rettifiche";

      $retegas->java_scripts_header[] = java_accordion(null,1); // laterale
      $retegas->java_scripts_header[] = java_superfish();
      $retegas->java_scripts_header[]=  java_tablesorter($ref_table);
      // assegno l'eventuale messaggio da proporre


      if(isset($msg)){
        $retegas->messaggio=$msg;
      }else{
        $retegas->messaggio="Funzione non ancora testata a fondo.<br> Usare a proprio rischio e pericolo";
      }



            // qui ci va la pagina vera e proria
      $retegas->content  =  schedina_ordine($id_ordine).
                            ordine_render_rettifica_denaro_totale($ref_table,$id_ordine);

      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);



?>