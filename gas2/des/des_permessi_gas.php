<?php


// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
}

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_vedere_retegas)){
     pussa_via();
}

if($do=="change_pcg"){

    $id_gas = CAST_TO_INT($id_gas);

    if($id_gas>0){
        if(read_option_gas_text_new($id_gas,"_GAS_PUO_CREARE_GAS")<>"NO"){

            write_option_gas_text_new($id_gas,"_GAS_PUO_CREARE_GAS","NO");
        }else{
            write_option_gas_text_new($id_gas,"_GAS_PUO_CREARE_GAS","SI");
        }
    }

}
if($do=="change_lds"){

    $id_gas = CAST_TO_INT($id_gas);

    if($id_gas>0){
        if(read_option_gas_text_new($id_gas,"_GAS_PUO_SCEGLIERE_POLITICA_ORDINI")<>"NO"){

            write_option_gas_text_new($id_gas,"_GAS_PUO_SCEGLIERE_POLITICA_ORDINI","NO");
        }else{
            write_option_gas_text_new($id_gas,"_GAS_PUO_SCEGLIERE_POLITICA_ORDINI","SI");
        }
    }

}

if($do=="change_poe"){
    $id_gas = CAST_TO_INT($id_gas);
    if($id_gas>0){
        if(read_option_gas_text_new($id_gas,"_GAS_PUO_PART_ORD_EST")<>"NO"){
            write_option_gas_text_new($id_gas,"_GAS_PUO_PART_ORD_EST","NO");
        }else{
            write_option_gas_text_new($id_gas,"_GAS_PUO_PART_ORD_EST","SI");
        }

    }

}

if($do=="change_coe"){
    $id_gas = CAST_TO_INT($id_gas);
    if($id_gas>0){
        if(read_option_gas_text_new($id_gas,"_GAS_PUO_COND_ORD_EST")<>"NO"){
            write_option_gas_text_new($id_gas,"_GAS_PUO_COND_ORD_EST","NO");
        }else{
            write_option_gas_text_new($id_gas,"_GAS_PUO_COND_ORD_EST","SI");
        }

    }

}

if($do=="max_plus"){
    $id_gas = CAST_TO_INT($id_gas);
    if($id_gas>0){

        $act = read_option_max_giorni_gas($id_gas);
        $act = $act + 30;
        write_option_max_giorni_gas($id_gas,$act);

    }

}

if($do=="max_minus"){
    $id_gas = CAST_TO_INT($id_gas);
    if($id_gas>0){

        $act = read_option_max_giorni_gas($id_gas,"_MAX_GIORNI_GAS");
        $act = $act - 30;
        if($act<0){$act=0;}

        write_option_max_giorni_gas($id_gas,$act);

    }

}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::des;
//Assegno il titolo che compare nella barra delle info
$r->title = "Gestione GAS";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = des_menu_completo(_USER_ID);

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");





if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h .= " <div class=\"rg_widget rg_widget_helper\">
                <h3>Permessi dei gas di "._USER_DES_NAME."</h3>
                <table id=\"output_1\">

                <thead>

                <tr>
                <th>Descrizione</th>

                <th>Utenti</th>
                <th>Gestori DES</th>
                <th>Può riprodursi ?</th>
                <th>Può Partecipare ad ordini condivisi ?</th>
                <th>Può aprire ordini condivisi ?</th>
                <th>Può decidere da solo ?</th>
                <th>Giorni max sospensione</th>
                </tr>

                 <thead>

                 <tbody>";

       //$o1 =   $db->sql_query("SELECT id_gas FROM maaking_users WHERE userid = ". $id_user );

       $result = $db->sql_query("SELECT * FROM retegas_gas WHERE id_des = "._USER_ID_DES.";");



       //$outp = mysql_fetch_row($o1);

       $riga=0;

         while ($row = $db->sql_fetchrow($result)){

         $riga++;


              //Creazione GAS
              if(read_option_gas_text_new($row["id_gas"],"_GAS_PUO_CREARE_GAS")<>"NO"){
                    $pcg="SI";
              }else{
                    $pcg="<span style=\"color:red;\">NO</span>";

                }
              $pcg_cambia = "<a class=\"awesome silver small\" href=\"?do=change_pcg&id_gas=".$row["id_gas"]."\">Cambia</a>";

              //LIBERTA' DI SCELTA
              if(read_option_gas_text_new($row["id_gas"],"_GAS_PUO_SCEGLIERE_POLITICA_ORDINI")<>"NO"){
                    $lds="SI";
              }else{
                    $lds="<span style=\"color:red;\">NO</span>";

                }
              $lds_cambia = "<a class=\"awesome silver small\" href=\"?do=change_lds&id_gas=".$row["id_gas"]."\">Cambia</a>";

              //PARTECIPARE ORDINI ESTERNI
              if(read_option_gas_text_new($row["id_gas"],"_GAS_PUO_PART_ORD_EST")<>"NO"){
                    $poe="SI";
              }else{
                    $poe="<span style=\"color:red;\">NO</span>";

                }
              $poe_cambia = "<a class=\"awesome silver small\" href=\"?do=change_poe&id_gas=".$row["id_gas"]."\">Cambia</a>";

              //CONDIVIDERE ORDINI ESTERNI
              if(read_option_gas_text_new($row["id_gas"],"_GAS_PUO_COND_ORD_EST")<>"NO"){
                    $coe="SI";
              }else{
                    $coe="<span style=\"color:red;\">NO</span>";

                }
              $coe_cambia = "<a class=\"awesome silver small\" href=\"?do=change_coe&id_gas=".$row["id_gas"]."\">Cambia</a>";




              $idgas = $row["id_gas"];
              $descrizionegas = $row['descrizione_gas'];
              $sedegas = $row['sede_gas'];
              $nomegas = $row['nome_gas'];
              $websitegas = $row['website_gas'];
              $mailgas = $row['mail_gas'];
              $n_ute= gas_n_user($idgas);
              $utenti_des = utenti_gestori_des($idgas);


              $max_cambia_plus = "<a class=\"awesome green option\" href=\"?do=max_plus&id_gas=$idgas\">+</a>";
              $max_cambia_minus = "<a class=\"awesome red option\" href=\"?do=max_minus&id_gas=$idgas\">-</a>";
              $max = read_option_max_giorni_gas($idgas);

              if($max==0){$max="0 <span class=\"small_link\">(Illimitato)</span>";}

              $h.= "<tr>";
              $h.= "<td $col_2>$descrizionegas</td>";
                    $h.="
                         <td $col_7>$n_ute</td>
                         <td $col_7>$utenti_des</td>
                         <td><strong>$pcg</strong> $pcg_cambia</td>
                         <td><strong>$poe</strong> $poe_cambia</td>
                         <td><strong>$coe</strong> $coe_cambia</td>
                         <td><strong>$lds</strong> $lds_cambia</td>
                         <td>$max_cambia_minus <strong>$max</strong> $max_cambia_plus</td>
                         </tr>";

            }//end while



         $h.= "</tbody></table>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r)
?>