<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_USA_CASSA)){
     pussa_via();
}

if($do=="add"){
    $da_chi = _USER_FULLNAME;
    $mail_da_chi = id_user_mail(_USER_ID);
    $importo = str_replace(",", ".", $importo);
    $sql = "INSERT INTO retegas_options (id_user,
                                        chiave,
                                        valore_text,
                                        note_1,
                                        id_gas,
                                        valore_real) 
                                        VALUES (
                                        "._USER_ID.",
                                        'PREN_MOV_CASSA',
                                        '".sanitize($descrizione)."',
                                        '".sanitize($documento)."',
                                        '"._USER_ID_GAS."',
                                        '".CAST_TO_FLOAT($importo,0)."');";
   $db->sql_query($sql);
   
   $qry="SELECT
                    maaking_users.fullname,
                    maaking_users.email,
                    maaking_users.id_gas,
                    userid
                    FROM
                    maaking_users
                    WHERE
                    maaking_users.id_gas = '"._USER_ID_GAS."'
                    ";
        $result = $db->sql_query($qry);
        $lista_destinatari ="";
        while ($row = $db->sql_fetchrow($result)){
            if(leggi_permessi_utente($row["userid"])& perm::puo_gestire_la_cassa){
                $verso_chi[] = $row["fullname"] ;
                $mail_verso_chi[] = $row["email"] ;
                $lista_destinatari .= $row["fullname"]."<br>";
            }

        }// END WHILE

        $soggetto = "["._SITE_NAME." - PRENOTAZIONE CARICO CASSA] $da_chi per una ricarica crediti";
        $msg_mail = "<strong>PRENOTAZIONE MOVIMENTO</strong><br>";
        $msg_mail .= "<p>Ciao cassiere, "._USER_FULLNAME." ha richiesto un anticipo di carico credito.</p>";
        $msg_mail .= "<p>Il suo messaggio è : <i>".$descrizione."</i></p>";
        if($documento<>""){
            $msg_mail .= "<p>Il documento che ha associato è: <b>".$documento."</b> </p>";    
        }
        $msg_mail .= "<p>per un importo di <b><i>".$importo."</i></b> Crediti</p><br><br>";
        $msg_mail .= "<p>Vai sul sito a <a href=\"".$RG_addr["cassa_movimenti_suggeriti"]."\">questa</a> pagina per controllare tutte le prenotazioni di carico credito;</p>";
        //$msg_mail .= "<p>oppure clicca <a href=\"".$RG_addr["cassa_movimenti_suggeriti"]."?do=car&id=\">QUA</a> per caricare il credito direttamente (se ti fidi).</p>";
        $msg_mail .= "<p>In ogni caso buon lavoro.</p>";
        manda_mail_multipla_istantanea($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,strip_tags($msg_mail),"MAN",0,_USER_ID,$msg_mail);
        log_me(0,_USER_ID,"CAS","PRE","Prenotazione carico",0,"Messaggio inviato:<br>".$msg_mail);    
        $msg="Prenotazione credito aggiunta, Mail correttamente inviata a: <br>$lista_destinatari";
 
    
    
}
if($do=="del"){
   $id=CAST_TO_INT($id); 
   $sql = "SELECT * FROM retegas_options WHERE id_option=".$id;
   $res=$db->sql_query($sql);
   $row=$db->sql_fetchrow($res);
   if($row["chiave"]=="PREN_MOV_CASSA" AND _USER_ID==$row["id_user"]){
    $sql = "DELETE FROM retegas_options WHERE id_option=".$id;
    $db->sql_query($sql);
    $msg="Tolto";   
   }else{
    $msg="NON POSSIBILE";    
   }
  
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Suggerisci movimento cassa";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale[] = cassa_menu_completo();

//Assegno le due tabelle a tablesorter
$r->javascripts_header[]=java_head_ckeditor();
$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}else{if(!is_empty($msg)){$r->messaggio=$msg;}}
//Contenuto

$sql = "SELECT * FROM retegas_options WHERE chiave='PREN_MOV_CASSA' AND id_user='"._USER_ID."'";
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result)){
    $m.="<div>".$row["valore_text"]." - ".$row["note_1"]."; &euro; "._nf($row["valore_real"])." - <a href=\"?do=del&id=".$row["id_option"]."\" class=\"awesome red option\">E</a></div>";
}

$h .='
<div class="rg_widget rg_widget_helper">
    <h3>Suggerisci carico</h3>
    <p>In questa pagina puoi avvisare il cassiere del tuo gas che hai eseguito una ricarica crediti, se lui si fida accetterà il movimento ed i crediti verranno subito aggiunti.</p>
    <form class="retegas_form" method="POST" action="">
        <div>
        <h4>1</h4>
        <label for="descrizione">Note</label>
        <input id="descrizione" type="text" name="descrizione" value="'.$descrizione.'" size="50"></input>
        <h5 title="Se vuoi comunicare qualcosa">Inf.</h5>
        </div>
        <div>
        <h4>2</h4>
        <label for="documento">Documento</label>
        <input id="documento" type="text" name="documento" value="'.$documento.'" size="20"></input>
        <h5 title="Se il movimento è riferibile ad un documento">Inf.</h5>
        </div>
        <div>
        <h4>3</h4>
        <label for="importo">Euro</label>
        <input id="importo" type="text" name="importo" value="'.$importo.'" size="10"></input>
        <h5 title="L\'entità del tuo movimento">Inf.</h5>
        </div>
        
        <div>
        <h4>4</h4>
        <label for="submit">e infine... </label>
        <input id="submit" type="submit" name="submit" value="Suggerisci carico" align="center" >
        <input type="hidden" name="do" value="add">
       
        </div>
    </form>
    <br>
    <h3>In attesa del cassiere : </h3>
    '.$m.'
           
       



</div>';


//Questo ?? il contenuto della pagina
$r->contenuto = $h;


//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);   
