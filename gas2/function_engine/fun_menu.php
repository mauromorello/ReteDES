<?php

//Funzionamento men?
//Il men? ? una gerarchia di UL LI
//Usano Jquery & Superfish per essere trasformati e visualizzati
//I men? sono divisi in settori, in modo da poter essere chiamati da gruppi di pagine
//la funzione COSASONO_menu_completo Raggruppa tutti i men? di un tipo di pagine
//In uscita c'? un array di elementi, che verranno disegnati da retegas->render
//
//Invece, ogni singola voce di men? esce come html semplice

//-------------------------------------------------HOME

 function home_menu_completo(){
      return;
 }

//-------------------------------------------------CASSA
 function gas_menu_gestisci_cassa(){

      global $RG_addr;

      $h_menu .= cassa_mia();

  if(_USER_PERMISSIONS & perm::puo_gestire_la_cassa){


            $h_menu .= cassa_amministra_cassa();
            $h_menu .= cassa_amministra_utenti();
            $h_menu .= cassa_amministra_gas();


  }



  return $h_menu;

  }

 function cassa_mia(){
    global $RG_addr;

    if(_USER_USA_CASSA){
    $h_menu ='<li><a class="medium green awesome" href="#"><b>Mia Cassa</b></a>';
            $h_menu .='<ul>';

                $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["movimenti_user_raggr"].'" >Raggruppamento</a></li>';
                $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["movimenti_cassa_users"].'">Dettaglio</a></li>';
                $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["cassa_suggerisci_movimento"].'">Suggerisci carico</a></li>';

            $h_menu .='</ul>';
            $h_menu .='</li>';


    }
    return $h_menu;
 }
 function cassa_amministra_utenti(){
    global $RG_addr;



    if(_USER_PERMISSIONS & perm::puo_gestire_la_cassa){
    $h_menu ='<li><a class="medium red awesome" href="#"><b>Utenti</b></a>';
            $h_menu .='<ul>';

                $h_menu .='<li><a class="medium blue awesome" href="'.$RG_addr["cassa_movimenti_suggeriti"].'" >Anticipi carico</a></li>';
                $h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["add_credits_multi"].'" >Aggiungi credito multiplo</a></li>';
                $h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["situazione_cassa_users"].'">Saldi Utenti</a></li>';

            $h_menu .='</ul>';
            $h_menu .='</li>';


    }
    return $h_menu;
 }
 function cassa_amministra_gas(){
    global $RG_addr;

    if(_USER_PERMISSIONS & perm::puo_gestire_la_cassa){
    $h_menu ='<li><a class="medium yellow awesome" href="#"><b>Gas</b></a>';

            $h_menu .='<ul>';

                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["movimenti_cassa_gas"].'">Movimenti GAS</a><li>';
                $h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["cassa_del_mov_gas"].'">Cancella tutti i movimenti di '.gas_nome(_USER_ID_GAS).'</a><li>';

                if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
                    $h_menu .='<li><a class="medium black awesome" href="'.$RG_addr["movimenti_cassa_gas"].'">Tutti i Movimenti ReteGas</a><li>';


                }
            $h_menu .='</ul>';
            $h_menu .='</li>';


    }
    return $h_menu;
 }
 function cassa_amministra_cassa(){
    global $RG_addr;

    if(_USER_PERMISSIONS & perm::puo_gestire_la_cassa){
     $h_menu ='<li><a class="medium celeste awesome" href="#"><b>Cassa</b></a>';
            $h_menu .='<ul>';
                $h_menu .='<li><a class="medium celeste awesome" href="'.$RG_addr["cassa_gas_panel"].'" >Pannello Cassa</a></li>';
                $h_menu .='<li><a class="medium celeste awesome" href="'.$RG_addr["cassa_options"].'" >Opzioni Cassa</a></li>';
                $h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["cassa_movimenti_reg"].'">Movimenti Da Registrare</a><li>';
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["cassa_movimenti_con"].'">Movimenti Da Contabilizzare</a><li>';

                //$h_menu .='<li><a class="medium celeste awesome" href="">Altro</a><li>';


            $h_menu .='</ul>';
            $h_menu .='</li>';


    }
    return $h_menu;
 }
 function cassa_menu_completo(){
    if(_USER_USA_CASSA){

        return cassa_mia().cassa_amministra_utenti().cassa_amministra_gas().cassa_amministra_cassa();

    }

 }


//-------------------------------------------------DES
function des_menu_completo($id_user){

      $mio_menu[] = des_menu_grafici($id_user);
      //$mio_menu[] = des_menu_comunica($id_user);
      $mio_menu[] = des_menu_partecipazione_ordini($id_user);
      $mio_menu[] = des_menu_geo();
      $mio_menu[] = des_menu_gestisci();

  return $mio_menu;

};
function des_menu_grafici($id_user){

      global $RG_addr;



            $h_menu ='<li><a class="medium green awesome"><b>Visualizza</b></a>';

            $h_menu .='<ul>';
            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["gas_table"].'" target="_self">Gas iscritti</a></li>';
            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["grafico_global_mon_all"].'" target="_self">Comparativo ordini tutti i GAS</a></li>';

            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["retegas_rec_act"].'" target="_self">Attivit? utenti</a></li>';
            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["retegas_perc_ut"].'" target="_self">Attivit? sito</a></li>';
            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["des_dimensione_gas"].'" target="_self">Dimensioni GAS</a></li>';
            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["des_soldi_giornaliero"].'" target="_self">Ordini Giornalieri Globale</a></li>';
            $h_menu .='</ul>';
            $h_menu .='</li>';



      return $h_menu;

  }
function des_menu_comunica($id_user){

      global $RG_addr;

      $id_gas = id_gas_user($id_user);
      $permission = leggi_permessi_utente($id_user);
      if($permission & perm::puo_vedere_retegas){

            $h_menu ='<li><a class="medium magenta awesome"><b>Comunica</b></a>';

            $h_menu .='<ul>';
            //$h_menu .='<li><a class="medium magenta awesome" href="'.$RG_addr[""].'" >Gruppo Google DES</a></li>';
            $h_menu .='<li><a class="medium magenta awesome" href="'.$RG_addr["gas_com_prg_des"].'" >Gruppo Retegas DES</a></li>';
            $h_menu .='<li><a class="medium magenta awesome" href="'.$RG_addr["gas_com_gas"].'" >Tutti gli utenti del tuo GAS</a></li>';
            $h_menu .='<li><a class="medium magenta awesome" href="'.$RG_addr["gas_com_retegas"].'" >Tutti gli utenti di ReteGas.AP</a></li>';
            $h_menu .='</ul>';
            $h_menu .='</li>';
      }


      return $h_menu;

  }
function des_menu_partecipazione_ordini($id_user){

      global $RG_addr;

      $icona_grafico = '<img SRC ="'.$RG_addr["img_icona_grafico"].'" width="20px" height="20px" alt="icona grafico" style="padding-right:1em;">';

      $id_gas = id_gas_user($id_user);

      if(_USER_PERMISSIONS & perm::puo_vedere_retegas){

            $h_menu ='<li><a class="medium yellow awesome"><b>Ordini</b></a>';
            $h_menu .='<ul>';
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["des_quanti_gestiscono"].'" target="_self">Utenti / Gestori</a></li>';
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["des_quanti_ordini_gas"].'" target="_self">Quanti ordini proposti</a></li>';
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["des_tipologie_acquisti"].'" target="_self">Tipologie di acquisti</a></li>';
            $h_menu .='</ul>';
            $h_menu .='</li>';
      }


      return $h_menu;

  }
function des_menu_geo(){

      global $RG_addr;

            $h_menu ='<li><a class="medium blue awesome"><b>GeoCose</b></a>';

                $h_menu .='<ul>';
                    $h_menu .='<li><a class="medium blue awesome" href="'.$RG_addr["des_geo_dimensioni_gas"].'" >Dimensioni GAS</a></li>';
                    $h_menu .='<li><a class="medium blue awesome" href="'.$RG_addr["des_geo_ultimi_ordini"].'" >Ultimi ordini</a></li>';
                    $h_menu .='<li><a class="medium blue awesome" href="'.$RG_addr["des_geo_valori_ditte"].'" >Soldi alle ditte</a></li>';
                $h_menu .='</ul>';
            $h_menu .='</li>';



      return $h_menu;

  }
function des_menu_gestisci(){

      global $RG_addr;



            $h_menu ='<li><a class="medium beige awesome"><b>Gestisci</b></a>';

            $h_menu .='<ul>';
            $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["des_permessi_gas"].'" target="_self">Permessi GAS aderenti</a></li>';
            $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["des_nuovo_gas"].'" target="_self">Crea un nuovo GAS</a></li>';
            $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["des_nuovi_utenti"].'" target="_self">Attiva nuovi utenti</a></li>';
            $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["des_switch_responsabile"].'" target="_self">Cambio responsabile GAS</a></li>';

            //$h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["retegas_rec_act"].'" target="_self">Attivit? utenti</a></li>';
            //$h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["retegas_perc_ut"].'" target="_self">Attivit? sito</a></li>';
            //$h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["des_dimensione_gas"].'" target="_self">Dimensioni GAS</a></li>';
            //$h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["des_soldi_giornaliero"].'" target="_self">Ordini Giornalieri Globale</a></li>';
            $h_menu .='</ul>';
            $h_menu .='</li>';



      return $h_menu;

  }



//------------------------------------------------AMMINISTRA
function amministra_menu_users(){
    global $RG_addr;


        $h_menu ='<li><a class="medium red awesome"><b>Utenti</b></a>';
            $h_menu.='<ul>';
                $h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["amministra_ute_tutti"].'" target="_self">Tutti</a></li>';
                //$h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["amministra_all_users"].'?do=not_act" target="_self">In dolce attesa</a></li>';
                $h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["amministra_ute_last_act"].'" target="_self">Attivi di recente</a></li>';
                $h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["amministra_ute_storti"].'" target="_self">Cose strane</a></li>';
                $h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["amministra_ute_temi"].'" target="_self">Temi</a></li>';
                $h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["amministra_ute_widg"].'" target="_self">Widgets</a></li>';
                $h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["amministra_ute_not_act"].'" target="_self">Utenti NON attivi</a></li>';
                $h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["amministra_user_donate"].'" target="_self">Donazioni</a></li>';
            $h_menu .='</ul>';  // Visualizza
        $h_menu .='</li>';

        return $h_menu;
 }
function amministra_menu_log(){
global $RG_addr;

$h_menu .='<li><a class="medium green awesome"><b>LOG</b></a>';
$h_menu .='<ul>';

     $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["amministra_log_all"].'">Tutti</a></li>';

$h_menu .='</ul>';
$h_menu .='</li>';
return $h_menu;
}
function amministra_menu_007(){
    global $RG_addr;
        $h_menu ='<li><a class="medium black awesome"><b>007</b></a>';
            $h_menu.='<ul>';
                $h_menu .='<li><a class="medium black awesome" href="'.$RG_addr["amministra_infiltrati"].'" target="_self">Infiltrati</a></li>';
            $h_menu .='</ul>';  // Visualizza
        $h_menu .='</li>';

        return $h_menu;
}
function amministra_menu_gas(){
    global $RG_addr;
        $h_menu ='<li><a class="medium yellow awesome"><b>GAS</b></a>';
            $h_menu.='<ul>';
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["amministra_nuovo_gas"].'" target="_self">Nuovo GAS</a></li>';
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["amministra_gas_table_opt"].'" target="_self">Opzioni GAS</a></li>';
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["amministra_gas_donate_all_gas"].'" target="_self">Donazioni GAS</a></li>';

            $h_menu .='</ul>';  // Visualizza
        $h_menu .='</li>';

        return $h_menu;
}
function amministra_menu_geocoding(){
    global $RG_addr;
        $h_menu ='<li><a class="medium marrone awesome"><b>GEO</b></a>';
            $h_menu.='<ul>';
                $h_menu .='<li><a class="medium marrone awesome" href="'.$RG_addr["amministra_geocoding"].'" target="_self">Aggiorna id Geocoding</a></li>';
                $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["gas_form_total"].'" target="_self">Geolocalizzazione</a></li>';
                $h_menu .='<li><a class="medium brown awesome" href="'.$RG_addr["ci_presentazione"].'" target="_self">Pres Cose(in)Utili</a></li>';
                $h_menu .='<li><a class="medium brown awesome" href="'.$RG_addr["ci_register"].'" target="_self">Reg Cose(in)Utili</a></li>';
               // $h_menu .='<li><a class="medium brown awesome" href="'.$RG_addr["amministra_upd_geo"].'?do=gas" target="_self">Aggiorna Geo GAS</a></li>';
               // $h_menu .='<li><a class="medium brown awesome" href="'.$RG_addr["amministra_upd_geo"].'?do=ditte" target="_self">Aggiorna Geo Ditte</a></li>';
            $h_menu .='</ul>';
        $h_menu .='</li>';

        return $h_menu;
}
function amministra_menu_opt(){
global $RG_addr;

    $h_menu .='<li><a class="medium blue awesome"><b>Opt.</b></a>';
    $h_menu .='<ul>';

     $h_menu .='<li><a class="medium blue awesome" href="'.$RG_addr["amministra"].'?do=m_ON" target="_self">Mailer ON</a></li>';
     $h_menu .='<li><a class="medium blue awesome" href="'.$RG_addr["amministra"].'?do=m_OFF" target="_self">Mailer OFF</a></li>';
     //$h_menu .='<li><a class="medium blue awesome" href="'.$RG_addr["amministra"].'?do=geocode" target="_self">Geocode</a></li>';
     $h_menu .='<li><a class="medium blue awesome" href="'.$RG_addr["amministra"].'?do=d_ON" target="_self">Debug ON</a></li>';
     $h_menu .='<li><a class="medium blue awesome" href="'.$RG_addr["amministra"].'?do=d_OFF" target="_self">Debug OFF</a></li>';
     $h_menu .='<li><a class="medium blue awesome" href="'.$RG_addr["amministra_option_table"].'" >OPZIONI</a></li>';
     $h_menu .='<li><a class="medium blue awesome" href="'.$RG_addr["amministra_merge_ditte"].'" >MERGE DITTE</a></li>';


$h_menu .='</ul>';
$h_menu .='</li>';
return $h_menu;
}
function amministra_menu_ordini(){
global $RG_addr;

    $h_menu .='<li><a class="medium beige awesome"><b>Ord.</b></a>';
    $h_menu .='<ul>';

     $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["amministra_integrita_ordine"].'" target="_self">Integrit? Ordini</a></li>';
     $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["amministra_ordini_tutti"].'" target="_self">Tutti gli ordini</a></li>';
     $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["amministra_log_ordine_singolo"].'" target="_self">LOG ordine singolo</a></li>';
     $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["amministra_ordini_time_interval"].'" target="_self">Time Interval Ordini</a></li>';
 //$h_menu .='<li><a class="medium blue awesome" href="'.$pa.'amministra_options_table.php" target="_self">OPZIONI</a></li>';

$h_menu .='</ul>';
$h_menu .='</li>';
return $h_menu;
}
function amministra_menu_db(){
global $RG_addr;

    $h_menu .='<li><a class="medium orange awesome"><b>DB</b></a>';
    $h_menu .='<ul>';

     $h_menu .='<li><a class="medium orange awesome" href="'.$RG_addr["amministra_rel_db"].'?do=dis" >Distribuzione Orfana</a></li>';
     $h_menu .='<li><a class="medium orange awesome" href="'.$RG_addr["amministra_rel_db"].'?do=o_s_l" >Ordini orfani</a></li>';
     $h_menu .='<li><a class="medium orange awesome" href="'.$RG_addr["amministra_rel_db"].'?do=art" >Articoli orfani</a></li>';
     $h_menu .='<li><a class="medium orange awesome" href="'.$RG_addr["amministra_rel_db"].'?do=lis" >Listini orfani</a></li>';
     $h_menu .='<li><a class="medium orange awesome" href="'.$RG_addr["amministra_rel_db"].'?do=ref" >Referenze orfane</a></li>';
     $h_menu .='<li><a class="medium orange awesome" href="'.$RG_addr["amministra_rel_db"].'?do=ami" >Amici orfani</a></li>';
     //$h_menu .='<li><a class="medium orange awesome" href="'.$RG_addr["amministra_ordini_tutti"].'" target="_self">Tutti gli ordini</a></li>';

 //$h_menu .='<li><a class="medium blue awesome" href="'.$pa.'amministra_options_table.php" target="_self">OPZIONI</a></li>';

$h_menu .='</ul>';
$h_menu .='</li>';
return $h_menu;
}
function amministra_menu_completo(){

  $mio_menu[] = amministra_menu_users();
  $mio_menu[] = amministra_menu_gas();
  $mio_menu[] = amministra_menu_geocoding();
  $mio_menu[] = amministra_menu_007();
  $mio_menu[] = amministra_menu_log();
  $mio_menu[] = amministra_menu_opt();
  $mio_menu[] = amministra_menu_ordini();
  $mio_menu[] = amministra_menu_db();
  // _USER_PERMISSION E' LETTA IN FASE DI LOGIN (fun_users -> is_logged_in($id_user))

  if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
      return $mio_menu;
  }else{
      return;
  }

};



//------------------------------------------------STATISTICHE



function storici_menu_personali(){

        global $RG_addr;



        $h_menu ='<li><a class="medium green awesome"><b>Personali</b></a>';
        $h_menu.='<ul>';
        $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["storici_miei_ordini"].'" target="_self">Ordini effettuati</a></li>';
        $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["storici_ditte_mie"].'" target="_self">miei ordini su Ditte</a></li>';

        $h_menu .='</ul>';  // Visualizza
        $h_menu .='</li>';

        return $h_menu;
    }
function storici_menu_gas(){
        //echo "SON IN ESPORTA<br> locatione = $location";
        global $RG_addr;



            $h_menu ='<li><a class="medium yellow awesome"><b>GAS</b></a>';
            $h_menu.='<ul>';
            $h_menu.='<li><a class="medium yellow awesome">Storici ditte</a>';
                $h_menu .='<ul>';
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["storici_ditte_gas"].'" target="_self"><img SRC ="'.$RG_addr["img_icona_scheda"].'" width="20px" height="20px" alt="icona scheda" style="padding-right:1em;">Tabulare</a></li>';
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["storici_ditte_gas_graf"].'" target="_self"><img SRC ="'.$RG_addr["img_icona_grafico"].'" width="20px" height="20px" alt="icona grafico" style="padding-right:1em;">Grafico</a></li>';
                $h_menu .='</ul>';
            $h_menu .='</li>';
            $h_menu.='<li><a class="medium yellow awesome">Storici famiglie</a>';
                $h_menu .='<ul>';
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["storici_fam_gas"].'" target="_self"><img SRC ="'.$RG_addr["img_icona_scheda"].'" width="20px" height="20px" alt="icona scheda" style="padding-right:1em;">Tabulare</a></li>';
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["storici_fam_gas_graf"].'" target="_self"><img SRC ="'.$RG_addr["img_icona_grafico"].'" width="20px" height="20px" alt="icona grafico" style="padding-right:1em;">Grafico</a></li>';
                $h_menu .='</ul>';
            $h_menu .='</li>';
            $h_menu.='<li><a class="medium yellow awesome">Ordini GAS</a>';
                $h_menu .='<ul>';
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["storici_ordini_gas"].'" target="_self"><img SRC ="'.$RG_addr["img_icona_scheda"].'" width="20px" height="20px" alt="icona scheda" style="padding-right:1em;">Tabulare</a></li>';
                //$h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["storici_fam_gas_graf"].'" target="_self"><img SRC ="'.$RG_addr["img_icona_grafico"].'" width="20px" height="20px" alt="icona grafico" style="padding-right:1em;">Grafico</a></li>';
                $h_menu .='</ul>';
            $h_menu .='</li>';
            $h_menu .='</ul>';  // Visualizza
            $h_menu .='</li>';

        return $h_menu;
    }
function storici_menu_completo(){

  $mio_menu[] = storici_menu_personali();
  $mio_menu[] = storici_menu_gas();
  // _USER_PERMISSION E' LETTA IN FASE DI LOGIN (fun_users -> is_logged_in($id_user))

  if(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini){
      return $mio_menu;
  }else{
      return;
  }

};


//---------------------------------------------------DITTE
function ditte_menu_visualizza($id_ditta=null){
        //echo "SON IN ESPORTA<br> locatione = $location";
        global $RG_addr;

        $h_menu ='<li><a class="medium green awesome"><b>Visualizza</b></a>';
            $h_menu.='<ul>';
            if(!is_null($id_ditta)){
                $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["ditte_form_new"].'?id_ditta='.$id_ditta.'" target="_self">Scheda ditta</a></li>';
            }
            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["tutte_le_ditte"].'" target="_self">Tutte le ditte</a></li>';
            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["mie_ditte"].'" target="_self">Mie ditte</a></li>';
            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["ditte_geo"].'" target="_self">Mappa ditte</a></li>';
            $h_menu .='</ul>';  // Visualizza
        $h_menu .='</li>';

        return $h_menu;
    }
function ditte_menu_statistiche($id_ditta=null){
    global $RG_addr;

        if(($id_ditta)<>""){

            $h_menu ='<li><a class="medium celeste awesome"><b>Statistiche</b></a>';
            $h_menu.='<ul>';
            $h_menu .='<li><a class="medium celeste awesome" href="'.$RG_addr["ditte_form_stat_listini"].'?id_ditta='.$id_ditta.'" target="_self">Articoli infra-listini</a></li>';
            $h_menu .='<li><a class="medium celeste awesome" href="'.$RG_addr["ditte_form_stat_ord_s"].'?id_ditta='.$id_ditta.'" target="_self">Valore ordini</a></li>';

            $h_menu .='</ul>';  // Visualizza
            $h_menu .='</li>';
        }
        return $h_menu;

}
function ditte_menu_modifica($id_ditta=null){
    global $RG_addr;

        if(_USER_ID == ditta_user($id_ditta)){
            $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["mod_ditta"].'?id='.$id_ditta.'"><b>Modifica Ditta</b></a></li>';
        }

        return $h_menu;
}
function ditte_menu_nuovo($id_ditta=null){
    global $RG_addr;


        if((_USER_PERMISSIONS & perm::puo_creare_ditte) AND ($id_ditta=="")){
            $h_menu .='<li><a class="medium blue awesome" href="'.$RG_addr["add_ditta"].'"><b>Nuova Ditta</b></a></li>';

        }
        if(isset($id_ditta) AND _USER_PERMISSIONS & perm::puo_creare_listini){
            $h_menu .='<li><a class="medium blue awesome" href="'.$RG_addr["add_listino"].'?id='.$id_ditta.'"><b>Nuovo Listino</b></a></li>';
        }

        return $h_menu;
}
function ditte_menu_des($id_ditta=null){
    global $RG_addr;



        if(isset($id_ditta) AND _USER_PERMISSIONS & perm::puo_vedere_retegas){
            $h_menu .='<li><a class="small magenta awesome" href="'.$RG_addr["opinione_certificante"].'?id_ditta='.$id_ditta.'"><b>CERTIFICA COME DES</b></a></li>';
        }

        return $h_menu;
}
function ditte_menu_gas($id_ditta=null){
    global $RG_addr;



        if(isset($id_ditta) AND _USER_PERMISSIONS & perm::puo_creare_gas){
            $h_menu .='<li><a class="small magenta awesome" href="'.$RG_addr["opinione_relazionante"].'?id_ditta='.$id_ditta.'"><b>RELAZIONA come GAS</b></a></li>';
        }

        return $h_menu;
}
function ditte_menu_completo($id_ditta=null){


       $mio_menu[] = ditte_menu_visualizza($id_ditta);
       $mio_menu[] = ditte_menu_statistiche($id_ditta);
       $mio_menu[] = ditte_menu_nuovo($id_ditta);
       $mio_menu[] = ditte_menu_modifica($id_ditta);
       $mio_menu[] = ditte_menu_des($id_ditta); // SOLO PER REFERENTI DES
       $mio_menu[] = ditte_menu_gas($id_ditta); // SOLO PER REFERENTI GAS

        return $mio_menu;
}





//--------------------------------------------------LISTINI
  function listini_menu($user,$id=null){

  //VUOTA DA CANCELLARE
  }
  function listini_menu_esportazione($id_listino){

  global $RG_addr;

// ------------------------------------------------Esporta

            $h_menu .='<li><a class="medium silver awesome"><b>Esporta</b></a>';
            $h_menu .='<ul>';
            $h_menu .='<li><a class="medium silver awesome" href="'.$RG_addr["listini_export"].'?id='.$id_listino.'&type=1" target="_blank">File Excel</a></li>';
            $h_menu .='<li><a class="medium silver awesome" href="'.$RG_addr["listini_export"].'?id='.$id_listino.'&type=2" target="_blank">Tabella HTML</a></li>';
            $h_menu .='<li><a class="medium silver awesome" href="'.$RG_addr["listini_export"].'?id='.$id_listino.'&type=3" target="_blank">File CSV</a></li>';
            $h_menu .='</ul>';
            $h_menu .='</li>';




return $h_menu;

  }
  function listini_menu_ordine($id_listino){
        global $RG_addr;


            if(_USER_PERMISSIONS AND perm::puo_creare_ordini){
                if(listino_tipo($id_listino)==0){
                    if(articoli_n_in_listino($id_listino)>0){
                        if(listino_tempo_valido($id_listino)){
                         $h_menu .='<li><a class="medium beige awesome" href="#"><b>Nuovo ordine !</b></a>';
                         $h_menu .='<ul>';
                            $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["nuovo_ordine_simple"].'?id_listino='.$id_listino.'">Semplice</a></li>';
                            $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["nuovo_ordine_completo"].'?id_listino='.$id_listino.'">Completo</a></li>';
                         $h_menu .='</ul>';
                         $h_menu .='</li>';
                    }
                    }
                }
            }
       return $h_menu;
  }
  function listini_menu_gestione_articoli($id_listino){
      global $RG_addr;
      // ------------------------------------------------ARTICOLI

        if(listino_proprietario($id_listino)==_USER_ID){
            $h_menu .='<li><a class="medium orange awesome"><b>Aggiungi Articoli</b></a>';
            $h_menu .='<ul>';
                $h_menu .='<li><a class="medium orange awesome" href="../articoli/articoli_form_add.php?id='.$id_listino.'" target="_self">Uno per volta</a></li>';
                $h_menu .='<li><a class="medium orange awesome" href="'.$RG_addr["select_listino_maga"].'?id_listino='.$id_listino.'" target="_self">Da un listino MAGAZZINO</a></li>';
                $h_menu .='<li><a class="medium orange awesome">Tutti assieme</a>';
                    $h_menu .='<ul>';
                        $h_menu .='<li><a class="medium orange awesome" href="'.$RG_addr["listini_upload"].'?id='.$id_listino.'&tipo_file=XLS" target="_self">File MS EXCEL (.XLS)</a></li>';
                        $h_menu .='<li><a class="medium orange awesome" href="'.$RG_addr["listini_upload"].'?id='.$id_listino.'&tipo_file=CSV" target="_self">File CSV (Tabella di testo)</a></li>';
                        //$h_menu .='<li><a class="medium orange awesome" href="'.$RG_addr["listini_upload"].'?id='.$id_listino.'&tipo_file=GOO" target="_self">OnLine (Google Docs)</a></li>';
                    $h_menu .='</ul></li>';

            $h_menu .='</ul>';
            $h_menu .='</li>';
        }

// ------------------------------------------------ARTICOLI
  return $h_menu;
  }
  function listini_menu_operazioni($id_listino){
      global $RG_addr;

      // ------------------------------------------------Operazioni


            $h_menu .='<li><a class="medium yellow awesome"><b>Operazioni</b></a>';
            $h_menu .='<ul>';
            $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["listini_form_clone"].'?id='.$id_listino.'">Clona questo listino</a></li>';

             if(listino_proprietario($id_listino)==_USER_ID){
                 $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["listini_form_edit"].'?id_listini='.$id_listino.'">Modifica intestazione listino</a></li>';

                 if(articoli_n_in_listino($id_listino)==0){
                    $h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["listini_form_delete"].'?id_listino='.$id_listino.'">Elimina questo listino</a></li>';
                 }else{

                 $h_menu .='<li><a class="medium yellow awesome">Operazioni sugli articoli</a>';
                    $h_menu .='<ul>';
                        $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["delete_articoli"].'?id_listini='.$id_listino.'">Cancella alcuni articoli da questo listino</a></li>';
                        $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["modifica_prz_alc_art"].'?id_listini='.$id_listino.'">Modifica i prezzi di alcuni articoli</a></li>';
                        $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["modifica_dsc_alc_art"].'?id_listini='.$id_listino.'">Modifica la descrizione di alcuni articoli</a></li>';
                        if(quanti_ordini_per_questo_listino($id_listino)==0){
                            $h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["listini_form_empty"].'?id_listino='.$id_listino.'" target="_self">Svuota questo listino</a></li>';
                        }
                    $h_menu .='</ul></li>';



                 }

             }
            $h_menu .='</ul>';
            $h_menu .='</li>';
      return $h_menu;
  }

  function listini_menu_completo($id_listino){

        $mio_menu[] = listini_menu_ordine($id_listino);
        $mio_menu[] = listini_menu_gestione_articoli($id_listino);
        $mio_menu[] = listini_menu_operazioni($id_listino);
        $mio_menu[] = listini_menu_esportazione($id_listino);

        return $mio_menu;
  }


//----------------------------------------------------GAS
  function gas_menu_nuovi_utenti(){

      if((_USER_PERMISSIONS & perm::puo_gestire_utenti)){

            $h_menu ='<li><a class="medium beige awesome" href="gas_utente_add.php"><b>Crea nuovo utente</b></a></li>';

      }

      return $h_menu;

  }
  function gas_menu_visualizza(){

      global $RG_addr;



            $h_menu ='<li><a class="medium green awesome"><b>Visualizza</b></a>';

            $h_menu .='<ul>';

            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["gas_form"].'" target="_self">Scheda</a>';
            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["grafico_global_mon"].'" target="_self">Grafico Spesa</a></li>';
            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["gas_users"].'" target="_self">Tabella Utenti</a></li>';
            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["gas_composizione"].'" target="_self">Composizione</a></li>';
            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["gas_ids"].'" target="_self">Indice di solidariet?</a></li>';
            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["gas_non_siamo_soli"].'" target="_self">I gas esterni vicini a te</a></li>';
            $h_menu .='</ul>';
            $h_menu .='</li>';



      return $h_menu;

  }
  function gas_zeus(){



      $h_menu ='<li><a class="medium black awesome" href="gas_form_total.php"><b>Geo Utenti</b></a></li>';





      return $h_menu;

  }
  function gas_menu_gestisci_gas(){

  global $RG_addr;




  if((_USER_PERMISSIONS & perm::puo_creare_gas)){

            $h_menu ='<li><a class="medium yellow awesome" href="#"><b>Gestisci GAS</b></a>';

            $h_menu .='<ul>';

                    $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["gas_modifica"].'" target="_self">Modifica dati</a></li>';
                    $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["gas_option_sito"].'" target="_self">Gestisci opzioni GAS</a></li>';
                    $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["gas_nuovo_gas"].'" target="_self">Crea nuovo GAS</a></li>';
            $h_menu .='</ul>';

            $h_menu .='</li>';



  }



  return $h_menu;

  }
  function gas_menu_gestisci_utenti(){
      global $RG_addr;

      if(_USER_PERMISSIONS & perm::puo_gestire_utenti){

        $h_menu ='<li><a class="medium beige awesome" href="#"><b>Gestisci Utenti</b></a>';
        $h_menu .='<ul>';

            //$h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["gas_users"].'">Utenti tuo GAS</a></li>';
            $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["gas_users"].'">Utenti Attivi</a></li>';
            //$h_menu .='<li><a class="medium silver awesome" href="#">Esporta in CSV :</a>';
            //    $h_menu .='<ul>';
                    //$h_menu .='<li><a class="medium silver awesome" href="'.$RG_addr["gas_users"].'?out=xls">Esporta EXCEL</a></li>';
            //        $h_menu .='<li><a class="medium silver awesome" href="'.$RG_addr["gas_users"].'?out=csv">Tutti gli utenti</a></li>';
            //    $h_menu .='</ul>';
            //$h_menu .='</li>';
            $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["gas_sposta_utenti"].'" target="_self">Sposta utenti</a></li>';

            $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["gas_perm_new_users"].'" target="_self">Permessi nuovi utenti</a></li>';
            $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["user_add"].'">Crea Nuovo utente</a></li>';
            $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["user_to_activate"].'">Utenti da attivare</a><li>';

            $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["user_suspended"].'">Utenti sospesi</a></li>';
            $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["user_deleted"].'">Utenti cancellati</a></li>';
            $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["utenti_chifacosa"].'">Chi può fare cosa</a></li>';
            $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["user_activity"].'">Ultime attività</a><li>';

            if(_USER_PERMISSIONS & perm::puo_gestire_retegas){

            }
        $h_menu .='</ul>';
        $h_menu .='</li>';

      }
      return $h_menu;
  }
  function gas_menu_gestisci_export(){
      global $RG_addr;

      if(_USER_PERMISSIONS & perm::puo_gestire_utenti){

        $h_menu ='<li><a class="medium silver awesome" href="#"><b>Esporta</b></a>';
        $h_menu .='<ul>';
              $h_menu .='<li><a class="medium silver awesome" href="'.$RG_addr["gas_users"].'?out=csv_all">Tutti gli utenti</a></li>';
              $h_menu .='<li><a class="medium silver awesome" href="'.$RG_addr["gas_users"].'?out=csv_act">Utenti Attivi</a></li>';
              $h_menu .='<li><a class="medium silver awesome" href="'.$RG_addr["gas_users"].'?out=csv_sus">Utenti Sospesi</a></li>';
              $h_menu .='<li><a class="medium silver awesome" href="'.$RG_addr["gas_users"].'?out=csv_eli">Utenti Eliminati</a></li>';


        $h_menu .='</ul>';
        $h_menu .='</li>';

      }
      return $h_menu;
  }

  function gas_menu_completo(){

      $mio_menu[] = gas_menu_visualizza();
      $mio_menu[] = gas_menu_comunica();
      $mio_menu[] = gas_menu_gestisci_gas();
      $mio_menu[] = gas_menu_gestisci_utenti();
      $mio_menu[] = gas_menu_gestisci_export();
      //$mio_menu[] = gas_menu_gestisci_cassa();

  return $mio_menu;

  };
  function gas_menu_comunica(){
      global $RG_addr;



      if((_USER_PERMISSIONS & perm::puo_postare_messaggi)){

            $h_menu ='<li><a class="medium magenta awesome">Comunica</a>';

            $h_menu .='<ul>';
                    $h_menu .='<li><a class="medium magenta awesome" href="gas_comunica_gas.php" target="_self">Al mio GAS</a></li>';
                    $h_menu .='<li><a class="medium magenta awesome" href="gas_comunica_utenti_gas.php" target="_self">Ad alcuni utenti del mio GAS</a></li>';
                    $h_menu .='<li><a class="medium magenta awesome" href="gas_comunica_retegas.php" target="_self">a tutta '._SITE_NAME.'</a></li>';
                    if(id_proprio_referente_retegas(_USER_ID_GAS)==_USER_ID){
                        $h_menu .='<li><a class="medium black awesome" href="gas_comunica_gas_hermes.php" target="_self">Al mio GAS (modalit? HERMES)</a></li>';
                        $h_menu .='<li><a class="medium black awesome" href="gas_comunica_retegas_hermes.php" target="_self">a tutta '._SITE_NAME.' (modalit? HERMES)</a></li>';
                    }

            $h_menu .='</ul>';
            $h_menu .='</li>';
      }

  return $h_menu;

  }

// ----------------------------------------------------------UTENTI
function menu_visualizza_user($id){
     global $RG_addr;

          $id=mimmo_encode($id);

          $h_menu .='<li><a class="medium green awesome" href="#">Visualizza</a>';
          $h_menu .='<ul>';
            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["user_form_public"].'?id_utente='.$id.'">La mia scheda pubblica</a></li>';
            $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["pag_users_form_mia"].'">La mia scheda privata</a></li>';
          $h_menu .='</ul>';
          $h_menu .='</li>';

   return $h_menu;
 }
function menu_gestisci_user($id_user,$id_user_target = null){
     global $RG_addr;

     if(isset($id_user)){

          //$user_level = user_level($id_user);


          $h_menu .='<li><a class="medium yellow awesome" href="#">Gestisci</a>';
          $h_menu .='<ul>';

            if(is_null($id_user_target)){
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["user_option_sito"].'">Modifica Opzioni Sito</a></li>';
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["pag_users_form_mia_edit"].'">Modifica propri dati</a></li>';
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["pag_users_form_password"].'">Modifica Password</a></li>';
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["pag_users_form_widgets"].'">Gestisci HomePage</a></li>';
                $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["pag_users_theme_select"].'">Modifica tema grafico</a></li>';
            }else{
                $usern = fullname_from_id($id_user_target);
                $id_user_target = mimmo_encode($id_user_target);
                if(db_val_q("id_gas",_USER_ID_GAS,"id_referente_gas","retegas_gas")==_USER_ID){
                    $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["utenti_superpoteri"].'?id_utente='.$id_user_target.'">Superpoteri di '.$usern.'</a></li>';
                }
                if(_USER_PERMISSIONS & perm::puo_gestire_utenti){
                    $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["user_permission_site"].'?id='.$id_user_target.'">Permessi di '.$usern.'</a></li>';
                    $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["utenti_nuova_password"].'?id_utente='.$id_user_target.'">Nuovi dati di accesso per '.$usern.'</a></li>';
                    $h_menu .='<li><a class="medium yellow awesome" href="#">Stato utente</a>';
                    $h_menu .='<ul>';
                    $h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["pag_users_form"].'?id_utente='.$id_user_target.'&do=del">ELIMINA '.$usern.'</a></li>';
                    $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["pag_users_form"].'?id_utente='.$id_user_target.'&do=susp">SOSPENDI '.$usern.'</a></li>';
                    $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["pag_users_form"].'?id_utente='.$id_user_target.'&do=act">ATTIVA '.$usern.'</a></li>';

                    $h_menu .='<li><a class="medium silver awesome" href="'.$RG_addr["pag_users_form"].'?id_utente='.$id_user_target.'&do=stb">RIMETTI IN ATTESA '.$usern.'</a></li>';
                    $h_menu .='</ul>';
                }
                if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
                    $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["amministra_user_info"].'?id_utente='.$id_user_target.'">Statistiche Admin di '.$usern.'</a></li>';
                }
            }
            //$h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["pag_amici_canc"].'?id_user='.$id_user.'">Opzioni sito</a></li>';
            $h_menu .='</ul>';
          $h_menu .='</li>';
      }

   return $h_menu;

}
function menu_gestisci_user_cassa($id_user,$id_user_target = null){
     global $RG_addr;

     if(isset($id_user)){

          $user_level = user_level($id_user);


          $h_menu .='<li><a class="medium celeste awesome" href="#">Cassa</a>';
          $h_menu .='<ul>';

            if(is_null($id_user_target)){
                $h_menu .='<li><a class="medium celeste awesome" href="'.$RG_addr[""].'">Movimenti personali</a></li>';
            }else{
                if (id_gas_user($id_user)==id_gas_user($id_user_target)){


                    $usern = fullname_from_id($id_user_target);
                    $id_user_target = mimmo_encode($id_user_target);

                    if(perm::puo_gestire_la_cassa & leggi_permessi_utente($id_user)){

                        $h_menu .='<li><a class="medium celeste awesome" href="'.$RG_addr["movimenti_cassa_users"].'?id_utente='.$id_user_target.'">Movimenti di '.$usern.'</a></li>';
                        $h_menu .='<li><a class="medium celeste awesome" href="'.$RG_addr["add_credits"].'?id_ut='.$id_user_target.'">Aggiungi credito a '.$usern.'</a></li>';
                        $h_menu .='<li><a class="medium celeste awesome" href="'.$RG_addr["sub_credits"].'?id_ut='.$id_user_target.'">Sottrai credito a '.$usern.'</a></li>';
                        $h_menu .='<li><a class="medium celeste awesome" href="'.$RG_addr["rett_credits"].'?id_ut='.$id_user_target.'">Rettifica credito di '.$usern.'</a></li>';

                    }
                }
            }
            //$h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["pag_amici_canc"].'?id_user='.$id_user.'">Opzioni sito</a></li>';
            $h_menu .='</ul>';
          $h_menu .='</li>';
      }

   return $h_menu;

}

//-------------------------------------------------ARTICOLI
function articoli_menu_completo($id_articolo){

      $mio_menu[] = articoli_menu_operazioni($id_articolo);
      //$mio_menu[] = des_menu_comunica($id_user);

  return $mio_menu;

};
function articoli_menu_operazioni($id_articolo){

      global $RG_addr;




            $h_menu ='<li><a class="medium yellow awesome"><b>Operazioni</b></a>';

            $h_menu .='<ul>';
            $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["articoli_edit"].'?id_articoli='.$id_articolo.'" target="_self">Modifica</a></li>';
            $h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["articoli_delete"].'?id='.$id_articolo.'" target="_self">Elimina</a></li>';
            $h_menu .='</ul>';
            $h_menu .='</li>';

      if(articoli_in_ordine($id_articolo)==0){
        if(articoli_user($id_articolo)==_USER_ID){
            return $h_menu;
        }
     }

  }

//------------------------------------------------EXTRA
    function extra_menu_dashboard($id_ordine=null){
        global $RG_addr;


            $h_menu ='<li><a class="medium yellow awesome"><b>Gestisci</b></a>';

            $h_menu .='<ul>';
            $h_menu .='<li><a class="medium yellow awesome" href="'.$RG_addr["extra_blacklist"].'" target="_self">Ordini Nascosti</a></li>';
            //$h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["articoli_delete"].'?id='.$id_articolo.'" target="_self">Elimina</a></li>';
            $h_menu .='</ul>';
            $h_menu .='</li>';


        return $h_menu;
    }
    function extra_menu_operazioni($id_ordine=null){
        global $RG_addr;


            $h_menu ='<li><a class="medium blue awesome"><b>Operazioni</b></a>';

            $h_menu .='<ul>';
            $h_menu .='<li><a class="medium blue awesome" href="'.$RG_addr[""].'?id_ordine='.$id_ordine.'" target="_self">Modifica stato</a></li>';
            //$h_menu .='<li><a class="medium red awesome" href="'.$RG_addr["articoli_delete"].'?id='.$id_articolo.'" target="_self">Elimina</a></li>';
            $h_menu .='</ul>';
            $h_menu .='</li>';


        return $h_menu;
    }
    function extra_menu_all($id_ordine=null){

        $m = array();
        $m[] = extra_menu_dashboard($id_ordine);
        $m[] = extra_menu_operazioni($id_ordine);
        return $m;
    }

//-------------------------------------------------ORDINI
    //MENU ORDINI
    function ordini_menu_visualizza($user=null,$id_ordine=null){
        global $RG_addr;


        $h_menu ='<li><a class="medium green awesome">Visualizza</a>';
        $h_menu.='<ul>';

        if(!empty($id_ordine)){
        $h_menu .='<li><a class="medium green awesome" href="#" target="_self">di questo ordine:</a>';
        $h_menu .='<ul>';
        $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["ordini_form_new"].'?id_ordine='.$id_ordine.'" >Scheda riepilogativa</a></li>';
        $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["grafico_1"].'?id_ordine='.$id_ordine.'"  >Grafici</a></li>';
        $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["ordini_form_contabilita"].'?id_ordine='.$id_ordine.'" >Contabilit?</a></li>';
        $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["ordini_listino_compilabile"].'?id_ordine='.$id_ordine.'" >Scheda Listino</a></li>';
        $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["ordine_log_pubblico"].'?id_ordine='.$id_ordine.'" >Sequenza Accadimenti</a></li>';
        $h_menu .='</ul>';  // di questo ordine
        $h_menu .='</li>';
        }

        $h_menu .='<li><a class="medium green awesome" href="#" target="_self">di altri ordini:</a>';
        $h_menu .='<ul>';
        $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["ordini_aperti_new"].'" target="_self">quelli aperti</a></li>';
        $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["ordini_chiusi"].'" target="_self">quelli chiusi</a></li>';

        $h_menu .='<li><a class="medium green awesome" href="#" target="_self">una Panoramica :</a>';
        $h_menu .='<ul>';
        $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["panoramica"].'" target="_self">a breve periodo</a></li>';
        $h_menu .='<li><a class="medium green awesome" href="'.$RG_addr["panoramica_anno"].'" target="_self">a lungo termine</a></li>';
        $h_menu .='</ul>'; // Panoramica
        $h_menu .='</li>';

        $h_menu .='</ul>';  // di altri ordini
        $h_menu .='</li>';

        $h_menu .='</ul>';  // Visualizza
        $h_menu .='</li>';

        return $h_menu;
    }
    function ordini_menu_oa_esporta($user){
        global $RG_addr;

        $cookie_read     =explode("|", base64_decode($user));
        $permission = $cookie_read[6];


        $h_menu ='<li><a class="medium silver awesome"><b>Esporta</b></a>';
        $h_menu.='<ul>';
        $h_menu .='<li><a class="medium silver awesome" href="'.$RG_addr["oa_export_printable"].'" target="_self">Stampabile</a></li>';
        $h_menu .='<li><a class="medium silver awesome" href="'.$RG_addr["oa_export_pdf"].'" target="_self">Pdf</a></li>';
        $h_menu .='<li><a class="medium silver awesome" href="'.$RG_addr["oa_export_word"].'" target="_self">Word</a></li>';
        $h_menu .='<li><a class="medium silver awesome" href="'.$RG_addr["oa_export_excel"].'" target="_self">Excel</a></li>';
        $h_menu .='<li><a class="medium silver awesome" href="'.$RG_addr["oa_export_csv"].'" target="_self">CSV</a></li>';



        $h_menu .='</ul>';  // esporta
        $h_menu .='</li>';

        return $h_menu;
    }
    function ordini_menu_report($user,$id_ordine=null){
        $cookie_read     =explode("|", base64_decode($user));
        $permission = $cookie_read[6];

        $h_menu ='<li><a class="medium yellow awesome"><b>Report</b></a>';
        $h_menu.='<ul>';

        if(!empty($id_ordine)){
        $h_menu .='<li><a class="medium orange awesome" href="#" target="_self">Mia Spesa</a>';
            $h_menu.='<ul>';
            $h_menu .='<li><a class="medium orange awesome" href="#" target="_self">Dettaglio</a></li>';
            $h_menu .='<li><a class="medium orange awesome" href="#" target="_self">Riepilogo</a></li>';
        $h_menu .='</ul>';  // Mia spesa
        $h_menu .='</li>';
        $h_menu .='<li><a class="medium yellow awesome" href="#" target="_self">Mio Gas</a>';
            $h_menu.='<ul>';
            $h_menu .='<li><a class="medium yellow awesome" href="#" target="_self">Dettaglio</a></li>';
            $h_menu .='<li><a class="medium yellow awesome" href="#" target="_self">Riepilogo</a></li>';
        $h_menu .='</ul>';  // Mia spesa
        $h_menu .='</li>';
        $h_menu .='<li><a class="medium blue awesome" href="#" target="_self">Mio Ordine</a>';
            $h_menu.='<ul>';
            $h_menu .='<li><a class="medium blue awesome" href="#" target="_self">Fornitore (S.Piene)</a></li>';
            $h_menu .='<li><a class="medium blue awesome" href="#" target="_self">Fornitore (Articoli)</a></li>';
            $h_menu .='<li><a class="medium blue awesome" href="#" target="_self">GAS</a></li>';
            $h_menu .='<li><a class="medium blue awesome" href="#" target="_self">Riepilogo</a></li>';
        $h_menu .='</ul>';  // Mia spesa
        $h_menu .='</li>';
        }


        $h_menu .='</ul>';  // Visualizza
        $h_menu .='</li>';

        return $h_menu;
    }
    function ordini_menu_pacco($id_ordine){
    global $RG_addr;
            if(is_numeric($id_ordine)){
                $h_menu ='<li><a href="'.$RG_addr["ordini_form_new"].'?id_ordine='.$id_ordine.'"><img src="'.$RG_addr["img_pacco"].'" height="30" width="30" alt="Scheda ordine" title="Torna alla scheda ordine" style="vertical-align:middle;margin-top:-1em;"></a>';
                $h_menu .='</li>';
            }
        return $h_menu;
    }

    function ordine_menu_gestisci_new($id_user,$id_ordine,$id_gas){

    global $db;
    global $RG_addr;

    $di_chi_e_questo ='<li><a class="awesome medium blue"  href="'.$RG_addr["di_chi_e_questo"].'?id_ordine='.$id_ordine.'" >Di chi ? questo ??</a></li>';
    $aggiungi_referenti='<li><a class="awesome medium blue"  href="'.$RG_addr["ordini_aggiungi_referenti"].'?id_ordine='.$id_ordine.'" >Gestione referenti aggiuntivi</a><li>';


    $rettifica_denaro_totale='<a class="medium blue awesome"  href="'.$RG_addr["rettifica_denaro_totale"].'?id='.$id_ordine.'" >Totale ordine</a>';
    $rettifica_totali='<a class="medium blue awesome"  href="'.$RG_addr["rettifica_totali"].'?id_ordine='.$id_ordine.'" >Totali articoli ordine</a>';
    $rettifica_users='<a class="awesome medium blue"  href="'.$RG_addr["rettifica_users"].'?id='.$id_ordine.'" >Articoli singolo utente</a>';
    $rettifica_denaro='<a class="awesome medium blue"  href="'.$RG_addr["rettifica_denaro"].'?id='.$id_ordine.'" >Importi singoli articoli</a>';
    $rettifica_denaro_user ='<a class="awesome medium blue"  href="'.$RG_addr["rettifica_denaro_user"].'?id_ordine='.$id_ordine.'" >Totale per Utente</a>';
    $rettifica_denaro_user_riga ='<a class="awesome medium silver"  href="'.$RG_addr["rettifica_denaro_user_riga"].'?id_ordine='.$id_ordine.'" >Rettifica Totale Utente (Beta)</a>';
    $rettifica_users_new ='<a class="awesome medium blue"  href="'.$RG_addr["rettifica_quantita_art_user"].'?id_ordine='.$id_ordine.'" >Quantit? articoli singoli utenti</a>';
    $rettifica_denaro_new ='<a class="awesome medium blue"  href="'.$RG_addr["rettifica_denaro_art_user"].'?id_ordine='.$id_ordine.'" >Valore articoli singoli utenti</a>';
    $rettifica_quantita_csv ='<a class="awesome medium blue"  href="'.$RG_addr["rettifica_quantita_csv"].'?id_ordine='.$id_ordine.'" >Rettifica quantit? OFFLINE</a>';
    $rettifica_singoli_valori ='<a class="awesome medium silver"  href="'.$RG_addr["rettifica_singoli_valori"].'?id_ordine='.$id_ordine.'" >Rettifica Singoli valori (Beta)</a>';
    $rettifica_excellike ='<a class="awesome medium silver"  href="'.$RG_addr["rettifica_excellike"].'?id_ordine='.$id_ordine.'" >Rettifica tipo Excel (Betissima)</a>';


    $report_gas = '<a class="medium blue awesome"  href="'.$RG_addr["ordini_gestione_riepgas"].'?id_ordine='.$id_ordine.'" >Situazione GAS</a>';
    $report_dettaglio_articoli = '<a class="medium blue awesome"  href="'.$RG_addr["rep_dettaglio_articoli_c"].'?id='.$id_ordine.'" >Dettaglio Articoli (tutti i gas)</a>';
    $report_riepilogo_articoli = '<a class="medium blue awesome"  href="'.$RG_addr["rep_riepilogo_articoli_c"].'?id='.$id_ordine.'" >Riepilogo Articoli (tutti i gas)</a>';
    $avanzo_ammanco ='<a class="medium blue awesome"  href="'.$RG_addr["rep_avanzo_ammanco"].'?id='.$id_ordine.'" >Avanzo-Ammanco</a>';
    $report_scatole_intere ='<a class="medium blue awesome"  href="'.$RG_addr["rep_scatole_intere"].'?id='.$id_ordine.'" >SOLO Scatole intere</a>';
    $report_note_articolo ='<a class="medium blue awesome"  href="'.$RG_addr["report_note_articolo"].'?id_ordine='.$id_ordine.'" >Note articoli</a>';


    $convalida ='<a class="medium celeste awesome"  href="'.$RG_addr["convalida_ordine"].'?id_ordine='.$id_ordine.'">Convalida</a>';
    $riesuma ='<a class="medium black awesome"  href="'.$RG_addr["ordini_deny_print"].'?id_ordine='.$id_ordine.'">Riesuma</a>';

    $cambia_costi = '<a class="awesome medium blue"  href="'.$RG_addr["edit_costi"].'?id_ordine='.$id_ordine.'">Costi (Generali)</a>';
    $cambia_descrizione ='<a class="awesome medium blue"  href="'.$RG_addr["edit_descrizione"].'?id_ordine='.$id_ordine.'">Descrizioni</a>';
    $cambia_gas_coinvolti ='<a class="awesome medium blue"  href="'.$RG_addr["edit_partecipazione"].'?id_ordine='.$id_ordine.'">Modifica Gas Coinvolti</a>';
    $cambia_date ='<a class="awesome medium blue"  href="'.$RG_addr["edit_scadenze"].'?id_ordine='.$id_ordine.'">Date e scadenze</a>';
    $cambia_listino ='<li><a class="awesome medium blue"  href="'.$RG_addr["edit_switch_listino"].'?id_ordine='.$id_ordine.'" >Cambia listino</a></li>';
        //}


    if (dettagli_ordine($id_ordine)==0){
        $delete_ordine ='<li><a class="medium red awesome" href="'.$RG_addr["delete_ordine"].'?id='.$id_ordine.'" target="_self">Elimina Ordine</a></li>';
    }

    $report_dettaglio_articoli = '<a class="medium blue awesome"  href="'.$RG_addr["rep_dettaglio_articoli_a"].'?id='.$id_ordine.'" >Dettaglio Articoli (tutti i gas)</a>';
    $report_riepilogo_articoli = '<a class="medium blue awesome"  href="'.$RG_addr["rep_riepilogo_articoli_a"].'?id='.$id_ordine.'" >Riepilogo Articoli (tutti i gas)</a>';



    $aiutanti = '<li><a class="awesome medium blue"  href="'.$RG_addr["ordini_aiutanti_table"].'?id_ordine='.$id_ordine.'">Gestione aiuti</a></li>';
    $partecipazione_utenti = '<a class="awesome medium blue"  href="'.$RG_addr["partecipat_utenti"].'?id_ordine='.$id_ordine.'">Totali per utente</a>';
    $cronologia_utenti = '<a class="awesome medium blue"  href="'.$RG_addr["partecipat_cronologia"].'?id_ordine='.$id_ordine.'">Cronologia ordini</a>';
    $stato_gas_partecipanti = '<a class="awesome medium blue"  href="'.$RG_addr["partecipat_gastable"].'?id_ordine='.$id_ordine.'">Partecipazione GAS</a>';
    $cambia_gestore ='<li><a class="awesome medium blue"  href="'.$RG_addr["edit_switch_gestore"].'?id_ordine='.$id_ordine.'" >Cambia Referente</a></li>';
    $pannello_report = '<li><a class="awesome medium blue"  href="'.$RG_addr["ordini_report_panel"].'?id_ordine='.$id_ordine.'" >Pannello Reports</a></li>';


    //ORDINE CHIUSO
    if (stato_from_id_ord($id_ordine)==3){
        if(is_printable_from_id_ord($id_ordine)){

        // ORDINE CHIUSO E CONFERMATO
        $m ='<li><a class="awesome blue medium" href="#">Gest.</a>';  // PRIMO LIVELLO
        $m.='<ul>';

            $m.='<li><a class="awesome blue medium" href="#">GAS</a>';  // SECONDO LIVELLO
            $m.='<ul>';
                $m.='<li>'.$report_gas.'</li>';
            $m.='</ul>';
            $m.='</li>'; //SECONDO LIVELLO

            $m.='<li><a class="awesome blue medium" href="#">Reports</a>';  // SECONDO LIVELLO
            $m.='<ul>';
                  $m .= $pannello_report;
                  $m.='<li>'.$partecipazione_utenti.'</li>';
                  //$m.='<li>'.$report_scatole_intere.'</li>';
                  $m.='<li><a class="awesome blue medium" href="#">Speciali</a>';  // SECONDO LIVELLO
                        $m.='<ul>';
                            $m.='<li>'.$avanzo_ammanco.'</li>';
                        $m.='</ul>';
                  $m.='</li>'; //TERZO LIVELLO
            $m.='</ul>';

            $m.='</li>'; //SECONDO LIVELLO




            $m.='<li><a class="awesome blue medium" href="#">Partecipazione</a>';  // SECONDO LIVELLO
            $m.='<ul>';
                  $m.='<li>'.$cronologia_utenti.'</li>';
                  $m.='<li>'.$stato_gas_partecipanti.'</li>';
            $m.='</ul>';
            $m.='</li>'; //SECONDO LIVELLO

            $m.='<li>'.$riesuma.'</li>';
            $m .= $delete_ordine;
            //$m .= $di_chi_e_questo;
            $m .= $aggiungi_referenti;

        $m.='</ul>';
        $m.='</li>';  //PRIMO LIVELLO

        }else{ // IS PRINTABLE

        // ORDINE CHIUSO DA CONFERMARE
        $m ='<li><a class="awesome blue medium" href="#">Gest.</a>';  // PRIMO LIVELLO
        $m.='<ul>';
            $m.='<li><a class="awesome blue medium" href="#">Modifica</a>';  // SECONDO LIVELLO
            $m.='<ul>';
                  $m.='<li>'.$cambia_costi.'</li>';
                  $m.='<li>'.$cambia_descrizione.'</li>';
                  $m.='<li>'.$cambia_date.'</li>';
                  $m.='<li>'.$convalida.'</li>';
            $m.='</ul>';
            $m.='</li>'; //SECONDO LIVELLO

            $m.='<li><a class="awesome blue medium" href="#">Rettifiche</a>';  // SECONDO LIVELLO
            $m.='<ul>';
                  $m.='<li>'.$rettifica_denaro_totale.'</li>';
                  $m.='<li>'.$rettifica_totali.'</li>';
                  //$m.='<li>'.$rettifica_users.'</li>';
                  $m.='<li>'.$rettifica_users_new.'</li>';
                  $m.='<li>'.$rettifica_quantita_csv.'</li>';
                  //$m.='<li>'.$rettifica_denaro.'</li>';
                  $m.='<li>'.$rettifica_denaro_new.'</li>';
                  $m.='<li>'.$rettifica_denaro_user.'</li>';
                  $m.='<li>--------------------------</li>';
                  $m.='<li>'.$rettifica_singoli_valori.'</li>';
                  $m.='<li>'.$rettifica_denaro_user_riga.'</li>';
                  $m.='<li>'.$rettifica_excellike.'</li>';
            $m.='</ul>';
            $m.='</li>'; //SECONDO LIVELLO

            $m.='<li><a class="awesome blue medium" href="#">GAS</a>';  // SECONDO LIVELLO
            $m.='<ul>';
                  $m.='<li>'.$cambia_gas_coinvolti.'</li>';
                  $m.='<li>'.$report_gas.'</li>';
            $m.='</ul>';
            $m.='</li>'; //SECONDO LIVELLO

            $m.='<li><a class="awesome blue medium" href="#">Reports</a>';  // SECONDO LIVELLO
            $m.='<ul>';
                  $m .= $pannello_report;
                  $m.='<li>'.$partecipazione_utenti.'</li>';
                  $m.='<li>'.$report_note_articolo.'</li>';
                  $m.='<li><a class="awesome blue medium" href="#">Speciali</a>';  // SECONDO LIVELLO
                        $m.='<ul>';
                            $m.='<li>'.$avanzo_ammanco.'</li>';
                        $m.='</ul>';
                  $m.='</li>'; //TERZO LIVELLO
            $m.='</ul>';
            $m.='</li>'; //SECONDO LIVELLO


            $m.='<li><a class="awesome blue medium" href="#">Partecipazione</a>';  // SECONDO LIVELLO
            $m.='<ul>';
                  $m.='<li>'.$cronologia_utenti.'</li>';
                  $m.='<li>'.$stato_gas_partecipanti.'</li>';
            $m.='</ul>';
            $m.='</li>'; //SECONDO LIVELLO


            $m .= $aiutanti;
            $m .= $delete_ordine;         // CANCELLA ORDINE VUOTO
            $m .= $cambia_gestore;
            $m .= $cambia_listino;
            //$m .= $di_chi_e_questo;
            $m .= $aggiungi_referenti;

        $m.='</ul>';
        $m.='</li>';  //PRIMO LIVELLO


        }

    }


    if (stato_from_id_ord($id_ordine)==2){

        // ORDINE APERTO
        $m ='<li><a class="awesome blue medium" href="#">Gest.</a>';  // PRIMO LIVELLO
        $m.='<ul>';
            $m.='<li><a class="awesome blue medium" href="#">Modifica</a>';  // SECONDO LIVELLO
            $m.='<ul>';
                  $m.='<li>'.$cambia_costi.'</li>';
                  $m.='<li>'.$cambia_descrizione.'</li>';
                  $m.='<li>'.$cambia_date.'</li>';

            $m.='</ul>';
            $m.='</li>'; //SECONDO LIVELLO


            $m.='<li><a class="awesome blue medium" href="#">GAS</a>';  // SECONDO LIVELLO
            $m.='<ul>';
                  $m.='<li>'.$cambia_gas_coinvolti.'</li>';
                  $m.='<li>'.$report_gas.'</li>';
            $m.='</ul>';
            $m.='</li>'; //SECONDO LIVELLO

            $m.='<li><a class="awesome blue medium" href="#">Reports</a>';  // SECONDO LIVELLO
            $m.='<ul>';
                  $m .= $pannello_report;
                  $m.='<li>'.$partecipazione_utenti.'</li>';
                  $m.='<li>'.$report_note_articolo.'</li>';
                  $m.='<li><a class="awesome blue medium" href="#">Speciali</a>';  // SECONDO LIVELLO
                        $m.='<ul>';
                            $m.='<li>'.$avanzo_ammanco.'</li>';
                        $m.='</ul>';
                  $m.='</li>'; //TERZO LIVELLO

            $m.='</ul>';
            $m.='</li>'; //SECONDO LIVELLO



            $m.='<li><a class="awesome blue medium" href="#">Partecipazione</a>';  // SECONDO LIVELLO
            $m.='<ul>';
                  $m.='<li>'.$cronologia_utenti.'</li>';
                  $m.='<li>'.$stato_gas_partecipanti.'</li>';
            $m.='</ul>';
            $m.='</li>'; //SECONDO LIVELLO


            $m .= $aiutanti;
            $m .= $delete_ordine;         // CANCELLA ORDINE VUOTO
            $m .= $cambia_gestore;
            $m .= $cambia_listino;
            //$m .= $di_chi_e_questo;
            $m .= $aggiungi_referenti;

        $m.='</ul>';
        $m.='</li>';  //PRIMO LIVELLO
    }

        if (stato_from_id_ord($id_ordine)==1){

        // ORDINE FUTURO
        $m ='<li><a class="awesome blue medium" href="#">Gestisci</a>';  // PRIMO LIVELLO
        $m.='<ul>';
            $m.='<li><a class="awesome blue medium" href="#">Modifica</a>';  // SECONDO LIVELLO
            $m.='<ul>';
                  $m.='<li>'.$cambia_costi.'</li>';
                  $m.='<li>'.$cambia_descrizione.'</li>';
                  $m.='<li>'.$cambia_date.'</li>';

            $m.='</ul>';
            $m.='</li>'; //SECONDO LIVELLO


            $m.='<li><a class="awesome blue medium" href="#">GAS</a>';  // SECONDO LIVELLO
            $m.='<ul>';
                  $m.='<li>'.$cambia_gas_coinvolti.'</li>';

            $m.='</ul>';
            $m.='</li>'; //SECONDO LIVELLO


            $m .= $aiutanti;
            $m .= $delete_ordine;         // CANCELLA ORDINE VUOTO
            $m .= $cambia_gestore;
            $m .= $cambia_listino;
            //$m .= $di_chi_e_questo;
            $m .= $aggiungi_referenti;

        $m.='</ul>';
        $m.='</li>';  //PRIMO LIVELLO
    }


    //if(id_referente_ordine_globale($id_ordine)==$id_user){
    //    return $m;
    //}else{
    //    return;
    //}

    if(posso_gestire_ordine_full($id_ordine,$id_user)){
        return $m;
    }else{
        return;
    }

    }
    function ordine_menu_cassa($id_user,$id_ordine,$id_gas){

    global $db;
    global $RG_addr;
    $permission = _USER_PERMISSIONS;

    if($permission & perm::puo_operare_con_crediti){$ok_crediti = true;}
    if($permission & perm::puo_gestire_la_cassa){$ok_cassiere = true;}
    if($id_gas=id_gas_user(id_referente_ordine_globale($id_ordine))){$ok_mio_gas = true;}
    if(id_referente_ordine_proprio_gas($id_ordine,$id_gas)==$id_user){$ok_gestore = true;}

    $paga_fornitore = '<a class="awesome medium blue"  href="'.$RG_addr["cassa_paga_ditta"].'?id_ordine='.$id_ordine.'">Anticipa al fornitore</a>';
    $scala_crediti_utenti = '<a class="awesome medium celeste"  href="'.$RG_addr["cassa_ordini_sit_ut"].'?id_ordine='.$id_ordine.'">Scala crediti utenti</a>';
    $registra_movimenti = '<a class="awesome medium celeste"  href="'.$RG_addr["cassa_movimenti_reg"].'?id_ordine='.$id_ordine.'">Registra Movimenti</a>';
    $contabilizza_movimenti = '<a class="awesome medium celeste "  href="'.$RG_addr["cassa_movimenti_con"].'?id_ordine='.$id_ordine.'">Contabilizza Movimenti</a>';
    $tutti_i_movimenti = '<a class="awesome medium celeste"  href="'.$RG_addr["cassa_ordini_tutti_mov"].'?id_ordine='.$id_ordine.'">Visualizza TUTTI Movimenti</a>';
    $elimina_tutti_i_movimenti = '<a class="awesome medium red"  href="'.$RG_addr["cassa_ordini_del_mov"].'?id_ordine='.$id_ordine.'">Elimina TUTTI i Movimenti di questo ordine</a>';
    $valori_ordine='<a class="awesome medium beige"  href="'.$RG_addr["cassa_valori_ordine"].'?id_ordine='.$id_ordine.'">Visualizza gli importi di tutti gli utenti</a>';;

    if(is_printable_from_id_ord($id_ordine)){
        //SE PUo' operare con i crediti
        if($ok_crediti){
            //Se ? un cassiere oppure un gestore
            if($ok_cassiere || $ok_gestore){
            $m ='<li><a class="awesome celeste medium" href="#">Cassa</a>';  // PRIMO LIVELLO
            $m.='<ul>';
                //Se ? cassiere
                if($ok_cassiere){
                    //Se ? cassiere dello stesso gas del referente ordine
                    if($ok_mio_gas){
                        $m.='<li><a class="awesome celeste medium" href="#">Come cassiere:</a>';  // SECONDO LIVELLO
                        $m.='<ul>';
                              $m.='<li>'.$valori_ordine.'</li>';
                              //$m.='<li>'.$paga_fornitore.'</li>';
                              $m.='<li>'.$scala_crediti_utenti.'</li>';
                              $m.='<li>'.$registra_movimenti.'</li>';
                              $m.='<li>'.$contabilizza_movimenti.'</li>';
                              $m.='<li>'.$tutti_i_movimenti.'</li>';
                              $m.='<li>'.$elimina_tutti_i_movimenti.'</li>';
                        $m.='</ul>';
                        $m.='</li>'; //SECONDO LIVELLO
                    }
                }
                //se ? gestore - Tolto
                if($ok_gestore){
                    $m2.='<li><a class="awesome celeste medium" href="#">Come Gestore:</a>';  // SECONDO LIVELLO
                    $m2.='<ul>';
                          $m2.='<li>'.$scala_crediti_utenti.'</li>';
                          $m2.='<li>'.$registra_movimenti.'</li>';
                    $m2.='</ul>';
                    $m2.='</li>'; //SECONDO LIVELLO
                }
            $m.='</ul>';
            $m.='</li>';  //PRIMO LIVELLO
            }
        }
    }
    return $m;

    }
    function ordine_menu_gas($id_user,$id_ordine,$id_gas){

    global $db;
    global $RG_addr;
    global $class_debug;
    //$class_debug->debug_msg[]="MENU- IL MIO GAS : id_user = $id_user, id_ordine = $id_ordine, id_gas = $id_gas";


    $vicini_di_casa ='<li><a class="awesome medium yellow" href="'.$RG_addr["gas_miei_vicini"].'?id_ordine='.$id_ordine.'">Miei Vicini di ordine</a></li>';
    $report_articoli_gas = '<li><a class="awesome medium yellow" href="'.$RG_addr["gas_ordine_riepilogo"].'?id_ordine='.$id_ordine.'">Riepilogo Articoli GAS</a></li>';
    $ok="NO";
    $ref="NO";

    if(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini){
        $ok = "OK";
    }

    if(read_option_gas_text_new($id_gas,"_GAS_VISIONE_CONDIVISA")=="SI"){
        $ok = "OK";
    }
    if(id_referente_ordine_proprio_gas($id_ordine,$id_gas)==$id_user){
        $ok = "OK";
        $ref = "OK";
    }

    //SE E' UN AIUTO EXTRA
    if(check_option_referente_extra($id_ordine,$id_user)>0){
        $ok = "OK";
    }

    if($ok=="OK"){


        $report_utenti_gas = '<li><a class="awesome medium yellow" href="'.$RG_addr["gas_ordine_riep_users"].'?id_ordine='.$id_ordine.'">Riepilogo Utenti GAS</a></li>';
        $report_dettaglio_utenti_gas ='<li><a class="awesome medium yellow" href="'.$RG_addr["gas_ordine_dett_users"].'?id_ordine='.$id_ordine.'">Dettaglio Utenti GAS</a></li>';


    }
    if($ref=="OK"){
        //DA RIMETTERE QUANDO E' TUTTO OK'
        $cambia_spese_gas = '<li><a class="awesome medium yellow" href="'.$RG_addr["edit_spese_gas"].'?id_ordine='.$id_ordine.'">Costi</a></li>';
        $cambia_distribuzione = '<li><a class="awesome medium yellow" href="'.$RG_addr["edit_distribuzione_gas"].'?id_ordine='.$id_ordine.'">Distribuzione</a></li>';
        $report_note_ordine ='<li><a class="awesome medium yellow" href="'.$RG_addr["report_riepilogo_note"].'?id_ordine='.$id_ordine.'">Note Utenti GAS</a></li>';

    }


    $m ='<li><a class="awesome yellow medium" href="#">Gas</a>';  // PRIMO LIVELLO
    $m.='<ul>'
      .$vicini_di_casa
      .$cambia_spese_gas
      .$cambia_distribuzione
      .$report_dettaglio_gas
      .$report_articoli_gas
      .$report_utenti_gas
      .$report_dettaglio_utenti_gas
      .$report_note_ordine
    .'</ul>';
    $m.='</li>';  //PRIMO LIVELLO





    return $m;

    }
    function ordine_menu_mia_spesa($id_user,$id_ordine){

    global $db;
    global $RG_addr;


    if(n_articoli_ordini_user($id_user,$id_ordine)>0){

     $riepilogo_amici = '<li><a class="medium orange awesome" href="'.$RG_addr["ordini_mia_spesa_riepami"].'?id_ordine='.$id_ordine.'" >Riepilogo amici</a></li>';
     $dettaglio_articoli_nuovo = '<li><a class="medium orange awesome" href="'.$RG_addr["ordini_mia_spesa_dettaglio"].'?id_ordine='.$id_ordine.'" >Dettaglio Assegnazioni</a></li>';
     $riepilogo_articoli_mia_spesa_nuovo = '<li><a class="medium orange awesome" href="'.$RG_addr["ordini_mia_spesa_riepilogo"].'?id_ordine='.$id_ordine.'" >Riepilogo articoli</a></li>';

     if(stato_from_id_ord($id_ordine)==2){
        $cancella_spesa = '<li><a class="medium black awesome" href="'.$RG_addr["ordini_del_all_art"].'?id_ordine='.$id_ordine.'" >Cancella la mia spesa</a></li>';
     }

    $m ='<li><a class="awesome orange medium" href="#">La mia spesa</a>';  // PRIMO LIVELLO
    $m.='<ul>'
      .$dettaglio_articoli_nuovo
      .$riepilogo_articoli_mia_spesa_nuovo
      .$riepilogo_amici
      .$cancella_spesa
    .'</ul>';
    $m.='</li>';  //PRIMO LIVELLO

    }




    return $m;

    }
    function ordine_menu_operazioni_base($id_user,$id_ordine){
    global $RG_addr;




        $proprio_referente =  id_referente_ordine_proprio_gas($id_ordine,id_gas_user($id_user));
        $is_prenotato = read_option_prenotazione_ordine($id_ordine,$id_user);


        if ($proprio_referente==0){

            if(_GAS_PUO_PART_ORD_EST){
                $partecipa .="<a id=\"blink_me\" class=\"medium beige awesome\" href=\"".$RG_addr["ordine_diventa_referente"]."?id_ordine=$id_ordine\"><b>Diventa referente</b></a>";
            }

        }else{
                $partecipa .="<a class=\"medium beige awesome\" href=\"".$RG_addr["ordine_partecipa"]."?id=$id_ordine#istruzioni\"><b>Acquistando merce</b></a>";
                $massivo .="<a class=\"medium beige awesome\" href=\"".$RG_addr["ordine_partecipa_massivo"]."?id_ordine=$id_ordine\">Gestendo molti amici</a>";


                //SE IL CASSIERE PERMETTE LA PRENOTAZIONE
                if (read_option_gas_text(_USER_ID_GAS,"_GAS_CASSA_PRENOTAZIONE_ORDINI")){
                    //SE NON CI SONO ANCORA ARTICOLI ORDINATI PUO' PRENOTARE
                    if(n_articoli_ordini_user($id_user,$id_ordine)==0){
                        $prenota .="<a class=\"medium beige awesome\" href=\"".$RG_addr["prenota_attiva"]."?id_ordine=$id_ordine\">Prenotando articoli</a>";
                    //SE CI SONO GIA' ARTICOLI
                    }else{
                        //SE ESISTE UNA PRENOTAZIONE
                        if($is_prenotato=="SI"){
                            $conferma .="<a class=\"medium beige awesome\" href=\"".$RG_addr["prenota_conferma"]."?id_ordine=$id_ordine\">Confermando la prenotazione</a>";
                        }else{
                            //SE NON C'E' LA PREONTAZIONE
                            $prenota .="";
                        }
                    }
                }else{
                    $prenota .="";
                }


        }



        //SE l'ordine ? solo per chi usa la cassa
        if(_GAS_USA_CASSA){
            if(ordini_field_value($id_ordine,"solo_cassati")=="SI"){
                if(!_USER_USA_CASSA){
                    $partecipa="";
                    $prenota .="";
                    $massivo = "";
                    }
                }
        }

       if($is_prenotato=="SI"){
           //SE ESISTE UNA PRENOTAZIONE
           //NON SI PUO' PRENOTARE MASSIVO
           $massivo = "";
           //E NEMMENO RIPRENOTARE
           $prenota = "";
       }

       if(check_option_aiuto_ordine($id_ordine,_USER_ID)==0){
            $offerta_aiuto="<a class=\"awesome beige medium\" href=\"".$RG_addr["aiutanti_offerta_form"]."?id_ordine=$id_ordine\">Offrendo un aiuto !</a>";

       }else{
            $offerta_aiuto="";
       }

       if($partecipa<>""){
           $testo_pulsantone = read_option_gas_text_new(_USER_ID_GAS,"_PULSANTE_PARTECIPA");
           if($testo_pulsantone<>""){
              $pulsantone = '<li><a class="large beige awesome" href="'.$RG_addr["ordine_partecipa"].'?id='.$id_ordine.'#istruzioni"><strong>'.$testo_pulsantone.'</strong></a></li>';
           }
       }

            $h_menu .= $pulsantone;
            $h_menu .='<li><a class="medium beige awesome">PARTECIPA</a>';
                $h_menu .='<ul>';
                    $h_menu .='<li>'.$partecipa.'</li>';
                    $h_menu .='<li>'.$massivo.'</li>';
                    $h_menu .='<li>'.$prenota.'</li>';
                    $h_menu .='<li>'.$conferma.'</li>';
                    $h_menu .='<li>'.$offerta_aiuto.'</li>';
                $h_menu .='</ul>';
            $h_menu .='</li>';


        if(ordine_partecipabile($id_ordine)){
            //TUTTO OK, UTENTE NORMALE
            return $h_menu;
            die();
        }else{

            // USER CHE PUO' VEDERE TUTTI GLI ORDINI
            if(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini){
                //ORDINE CHIUSO ?
                if(stato_from_id_ord($id_ordine)==3){
                    // ORDINE NON STAMPABILE
                    if(!is_printable_from_id_ord($id_ordine)){
                        //ESISTE IL REFERENTE
                        if(id_referente_ordine_proprio_gas($id_ordine,_USER_ID_GAS)>0){


                                 return $h_menu;
                                 die();
                        }
                    }
                }
            }
        }

}
    function ordine_menu_comunica($id_user,$id,$id_gas){
    global $RG_addr;
         // ------------------------------------------------MIE COMUNICAZIONI
    $show=false;

            if(n_articoli_ordini_user($id_user,$id)>0){
                 if (id_referente_ordine_proprio_gas($id,id_gas_user($id_user))<>$id_user){
                    //$com1 ='<li><a class="medium magenta awesome" href="'.$RG_addr["ordini_comunica"].'?id='.$id.'&mail_type=2" target="_self">Al mio referente GAS</a></li>';
                    $com1_b = '<li><a class="medium magenta awesome" href="'.$RG_addr["comunica_referente_proprio_gas"].'?id_ordine='.$id.'">Al mio referente GAS (Beta)</a></li>';
                    $com_vicini ='<li><a class="medium magenta awesome" href="'.$RG_addr["gas_miei_vicini"].'?id_ordine='.$id.'" target="_self">Vicini di casa</a></li>';
                    $com8 ='<li><a class="medium magenta awesome" href="'.$RG_addr["opinione_partecipante"].'?id_ordine='.$id.'" target="_self">Recensisci il fornitore</a></li>';
                    $show = true;
                 }else{
                    $com8 ='<li><a class="medium magenta awesome" href="'.$RG_addr["opinione_partecipante"].'?id_ordine='.$id.'" target="_self">Recensisci il fornitore</a></li>';
                    $show = true;
                 }
            } // SE HO IN CORSO L?ORDINE

                // Se sono il referente del MIO GAS posso vedere il referente ordine
                if (id_referente_ordine_proprio_gas($id,id_gas_user($id_user))==$id_user){
                // ma solo se non sono io il referente ordine
                    if (id_referente_ordine_globale($id)<>$id_user){

                        $com2 ='<li><a class="medium magenta awesome" href="'.$RG_addr["ordini_comunica"].'?id='.$id.'&mail_type=1" target="_self">Al referente Ordine</a></li>';
                        $com2_b ='<li><a class="medium magenta awesome" href="'.$RG_addr["comunica_referente_ordine"].'?id_ordine='.$id.'" target="_self">Al referente Ordine (Beta)</a></li>';
                        $com3 ='<li><a class="medium magenta awesome" href="'.$RG_addr["ordini_comunica"].'?id='.$id.'&mail_type=3" target="_self">A coloro i quali hanno comprato qualcosa, nel mio GAS</a></li>';
                        $com3_b ='<li><a class="medium magenta awesome" href="'.$RG_addr["comunica_partecipanti_mio_gas"].'?id_ordine='.$id.'" target="_self">A coloro i quali hanno comprato qualcosa, nel mio GAS (beta)</a></li>';
                        $show = true;
                    }

                }

                // Se invece sono il referente ORDINE
                 if (posso_gestire_ordine_full($id,$id_user)){
                    //$com4 ='<li><a class="medium magenta awesome" href="'.$RG_addr["ordini_comunica"].'?id='.$id.'&mail_type=5" target="_self">Ai referenti GAS</a></li>';
                    $com4_b ='<li><a class="medium magenta awesome" href="'.$RG_addr["comunica_referenti_gas"].'?id_ordine='.$id.'" target="_self">Ai referenti GAS</a></li>';
                    //$com5 ='<li><a class="medium magenta awesome" href="'.$RG_addr["ordini_comunica"].'?id='.$id.'&mail_type=3" target="_self">A coloro i quali hanno comprato qualcosa (mio GAS)</a></li>';
                    $com5_b ='<li><a class="medium magenta awesome" href="'.$RG_addr["comunica_partecipanti_mio_gas"].'?id_ordine='.$id.'">A coloro i quali hanno comprato qualcosa (mio GAS) - BETA</a></li>';
                    $com11 ='<li><a class="medium magenta awesome" href="'.$RG_addr["comunica_alcuni_articoli"].'?id_ordine='.$id.'">A coloro i quali hanno comprato specifici articoli</a></li>';
                    $com12 ='<li><a class="medium magenta awesome" href="'.$RG_addr["comunica_alcuni_partecipanti"].'?id_ordine='.$id.'">Ad alcuni partecipanti</a></li>';

                    $com6 ='<li><a class="medium magenta awesome" href="'.$RG_addr["ordini_comunica"].'?id='.$id.'&mail_type=4" target="_self">A coloro i quali hanno comprato qualcosa, di tutti i GAS</a></li>';
                    $com7 ='<li><a class="medium magenta awesome" href="'.$RG_addr["ordini_comunica"].'?id='.$id.'&mail_type=6" target="_self">Bacino utenti potenziali</a></li>';
                    $com9 ='<li><a class="medium magenta awesome" href="'.$RG_addr["opinione_referente"].'?id_ordine='.$id.'" target="_self">Recensisci il fornitore come gestore</a></li>';

                    $show = true;
                 }





if($show){
    $h_menu .='<li><a class="medium magenta awesome">Comunica</a>';
    $h_menu .='<ul>';
    $h_menu .=$com_vicini;
    //$h_menu .=$com1;
    $h_menu .=$com1_b;
    $h_menu .=$com2_b;
    $h_menu .=$com3_b;
    //$h_menu .=$com4;
    $h_menu .=$com4_b;
    //$h_menu .=$com5;
    $h_menu .=$com5_b;
    $h_menu .=$com6;
    $h_menu .=$com7;
    $h_menu .=$com11;
    $h_menu .=$com12;

    //$h_menu .=$com8;
    //$h_menu .=$com9;
    $h_menu .='</ul>';
    $h_menu .='</li>';
}
// ------------------------------------------------FINE MIE COMUNICAZIONI


return $h_menu;

}
    function ordine_menu_extra($id_ordine){
        global $RG_addr;
            $elimina_ordine ='<li><a class="awesome medium red" href="'.$RG_addr["ordini_form_delete_all"].'?id_ordine='.$id_ordine.'">ELIMINA questo ordine</a></li>';
            $report_dettaglio_gas = '<li><a class="awesome medium black" href="'.$RG_addr["gas_ordine_dett_users"].'?id_ordine='.$id_ordine.'">Dettaglio Articoli GAS</a></li>';
            $report_articoli_gas = '<li><a class="awesome medium black" href="'.$RG_addr["gas_ordine_riepilogo"].'?id_ordine='.$id_ordine.'">Riepilogo GAS</a></li>';
            $report_gas = '<li><a class="medium black awesome"  href="'.$RG_addr["rep_situazione_gas"].'?id='.$id_ordine.'" >Situazione tutti GAS</a></li>';
            $report_dettaglio_articoli = '<li><a class="medium black awesome"  href="'.$RG_addr["rep_dettaglio_articoli_c"].'?id='.$id_ordine.'" >Dettaglio Articoli (tutti i gas)</a></li>';
            $report_riepilogo_articoli = '<li><a class="medium black awesome"  href="'.$RG_addr["rep_riepilogo_articoli_c"].'?id='.$id_ordine.'" >Riepilogo Articoli (tutti i gas)</a></li>';
            $report_scatole_intere ='<li><a class="medium black awesome"  href="'.$RG_addr["rep_scatole_intere"].'?id='.$id_ordine.'" >SOLO Scatole intere</a></li>';
            $partecipazione_utenti = '<li><a class="awesome medium blue"  href="'.$RG_addr["partecipat_utenti"].'?id_ordine='.$id_ordine.'">Lista Partecipanti</a></li>';
            $cronologia_utenti = '<li><a class="awesome medium blue"  href="'.$RG_addr["partecipat_cronologia"].'?id_ordine='.$id_ordine.'">Cronologia ordini</a></li>';
            $avanzo_ammanco = '<li><a class="awesome medium blue"  href="'.$RG_addr["rep_avanzo_ammanco"].'?id='.$id_ordine.'">Avanzo ammanco (Oleggio)</a></li>';
            $dashboard ='<li><a class="awesome medium black"  href="'.$RG_addr["extra_dashboard"].'?id_ordine='.$id_ordine.'">Pannello reports</a></li>';

        $h_menu ='<li><a class="medium black awesome"><b>EXTRA</b></a>';
        $h_menu .='<ul>';

        $h_menu .= $dashboard;
        //$h_menu .= $report_dettaglio_gas;
        //$h_menu .= $report_articoli_gas;
        //$h_menu .= $report_gas;
        //$h_menu .= $report_dettaglio_articoli;
        //$h_menu .= $report_riepilogo_articoli;
        //$h_menu .= $report_scatole_intere;
        $h_menu .= $partecipazione_utenti;
        $h_menu .= $cronologia_utenti;
        $h_menu .= $avanzo_ammanco;
        $h_menu .='<li></li>';
        $h_menu .= $elimina_ordine;
        $h_menu .='<li></li>';
        $h_menu .='</ul>';
        $h_menu .='</li>';


        if(_USER_ID_GAS==id_gas_user(id_referente_ordine_globale($id_ordine))){
            if(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini){
                return $h_menu;
            }
        };
        if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
                return $h_menu;
        }

    }
    function ordine_menu_feedback($id_ordine){
    global $RG_addr;
         // ------------------------------------------------MIE COMUNICAZIONI
    $show=false;


    //SE l'ordine ? chiuso e convalidato
    if(stato_from_id_ord($id_ordine)==3){
        // Se invece sono il referente ORDINE
         if (id_referente_ordine_globale($id_ordine)==_USER_ID){

            $com9 ='<li><a class="medium amaranto awesome" href="'.$RG_addr["opinione_referente"].'?id_ordine='.$id_ordine.'" target="_self">Recensisci il fornitore come gestore</a></li>';
            $show = true;
         }

         if(n_articoli_ordini_user(_USER_ID,$id_ordine)>0){
            $com8 ='<li><a class="medium amaranto awesome" href="'.$RG_addr["opinione_partecipante"].'?id_ordine='.$id_ordine.'" target="_self">Recensisci il fornitore come partecipante</a></li>';
            $show = true;
         }
     }



if($show){
    $h_menu .='<li><a class="medium amaranto awesome">FeedBack</a>';
    $h_menu .='<ul>';
    $h_menu .=$com8;
    $h_menu .=$com9;
    $h_menu .='</ul>';
    $h_menu .='</li>';
}
// ------------------------------------------------FINE MIE COMUNICAZIONI


return $h_menu;

}


    function ordini_menu_nuovo($user){
        global $RG_addr;
        $cookie_read     =explode("|", base64_decode($user));
        $permission = $cookie_read[6];
        if($permission & perm::puo_creare_ordini){
            $h_menu ='<li><a class="medium beige awesome"><b>Nuovo ordine</b></a>';
            $h_menu .='<ul>';
            $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["nuovo_ordine_simple"].'" target="_self">scheda Veloce</a></li>';
            $h_menu .='<li><a class="medium beige awesome" href="'.$RG_addr["nuovo_ordine_completo"].'" target="_self">scheda Completa</a></li>';
            $h_menu .='</ul>';
            $h_menu .='</li>';
        }
        return $h_menu;
    }
    function ordini_menu_completo($user,$id_ordine=null,$id_gas=null,$is_exportable=null,$location=null){
        global $RG_addr;
        $cookie_read     =explode("|", base64_decode($user));
        $permission = $cookie_read[6];
        $id_user = $cookie_read[0];

        //echo " MENU COMPLETO $id_ordine = id ordine, $id_gas = id gas<br>";
        $mio_menu[] = ordini_menu_visualizza($user,$id_ordine);
        $mio_menu[] = ordini_menu_nuovo($user);


        // Se ? una scheda riferita ad un ordine
        if(isset($id_ordine)){
            $mio_menu[] = ordini_menu_gestisci_new($user,$id_ordine,$id_gas);
            //$mio_menu[] = ordini_menu_report($user,$id_ordine,$id_gas);
            $mio_menu[]='<li><a class="medium silver awesome" href="'.$RG_addr["pannello_report"].'?id_ordine='.$id_ordine.'"><b>Report</b></a><li>';

            if(n_articoli_ordini_user($id_user,$id_ordine)>0){
                $h_menu ='<li><a class="medium orange awesome"><b>La mia Spesa</b></a>';
                $h_menu .='<ul>';
                     if(stato_from_id_ord($id_ordine)==2){
                        $h_menu .='<li><a class="medium orange awesome" href="'.$RG_addr["oa_dett_ass"].'?id='.$id_ordine.'" target="_self">Dettaglio Assegnazioni</a></li>';
                     }
                     if(stato_from_id_ord($id_ordine)==3){
                        $h_menu .='<li><a class="medium orange awesome" href="'.$RG_addr["oc_dett_ass"].'?id='.$id_ordine.'" target="_self">Dettaglio Assegnazioni</a></li>';
                     }

                $h_menu .='</ul>';
                $h_menu .='</li>';
            }

            $mio_menu[]=$h_menu;




        }
        if(isset($is_exportable)){
            //echo "SONO ESPORTABILE con location : $location";
            $mio_menu[] = ordini_menu_esporta($user,$id_ordine,$location);
        }


        return $mio_menu;
    };
    function ordini_menu_all($id_ordine=null){

        $m = array();
        $m[] = ordini_menu_visualizza(null,$id_ordine);
        $m[] = ordine_menu_operazioni_base(_USER_ID,$id_ordine);
        $m[] = ordine_menu_mia_spesa(_USER_ID,$id_ordine);
        $m[] = ordine_menu_gas(_USER_ID,$id_ordine,_USER_ID_GAS);
        $m[] = ordine_menu_gestisci_new(_USER_ID,$id_ordine,_USER_ID_GAS);
        $m[] = ordine_menu_cassa(_USER_ID,$id_ordine,_USER_ID_GAS);
        $m[] = ordine_menu_comunica(_USER_ID,$id_ordine,_USER_ID_GAS);
        $m[] = ordine_menu_feedback($id_ordine);
        $m[] = ordine_menu_extra($id_ordine);
        return $m;
    }