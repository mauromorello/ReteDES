<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
include_once ("../ordini_renderer.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if(ordine_inesistente($id_ordine)){
        pussa_via();
        exit;
}

if($do=="offerta_aiuto"){
    write_option_aiuto_ordine($id_ordine,_USER_ID,CAST_TO_STRING($ruolo));
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
     <p>Mi offrirei in qualità  di <quote>$ruolo</quote>, cosa ne dici ?</p>
     <p style=\"color:red\">ATTENZIONE : questo utente è in attesa di una tua conferma o rifiuto della sua offerta di aiuto; Per gestire le offerte di aiuto vai nella pagina
     \"Scheda ordine\"->\"Gestisci\"->\"Gestione aiuti\" e clicca su \"conferma\" o \"rifiuta\" a seconda della tua volontà .</p>
     <p>"._USER_FULLNAME."</p>");
     go("ordini_form_new",_USER_ID,"Aiuto offerto, una mail è stata mandata al referente per avvisarlo","?id_ordine=$id_ordine");
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Offerta di aiuto";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);



if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
        $help_ruolo='<h3>Alcuni esempi:</h3>
                     <ul>
                        <li>Logistica</li>
                        <li>Trasporto</li>
                        <li>Stoccaggio</li>
                        <li>Movimentazione</li>
                        <li>Godronatura</li>
                        <li>Buccellatura</li>
                        <li>Collazione di beni</li>
                        <li>Spingitore di cavalieri</li>
                        <li>Perizia di parte</li>
                        <li>Pettinatore di bambole</li>
                        <li>Mastro cioccolataio</li>
                     </ul>
                     <br>
                     MAX 50 caratteri, prego.';


        $h = '<div class="rg_widget rg_widget_helper">
        <h3>Stai per offrire il tuo aiuto !</h3>

        <form name="offerta_aiuto" method="POST" action="" class="retegas_form">

        
        <div>
        <h4>1</h4>
        <label for="descrizione">Descrivi in cosa consiste il tuo aiuto:</label>
        <input type="text" name="ruolo" value="'.$ruolo.'" size="50"></input>
        <h5 title="'.$help_ruolo.'">Inf.</h5>
        </div>

             
                        
        <div>
        <h4>2</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Offriti !" align="center" >
        <input type="hidden" name="do" value="offerta_aiuto">
        <input type="hidden" name="id_ordine" value="'.$id_ordine.'">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div> 


        </form>
        </div>';

//Questo ?? il contenuto della pagina
$r->contenuto = schedina_ordine($id_ordine).$h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>