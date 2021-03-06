<?php

//TEST

if (preg_match('/rend.php/i', $_SERVER['SCRIPT_NAME'])){
    Header("Location: index.php"); die();
}

if(!is_Array($_FUNCTION_LOADER)){
     $_FUNCTION_LOADER = array    ( "widgets",
                                    "gphpcharts",
                                    "swift",
                                    "posta",
                                    "amici",
                                    "gas",
                                    "listini",
                                    "ditte",
                                    "tipologie",
                                    "articoli",
                                    "graphics",
                                    "ordini",
                                    "ordini_valori",
                                    "bacheca",
                                    "geocoding",
                                    "admin",
                                    "dareavere",
                                    "cassa",
                                    "opinioni",
                                    "des",
                                    "theming",
                                    "aiutanti");
}

array_push($_FUNCTION_LOADER,       "menu",
                                    "users",
                                    "data_check",
                                    "options",
                                    "varie",
                                    "rendering",
                                    "twitter");

//CARICA TUTTE LE FUNZIONI DI RETEGASAP
include_once("functions.php");


//Euro symbol
 $euro = "&#0128";

//Argomenti messaggi
$class_argomenti_messaggi = new argo;
$RG_lista_argomenti_messaggi = $class_argomenti_messaggi->argomenti;

//Visibility messaggi
$class_visibility_messaggi = new visi;
$RG_visibility_messaggi = $class_visibility_messaggi->visibility;


//Indirizzi pagine
$class_indirizzi = new addresses($site_path,$local_path, _ROOOT_,$images_path);
$RG_addr = $class_indirizzi->addr;

//debug
$class_debug = new debugs();
$RG_debug_state = $class_debug->debug_state;
$RG_debug_msg = $class_debug->debug_msg;
$RG_debug_start = $class_debug->debug_start;






if(function_exists("manda_mail")){

    // POSTINO----------------------------------------------------QUESTA VIENE ESEGUITA SEMPRE
    // Se la tabella postino ha mail in coda, ne mando 2.
    check_coda_mail();

}


// CONTROLLO SE USER E' LOGGATO, assegno i valori alle costanti
if (is_logged_in($user)) {

        // Aggiorno l'ultimo accesso di USER
        //update_activity($user);

        //AGGIORNO TABELLA USERS SU ULTIMO LOGIN di USER
        $db->sql_query("UPDATE maaking_users SET
                        maaking_users.last_activity = NOW(),
                        maaking_users.user_start_page = '".$_SERVER['HTTP_USER_AGENT']."'
                        WHERE maaking_users.userid ='"._USER_ID."';");

        //CARICO LE IMPOSTAZIONI SITO DI USER (beta)
        load_user_options(_USER_ID);
}else{


        //SE USER NON E' LOGGATO SISTEMO LE COSTANTI DES
        //TODO Gestire pi? decentemente le options
        define("_USER_ID_DES",0);
        define("_USER_DES_NAME","Rete dei DES");
        define("_DES_SITE_LOGO",$RG_addr["img_logo_retedes"]);

}


//Scelgo comunque il linguaggio, anche se USER non ? loggato.
select_language(_USER_ID);
