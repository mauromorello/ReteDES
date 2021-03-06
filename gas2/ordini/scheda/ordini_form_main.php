<?php


// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
//
include_once ("../ordini_renderer.php");

//Ricevo come GET id = id_ordine
// Lo obbligo ad essere un intero
(int)$id_ordine;
(int)$id;
if(isset($id)){$id_ordine=$id;}

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    pussa_via();
    exit;
}

//COntrollo permessi


//Se non esiste l'ordine
if(ordine_inesistente($id_ordine)){
        pussa_via();
        exit;
}


// Se in quell'ordine non posso fare nulla
if(!((_USER_PERMISSIONS & perm::puo_gestire_retegas) OR (_USER_PERMISSIONS & perm::puo_gestire_la_cassa))){
    if(ordine_io_cosa_sono($id_ordine,_USER_ID)==0){
            pussa_via();
            exit;
    }
}

if($do=="offerta_aiuto"){
    write_option_aiuto_ordine($id_ordine,_USER_ID,CAST_TO_STRING($ruolo));
    $msg=32;
    manda_mail(_USER_FULLNAME,
    email_from_id(_USER_ID),
    fullname_from_id(id_referente_ordine_globale($id_ordine)),
    mail_referente_ordine_globale($id_ordine),
    "["._SITE_NAME."] Aiuto Offerto !!",
    null,
    "AIU",
    $id_ordine,
    _USER_ID,
    "<h3>Vorrei aiutarti per l'ordine $id_ordine;</h3>
     <p>Mi offrirei in qualità di <quote>$ruolo</quote>, cosa ne dici ?</p>
     <p style=\"color:red\">ATTENZIONE : questo utente è in attesa di una tua conferma o rifiuto della sua offerta di aiuto; Per gestire le offerte di aiuto vai nella pagina
     \"Scheda ordine\"->\"Gestisci\"->\"Gestione aiuti\" e clicca su \"conferma\" o \"rifiuta\" a seconda della tua volontà.</p>
     <p>"._USER_FULLNAME."</p>");
}

    // ISTANZIO un nuovo oggetto "retegas"
    // Prender? come variabile globale $user, nel caso di user loggato
    // allora visualizza la barra info ed il menu verticale,
    // nel caso di user non loggato visualizza la pagina con "benvenuto" e
    //nel men? verticale i campi per il login
    $retegas = new sito;

    // assegno la posizione che sar? indicata nella barra info
    $retegas->posizione = "Ordine n.".$id_ordine;

    // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard

    $retegas->sezioni = $retegas->html_standard;

    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    //$retegas->menu_sito[] = ordini_menu_pacco($id_ordine);
    //$retegas->menu_sito[] = ordini_menu_visualizza($user,$id_ordine);
    //$retegas->menu_sito[] = ordine_menu_operazioni_base(_USER_ID,$id_ordine);
    //$retegas->menu_sito[] = ordine_menu_mia_spesa(_USER_ID,$id_ordine);
    //$retegas->menu_sito[] = ordine_menu_gas(_USER_ID,$id_ordine,_USER_ID_GAS);
    //$retegas->menu_sito[] = ordine_menu_gestisci_new(_USER_ID,$id_ordine,_USER_ID_GAS);
    //$retegas->menu_sito[] = ordine_menu_cassa(_USER_ID,$id_ordine,_USER_ID_GAS);
    //$retegas->menu_sito[] = ordine_menu_comunica(_USER_ID,$id_ordine,_USER_ID_GAS);
    //$retegas->menu_sito[] = ordine_menu_extra(_USER_ID,$id_ordine,_USER_ID_GAS);
    $retegas->menu_sito = ordini_menu_all($id_ordine);


    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;


    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");


      // creo  gli scripts per la gestione dei menu

      $retegas->java_scripts_header[] = java_accordion(null,menu_lat::ordini); // laterale
      $retegas->java_scripts_header[] = java_superfish();
      $retegas->java_scripts_header[] = java_head_jeditable();
      $retegas->java_scripts_bottom_body[] = '<script type="text/javascript">
                                                    $(document).ready(function() {
                                                        $("#postit").draggable();
                                                        $("#blink_me").effect("pulsate", { times:5 }, 4000);
                                                        $(".edit_area").editable("'.$RG_addr["ajax_ordini_note"].'", {
                                                             type      : "textarea",
                                                             submit    : "Salva",
                                                             submitdata : {id_ordine: "'.$id_ordine.'"}
                                                         });
                                                     });
                                               </script>';

      // assegno l'eventuale messaggio da proporre

        switch ((int)$msg){
            case 1:
            $msg = "Articoli correttamente inseriti in ordine.";
            break;
            case 2:
            $msg = "Articoli correttamente eliminati dall'ordine.";
            break;
            case 3:
            $msg = "Modifiche correttamente salvate.";
            break;
            case 4:
            $msg = "Referenza aggiunta.<br>Ora tu ed il tuo GAS potrete ordinare articoli da questo ordine.";
            break;
            case 5:
            $msg = "Operazione non possibile<br>Questo ordine non è tuo.";
            break;
            case 6:
            $msg = "Operazione non possibile<br>Ordine NON vuoto.";
            break;
            case 7:
            $msg = "Esiste già una operazione di pagamento al fornitore per questo ordine.<br>E' necessaria una operazione di rettifica per modificare l'importo.";
            break;

            case 8:
            $msg = "Il tuo credito disponibile non è sufficiente per questa operazione.";
            break;

            case 32:
            $msg = "Grazie per la tua offerta di aiuto; Il referente ordine la potrà confermare o rifiutare a suo piacimento, una mail gli è stata recapitata per informarlo.";
            break;

            default :
            $msg = read_option_text(_USER_ID,"MSG");
                   delete_option_text(_USER_ID,"MSG");
            break;

        }


      $retegas->messaggio=$msg;
      $retegas->has_bookmark="SI";
      $retegas->id_ordine = $id_ordine;

      $h =  schedona_ordine($id_ordine,_USER_ID);






      // qui ci va la pagina vera e proria
      $retegas->content  =  $warning_int.$h;


      //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
      $html = $retegas->sito_render();
      // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
      echo $html;


      //distruggo retegas per recuperare risorse sul server
      unset($retegas);

