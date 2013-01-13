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
   if($id_user<>id_referente_ordine_globale($id_ordine)){
        c1_go_away("?q=no_permission");  
        exit;    
   }   
   
   
   //IF DO = RETT
   if($do=="do_rett"){
        //print_r($box_userid);
        //print_r($box_value_qord);
        //print_r($box_value_qarr);
      while (list ($key,$utente) = @each ($box_userid)) { // PASSO LA LISTA DEGLI utenti    
      
      //sostituisco la virgola con il punto e tolgo il simbolo euro, ed eventuali spazi
      $box_value_qarr[$key]=floatval(trim(str_replace(array(",","?"),array(".",""),$box_value_qarr[$key])));    
      $box_value_qord[$key]=floatval(trim(str_replace(array(",","?"),array(".",""),$box_value_qord[$key])));
      
      //calcolo il rapporto per sapere la variazione delle singole quantit?
      $rapporto = ($box_value_qarr[$key] / $box_value_qord[$key]);
      
      //passo il dettaglio ordini dell'utente e cambio le quantit? arrivate in base al rapporto
      $sql = "SELECT * FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine' AND id_utenti = '$utente';";
      $res = $db->sql_query($sql);

      //SE ? presente il codice utente tra quelli da operare, allora vedo la lista delle ordinazioni
      if(in_array($utente,$box_operate)){
          //echo "-----------------------> DA OPERARE user $utente<br>";  
          //echo "KEY =".$key." Utente =".$utente." Prezzo con Q.ORD =".$box_value_qord[$key]." Prezzo con Q.ARR =".$box_value_qarr[$key].", rapporto = $rapporto<br>";
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
     
          }
      
      
      }
      

      
      
      
      
      
      
      //UTENTE = $utente
      //Q_ordinata = $box_value_qord[$key]
      //Q_arrivata = $box_value_qarr[$key]
      //Operate  = $box_operate[$key]
      
      } 
       
   }
   
 
   // ISTANZIO un nuovo oggetto "retegas"

    $retegas = new sito;

    $retegas->posizione = "Rettifica totali di ogni utente";
    $retegas->help_page = "https://sites.google.com/site/retegasapwiki/come-fare-per/rettificare-i-quantitativi";     


    // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    
    // Menu specifico per l'output  

    $retegas->menu_sito = ordini_menu_all($id_ordine);
    
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
        $retegas->messaggio="Selezionare solo gli utenti con importo da rettificare; Questa funzione è ancora in stato di test, utilizzarla responsabilmente.";  
      }
      
      
      
            // qui ci va la pagina vera e proria  
      $retegas->content  =  schedina_ordine($id_ordine).
                            ordine_render_rettifica_denaro_user($ref_table,$id_ordine);
        
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);      
      
      
    
?>