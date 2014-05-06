<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI

$id_ordine = CAST_TO_INT($id_ordine);


$mio_Stato = ordine_io_cosa_sono($id_ordine,_USER_ID);
if ($mio_Stato<3){
    go("sommario",_USER_ID,"Questo ordine non mi compete");
}

if(read_option_gas_text_new(_USER_ID_GAS,"_REFERENTI_MULTIPLI")<>"SI"){
    go("ordini_form",_USER_ID,"Il tuo GAS non permette gestori multipli !","?id_ordine=$id_ordine");
}

if($do=="add"){
    $id_referente_extra=CAST_TO_INT($id_referente_extra);
    if($id_referente_extra>0){
        write_option_referente_extra($id_ordine,$id_referente_extra,$ruolo);
        $fullname = fullname_from_id($id_referente_extra);
        log_me($id_ordine,_USER_ID,"ORD","EXT","Aggiunto referente Extra", 0,$fullname." per ".$ruolo);
        
        $oggetto = "["._SITE_NAME." - GESTORE EXTRA] - Sei stato nominato per aiutare a gestire l'ordine $id_ordine (".descrizione_ordine_from_id_ordine($id_ordine).") !!";
        $messaggio ="<h3>Congratulazioni !!!</h3>
                    <p>"._USER_FULLNAME.", che è il gestore dell'ordine in oggetto, ha pensato bene di nominarti come vice-aiuto-gestore per questo ordine.</p>
                    <p>Egli ti pensava di grande aiuto nel ruolo di <strong>$ruolo</strong>;</p>
                    <p>Avrai quandi accesso a menù estremamente riservati e potrai effettuare anche tu tutte le importantissime operazioni che questa responsabilità implica.</p>
                    <p>Ogni operazione effettuata verrà comunque registrata, e la cronistoria degli accadimenti è disponibile alla consultazione da parte di tutti gli utenti.</p>";
        $da_chi = _USER_FULLNAME;
        $mail_da_chi = email_from_id(_USER_ID);
        
        $verso_chi = $fullname;
        $mail_verso_chi = email_from_id($id_referente_extra);
        
        //MAIL All'AIUTO REFERENTE
        manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$oggetto,strip_tags($messaggio),"AUT",$id_ordine,_USER_ID,$messaggio);
        
        $msg = "Referente aggiunto e mail inviata.";
        
    
    }else{
        $msg= "Devi selezionare un utente";
    }
    
}
if($do=="del"){
    $id_referente_extra=CAST_TO_INT($id_referente_extra);
    if($id_referente_extra>0){
        delete_option_referente_extra($id_ordine,$id_referente_extra);
         log_me($id_ordine,_USER_ID,"ORD","EXT","Tolto referente Extra", 0,$fullname);
    }   
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Referenti EXTRA";
$r->javascripts_header[] = java_head_select2();
$r->javascripts[]= "<script>
        $(document).ready(function() { $('#id_referente_extra').select2(); });
    </script>";

//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);;


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}else{
    $r->messaggio = $msg;
}

$query_users = "SELECT  maaking_users . * 
                        FROM maaking_users
                        WHERE userid <>'"._USER_ID."'
                        AND id_gas='"._USER_ID_GAS."'
                        AND isactive =1 ";
        $res_users = $db->sql_query($query_users);

        while ($row = $db->sql_fetchrow($res_users)){

            $user_select .= '<option value="'.$row["userid"].'" '.$selected.'>'.$row["fullname"].'</option>\\n';     
        }

        $help_ruolo = "Indica cosa dovrebbe fare nello specifico il tuo nuovo aiutante. Questa frase verrà riportata nella mail che gli stai per mandare.";
        $help_id_referente_extra = "Scegli tra gli utenti attivi del tuo gas chi vuoi che ti aiuti";
        $help_partenza = "Cliccando qua parte una mail che avvisa l'ignaro utente della tua scelta";
        
//Contenuto
$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Gestioni referenti EXTRA</h3>";
$h .= "<p>I referenti EXTRA possono aiutare il referente ordine a compiere alcune operazioni, e a controllare i reports.</p>";
$h .= "<form action=\"\" method=\"POST\" class=\"retegas_form\">";
$h .= '<div>
        <h4>1</h4>
        <span>
        <label for="id_referente_extra">Utente referente EXTRA</label>
        <select id="id_referente_extra" name="id_referente_extra">
        <option value="0">Nessun utente selezionato</OPTION>
        '.$user_select.'        
        </select>
        <h5 title="'.$help_id_referente_extra.'">Inf.</h5>
        </span>

        </div>';
$h .= '<div>
        <h4>2</h4>
        <label for="ruolo">Ruolo assegnato:</label>
        <input type="text" name="ruolo" value="" >
        <h5 title="'.$help_ruolo.'">Inf.</h5>
        </div>';        
$h .= '<div>
        <h4>3</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Aggiungi referente !" align="center" >
        <input type="hidden" name="do" value="add">
        <input type="hidden" name="id_ordine" value="'.$id_ordine.'">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div>';
$h .= "</form>";



$h .= "<h3>Lista attuali referenti EXTRA :</h3>";
$h .= crea_lista_referente_extra($id_ordine);
$h .= "<div>";







//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);