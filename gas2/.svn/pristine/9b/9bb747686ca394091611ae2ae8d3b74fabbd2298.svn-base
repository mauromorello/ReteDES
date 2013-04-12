<?php
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
//
include_once ("../ordini_renderer.php");

(int)$id_ordine;

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    pussa_via();
    exit;     
}    
   
   
   //CONTROLLI SE POSSO
   if(!isset($id_ordine)){
       pussa_via();
       exit;    
   }
   
   if((ordine_inesistente($id_ordine))){
       pussa_via();
       exit;     
   }
   if(_USER_ID <> id_referente_ordine_proprio_gas($id_ordine,_USER_ID_GAS)){
       if(!(_USER_PERMISSIONS & perm::puo_gestire_la_cassa)){
           pussa_via();
           exit;   
       }      
   }
   if(!(_USER_PERMISSIONS & perm::puo_operare_con_crediti)){
       pussa_via();
       exit;   
   }

   // VERIFICARE :::::
   // OPERAZIONE NON DOPPIA (tempo trascorso oppure quote inserite)
   // VALORI DIVERSI DA ZERO            (FATTO)
   // VALORI VALIDI PER OGNI OPERAZIONE
   
   if($do=="do_del_anticipo"){
        
        $sql_e = "DELETE FROM retegas_cassa_utenti WHERE id_ordine='$id_ordine' AND tipo_movimento=11 AND id_gas='"._USER_ID_GAS."';";
        $res = $db->sql_query($sql_e);
        $msg= "Eliminati movimenti di anticipo copertura."; 
        log_me($id_ordine,_USER_ID,"CAS","DEL","Eliminazione manuale copertura",0,$sql_e);                     
   }    
   
   if($do=="save"){
   

       //SE E' UN ARRAY IL BOX ID UTENTI
       if(isset($box_id_ut)){
            $err_db = 0;
            $ok = 0;
            $err_valuta =0 ;
            $ignorati = 0;
            
            //PERCORRI LA LISTA DEI REGISTRABILI
            while (list ($key,$val) = @each ($box_reg)) {

            $log.= "KEY : $key VAL : $val<br>";

            //SE E' CONTEGGIABILE
            if((true)){
                //SE E' VALUTA VALIDA
                if(true){
                    //INSERISCI DATABASE
                         
                        // if($box_gestione[$key]<>0){     //SE GESTIONE E' MAGGIORE DI ZERO
                         
                             $idg = id_gas_user($box_id_ut[$key]);
                             $idd = ditta_id_from_listino(listino_ordine_from_id_ordine($id_ordine));
                             $nur = fullname_from_id(id_referente_ordine_globale($id_ordine));
                             
                             //ELIMINO TUTTI GLI ANTICIPI PER COPERTURA
                               $sql_e = "DELETE FROM retegas_cassa_utenti WHERE id_ordine='$id_ordine' AND tipo_movimento=11 AND id_utente='".$box_id_ut[$key]."' LIMIT 1;";
                               $res = $db->sql_query($sql_e);
                               $log.= "Eliminati movimenti di anticipo copertura utente ".$box_id_ut[$key]."<br>"; 
                             
                             if($box_netto[$key]>0){
                                 
                                 $log .= "box_netto($key) = "._nf($box_netto[$key])." <br>";
                                 
                                 //SE ESISTONO GIA' MOVIMENTI COME "PAGAMENTO AL FORNITORE"
                                 $movimenti_netto = db_nr_q_3("id_utente",$box_id_ut[$key],"id_ordine",$id_ordine,"tipo_movimento",7,"retegas_cassa_utenti");
                                 if($movimenti_netto==0){
                                     $log .= "Nessun movimento antecedente, a ".$box_id_ut[$key]." imputati ".$box_netto[$key]." come movimento 7 pagamento al fornitore, segno "-".<br>";
                                     //SCARICA DAL CREDITO UTENTE  COME "PAGAMENTO AL FORNITORE"
                                     if (db_insert_cassa_utenti($box_id_ut[$key],
                                                                $id_ordine,
                                                                _USER_ID,
                                                                $idg,
                                                                $idd,
                                                                $box_netto[$key],
                                                                "-",
                                                                7,
                                                                1,
                                                                "Pagamento al fornitore",
                                                                "",
                                                                "",
                                                                "no",
                                                                "no")){
                                        $ok++;
                                     }else{
                                        $err_db++;                               
                                     }
                                 
                                 }
                             }
                             
                             if($box_trasporto[$key]>0){
                                 
                                 $log .= "box_trasporto($key) = "._nf($box_trasporto[$key])."<br>";
                                 //SE ESISTONO GIA'MOVIMENTI COME MOVIMENTO TRASPORTO
                                 $movimenti_trasporto = db_nr_q_3("id_utente",$box_id_ut[$key],"id_ordine",$id_ordine,"tipo_movimento",8,"retegas_cassa_utenti");
                                 if($movimenti_trasporto==0){
                                 
                                         $log .= "Nessun movimento antecedente, a ".$box_id_ut[$key]." imputati ".$box_trasporto[$key]." come movimento 8 pagamento per trasporto, segno "-".<br>";
                                         //SCARICA DAL CREDITO UTENTE  COME "PAGAMENTO AL FORNITORE"
                                         if (db_insert_cassa_utenti($box_id_ut[$key],
                                                                    $id_ordine,
                                                                    _USER_ID,
                                                                    $idg,
                                                                    $idd,
                                                                    $box_trasporto[$key],
                                                                    "-",
                                                                    8,
                                                                    1,
                                                                    "Pagamento al fornitore",
                                                                    "",
                                                                    "",
                                                                    "no",
                                                                    "no")){
                                            $ok++;
                                         }else{
                                            $err_db++;                               
                                         }
                                 }
                             }
                             
                             
                             if($box_gestione[$key]>0){
                                 $log .= "box_gestione($key) = "._nf($box_gestione[$key])."<br>";
                                 
                                 $movimenti_gestione = db_nr_q_3("id_utente",$box_id_ut[$key],"id_ordine",$id_ordine,"tipo_movimento",9,"retegas_cassa_utenti");
                                 if ($movimenti_gestione==0){
                                 
                                         $log .= "Nessun movimento antecedente, a ".$box_id_ut[$key]." imputati ".$box_gestione[$key]." come movimento 9 pagamento per gestione, segno "-".<br>";
                                         //SCARICA DAI CREDITI UTENTI COME "PAGAMENTO GESTIONE"
                                         if (db_insert_cassa_utenti($box_id_ut[$key],
                                                                    $id_ordine,
                                                                    _USER_ID,
                                                                    $idg,
                                                                    $idd,
                                                                    $box_gestione[$key],
                                                                    "-",
                                                                    9,
                                                                    0,
                                                                    "Pagamento Gestione a ".$nur,
                                                                    "",
                                                                    "",
                                                                    "no",
                                                                    "no")){
                                            $ok++;
                                         }else{
                                            $err_db++;                               
                                         }
                                         //CARICA LA STESSA CIFRA PER LA GESTIONE a REF. ORDINE"
                                         $log .= "Nessun movimento antecedente, a gestore ordine imputati ".$box_gestione[$key]." come movimento 9 pagamento per gestione, segno "+".<br>";
                                         if (db_insert_cassa_utenti(id_referente_ordine_globale($id_ordine),
                                                                    $id_ordine,
                                                                    _USER_ID,
                                                                    id_gas_user(id_referente_ordine_globale($id_ordine)),
                                                                    ditta_id_from_listino(listino_ordine_from_id_ordine($id_ordine)),
                                                                    $box_gestione[$key],
                                                                    "+",
                                                                    1,
                                                                    0,
                                                                    "Pagamento per gestione da ".fullname_from_id($box_reg[$key]),
                                                                    "",
                                                                    "",
                                                                    "no",
                                                                    "no")){
                                            $ok++;
                                         }else{
                                            $err_db++;                               
                                         }
                                 }
                             }//BOX GESTIONE > 0
                             
                             if($box_costi_gas[$key]>0){
                                 
                                 $movimenti_fingas = db_nr_q_3("id_utente",$box_id_ut[$key],"id_ordine",$id_ordine,"tipo_movimento",10,"retegas_cassa_utenti");
                                 if($movimenti_fingas ==0 ){
                                     $log .= "Nessun movimento antecedente, a "._nf($box_id_ut[$key])." imputati ".$box_costi_gas[$key]." come movimento 10 pagamento finanziamento gas, segno "-".<br>";
                                     //SCARICO A UTENTE COME "FINANZIAMENTO GAS" PER QUESTO ORDINE
                                     if (db_insert_cassa_utenti($box_reg[$key],
                                                                $id_ordine,
                                                                _USER_ID,
                                                                $idg,
                                                                $idd,
                                                                $box_costi_gas[$key],
                                                                "-",
                                                                "10",
                                                                1,
                                                                "Finanziamento GAS",
                                                                "",
                                                                "",
                                                                "no",
                                                                "no")){
                                        $ok++;
                                     }else{
                                        $err_db++;                               
                                     }
                                 
                                     //CARICO UTENTE 0 COME FINANZIAMENTO GAS
                                     //if (db_insert_cassa_utenti(0,
                                     //                           $id_ordine,
                                     //                           _USER_ID,
                                     //                           $idg,
                                     //                           $idd,
                                     //                           $box_costi_gas[$key],
                                     //                           "+",
                                     //                           "10",
                                     //                           1,
                                     //                           "Finanziamento GAS",
                                     //                           "",
                                     //                           "",
                                     //                           "no",
                                     //                           "no")){
                                     //   $ok++;
                                     //}else{
                                     //   $err_db++;                               
                                     //}
                                 
                                 
                                 }
                             } // BOX COSTI GAS >0
                             
                             
                     
                         
                         
                         
                                              
                    
                    
                    //MAIL INTERESSATO (POSTINO ?)
                }else{
                    $err_valuta++;
                }
            }else{
                $ignorati++;
            }
            
            //CONTA ERRORI
            
            
            
                
            }
       }
   $msg = "Risultato Operazione :<br>
           Salvati = <strong>$ok;</strong><br>
           Ignorati = $ignorati;<br> 
           Importi non riconosciuti = $err_valuta;<br>
           Errori Database = $err_db<br>
           ";
   $log .=$msg;        
   log_me($id_ordine,_USER_ID,"CAS","REG","Cassiere in azione !",0,$log);         
   } 
   
    
   // ISTANZIO un nuovo oggetto "retegas"

    $retegas = new sito;

    $retegas->posizione = "Cassa ordine su utente";


    // Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
    
    // Menu specifico per l'output  


    $retegas->menu_sito[] = ordini_menu_visualizza($user,$id_ordine);
    $retegas->menu_sito[] = ordine_menu_operazioni_base(_USER_ID,$id_ordine);
    $retegas->menu_sito[] = ordine_menu_mia_spesa(_USER_ID,$id_ordine);
    $retegas->menu_sito[] = ordine_menu_gas(_USER_ID,$id_ordine,_USER_ID_GAS);
    $retegas->menu_sito[] = ordine_menu_gestisci_new(_USER_ID,$id_ordine,_USER_ID_GAS);
    $retegas->menu_sito[] = ordine_menu_cassa(_USER_ID,$id_ordine,_USER_ID_GAS);
    $retegas->menu_sito[] = ordine_menu_comunica(_USER_ID,$id_ordine,_USER_ID_GAS);
    $retegas->menu_sito[] = ordine_menu_extra($id_ordine); 
    
    // dico a retegas quali sono i fogli di stile che dovrà usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
    //$retegas->css[]  = "datetimepicker"; 
      
    // dico a retegas quali file esterni dovrà caricare
    $retegas->java_headers = array("rg");  // editor di testo
          
      // creo  gli scripts per la gestione dei menu
      
      $ref_table = "lista_utenti";
      
      $retegas->java_scripts_header[] = java_accordion(null,menu_lat::ordini); // laterale    
      $retegas->java_scripts_header[] = java_superfish();       
      $retegas->java_scripts_header[]=  java_tablesorter($ref_table);
      //$retegas->java_scripts_bottom_body[]=  java_qtip();

      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      
      $retegas->help_page = "https://sites.google.com/site/retegasapwiki/i-menu-del-sito-retegas-ap/pagine-del-sito/i-miei-listini";     

      
            // qui ci va la pagina vera e proria  
      $retegas->content  =  schedina_ordine($id_ordine).
                            cassa_situazione_ordine_utenti($ref_table,$id_ordine,_USER_ID_GAS);
        
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas);      
      
      
      
?>
