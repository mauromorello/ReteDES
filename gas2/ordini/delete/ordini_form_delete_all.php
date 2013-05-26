<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
include_once ("../ordini_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if(!isset($id_ordine)){
     pussa_via();
}

$ok="";
//CONTROLLI
     
// SE E' DELLO STESSO GAS e PUO' VEDERE TUTTI GLI ORDINI
if(_USER_ID_GAS==id_gas_user(id_referente_ordine_globale($id_ordine))){          
    if(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini){
        $ok="OK";     
    }        
}
//SE PUO' GESTIRE RETEGAS    
if ((_USER_PERMISSIONS & perm::puo_gestire_retegas)){
    $ok="OK";
}

if($ok<>"OK"){
    pussa_via();
}

if($do=="del_all"){
        
        $verso_chi = fullname_referente_ordine_globale($id_ordine); 
        $mail_verso_chi = mail_referente_ordine_globale($id_ordine);
    
        $nomordine = descrizione_ordine_from_id_ordine($id_ordine);
              
        $msg = "";    
        
        
                $h_table .="<h3>BACKUP ORDINE $id_ordine</h3>";
                $h_table .="<p>Questi dati sono stati estratti il ".date("d/m/Y H:i")."</p>";
                $h_table .="<hr>";
                $h_table .="<h4>Tabella ORDINI</h4>";
                $h_table .=db_splat_table("retegas_ordini","id_ordini='$id_ordine'");
                $h_table .="<hr>";
                $h_table .="<h4>Tabella DETTAGLIO ORDINI</h4>";
                $h_table .=db_splat_table("retegas_dettaglio_ordini","id_ordine='$id_ordine'");
                $h_table .="<hr>";
                $h_table .="<h4>Tabella DISTRIBUZIONE SPESA</h4>";
                $h_table .=db_splat_table("retegas_distribuzione_spesa","id_ordine='$id_ordine'");
                $h_table .="<hr>";
                $h_table .="<h4>Tabella LOGS</h4>";
                $h_table .=db_splat_table("retegas_messaggi","id_ordine='$id_ordine'");
                $h_table .="<hr>";
                $h_table .="<h4>Tabella REFERENZE</h4>";
                $h_table .=db_splat_table("retegas_referenze","id_ordine_referenze='$id_ordine'");
                $h_table .="<hr>";
                $h_table .="<h4>Tabella CASSA</h4>";
                $h_table .=db_splat_table("retegas_cassa_utenti","id_ordine='$id_ordine'");
                $h_table .="<hr>";
                $h_table .= ordine_render_partecipazione("partecipazione",$id_ordine);
                $h_table .="<hr>";
                $h_table .= ordine_render_cronologia("cronologia",$id_ordine);

        // eliminazione tabella ORDINI
        if($el_ordini=="ON"){
            //echo "ELIMINAZIONE ORDINE  -> $el_ordini<br>";
            $msg .="Tolto da tabella ORDINI<br>";
            $sql = $db->sql_query("delete from  retegas_ordini where retegas_ordini.id_ordini=$id_ordine LIMIT 1;");
        }else{
            $msg .="SALTATA tabella ORDINI<br>";
        }
        // eliminazione tableea DETTAGLI
        if($el_dettagli=="ON"){ 
            //echo "ELIMINAZIONE DETTAGLI -> $el_dettagli<br>";
            $sql = $db->sql_query("delete FROM retegas_dettaglio_ordini WHERE id_ordine= '$id_ordine';");
            $msg .="Tolto da tabella DETTAGLI<br>";  
        }else{
            $msg .="SALTATA tabella DETTAGLI<br>";
        }    
        // eliminazione tableea ASSEGNAZIONI
        if($el_assegnazioni=="ON"){ 
            //echo "ELIMINAZIONE ASSEGNAZIONI -> $el_assegnazioni<br>";
            $sql = $db->sql_query("delete FROM retegas_distribuzione_spesa WHERE id_ordine= '$id_ordine';");
            $msg .="Tolto da tabella ASSEGNAZIONI<br>"; 
        }else{
            $msg .="SALTATA tabella ASSEGNAZIONI<br>";
        }
             
        // eliminazione dalla tabella MESSAGGI
        if($el_messaggi=="ON"){ 
            //echo "ELIMINAZIONE MESSAGGI -> $el_messaggi<br>";
            $sql = $db->sql_query("delete FROM retegas_messaggi WHERE id_ordine= '$id_ordine';");
            $msg .="Tolto da tabella MESSAGGI<br>";  
        }else{
            $msg .="SALTATA tabella MESSAGGI<br>";
        }
        
        if($el_referenze=="ON"){ 
            //echo "ELIMINAZIONE REFERENZE -> $el_referenze<br>";
            $sql = $db->sql_query("delete FROM retegas_referenze WHERE id_ordine_referenze= '$id_ordine'");
            $msg .="Tolto da tabella REFERENZE<br>";  
        }else{
            $msg .="SALTATA tabella REFERENZE<br>";
        }
        
        if($el_cassa=="ON"){ 
            //echo "ELIMINAZIONE REFERENZE -> $el_referenze<br>";
            $sql = $db->sql_query("delete FROM retegas_cassa_utenti WHERE id_ordine= '$id_ordine'");
            $msg .="Tolto da tabella CASSA<br>";  
        }else{
            $msg .="SALTATA tabella CASSA<br>";
        }    

        
        $h_table .= "<hr><h3>RISULTATI</h3><br>".$msg;
        
        $da_chi = fullname_from_id(_USER_ID);
        $mail_da_chi = id_user_mail(_USER_ID);
        
        

        $soggetto = "["._SITE_NAME."] - da $da_chi - ELIMINAZIONE ORDINE";
        
        $testata = "<p>Caro $verso_chi;<br>
                    L'ordine $id_ordine (TUTTO o alcune sue parti) è stato eliminato definitivamente dal sito da "._USER_FULLNAME.".<br>
                    Questa mail che ricevi contiene TUTTI i dati riguardanti quest'ordine che ad oggi sono presenti su
                    www.retedes.it<br><br>";
        
        $msg.="Mail correttamente inviata a $verso_chi (ref. ordine)<br>";
        manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,"","MAN",$id_ordine,_USER_ID,$testata.$h_table);
        sleep(1);

        $verso_chi = _USER_FULLNAME;
        $mail_verso_chi = id_user_mail(_USER_ID);
        $testata = "<p>Caro $verso_chi;<br>
                    L'ordine $id_ordine (TUTTO o alcune sue parti) è stato eliminato definitivamente dal sito da "._USER_FULLNAME.".<br>
                    Questa mail che ricevi contiene TUTTI i dati riguardanti quest'ordine che ad oggi sono presenti su
                    www.retedes.it<br><br>";
        manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,"","MAN",$id_ordine,_USER_ID,$testata.$h_table);
        $msg.="Mail correttamente inviata a $verso_chi (autore operazione)";
        
        
        
            
        log_me($id_ordine,_USER_ID,"ORD","ERA","Eliminazione globale ordine $id_ordine, ($nomordine)",0,$msg.$h_table);
        go("sommario",_USER_ID,$msg);
        die();
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Eliminazione definitiva ordine $id_ordine";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine); 

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
      $my_query="SELECT * FROM retegas_ordini WHERE (id_ordini='$id_ordine')  LIMIT 1";
      $result = mysql_query($my_query);
      $row = mysql_fetch_array($result);  
      
      //$h_table .= amici_menu_1();
      $c1 = $row["id_ordini"];
      $c2 = $row["descrizione_ordini"];
      $c3 = conv_date_from_db($row["data_apertura"]);
      $c4 = conv_date_from_db($row["data_chiusura"]);
      $titolo_tabella = "Eliminazione ordine GLOBALE";
      
      $total_ordini = ceil(mysql_num_rows(mysql_query("SELECT id_ordini FROM retegas_ordini WHERE id_ordini= '$id_ordine'")));
      $total_dettagli = ceil(mysql_num_rows(mysql_query("SELECT id_dettaglio_ordini FROM retegas_dettaglio_ordini WHERE id_ordine= '$id_ordine'"))); 
      $total_assegnazioni = ceil(mysql_num_rows(mysql_query("SELECT id_distribuzione FROM retegas_distribuzione_spesa WHERE id_ordine= '$id_ordine'"))); 
      $total_messaggi = ceil(mysql_num_rows(mysql_query("SELECT id_messaggio FROM retegas_messaggi WHERE id_ordine= '$id_ordine'"))); 
      $total_referenze = ceil(mysql_num_rows(mysql_query("SELECT id_referenze FROM retegas_referenze WHERE id_ordine_referenze= '$id_ordine'"))); 
      $total_cassa = ceil(mysql_num_rows(mysql_query("SELECT * FROM retegas_cassa_utenti WHERE id_ordine= '$id_ordine'"))); 
      
      $h_table .= ' 
                    <div class="rg_widget rg_widget_helper">
                    <h3>Eliminazione perpetua dell\'ordine '.$c1.' - '.$c2.'</h3>
                    <h4>Attenzione : saranno eliminati TUTTI i records associati a questo ordine, compresi quelli della cassa, anche se appartengono ad altri GAS.<br>
                    usare questa funzione DOPO aver avvisato tutti gli interessati.<br>
                    Scegliendo "Crea backup" si invierà una mail con i dati "crudi" del database a se stessi e al referente ordine.<br>
                    Attualmente NON è possibile Reintegrare un ordine una volta eliminato.<br>
                    Per avere un backup dell\'ordine deselezionare tutte le tabelle<br></h4>
                    <form name="delete_all" action="" method="post" >
                    ';

     
         $h_table .=rg_toggable('<input type="checkbox" name="el_ordini" id="ordini" value="ON"  checked  title="Tabella Ordini">TABELLA ORDINI: '.$total_ordini,"1_a",db_splat_table("retegas_ordini","id_ordini='$id_ordine'"));
         $h_table .=rg_toggable('<input type="checkbox" name="el_dettagli" id="dettagli" value="ON" align="left" checked  title="Tabella DETTAGLI">TABELLA DETTAGLI: '.$total_dettagli,"2_a",db_splat_table("retegas_dettaglio_ordini","id_ordine='$id_ordine'"));
         $h_table .=rg_toggable('<input type="checkbox" name="el_assegnazioni" id="assegnazioni" value="ON" align="left" checked  title="Tabella Assegnazioni">TABELLA ASSEGNAZIONI: '.$total_assegnazioni,"3_a",db_splat_table("retegas_distribuzione_spesa","id_ordine='$id_ordine'"));
         $h_table .=rg_toggable('<input type="checkbox" name="el_messaggi" id="messaggi" value="ON" align="left" checked  title="Tabella Messaggi">TABELLA MESSAGGI: '.$total_messaggi,"4_a",db_splat_table("retegas_messaggi","id_ordine='$id_ordine'"));
         $h_table .=rg_toggable('<input type="checkbox" name="el_referenze" id="referenze" value="ON" align="left" checked  title="Tabella Referenze">TABELLA REFERENZE: '.$total_referenze,"5_a",db_splat_table("retegas_referenze","id_ordine_referenze='$id_ordine'"));
         $h_table .=rg_toggable('<input type="checkbox" name="el_cassa" id="cassa" value="ON" align="left" checked  title="Tabella Cassa">TABELLA CASSA: '.$total_cassa,"6_a",db_splat_table("retegas_cassa_utenti","id_ordine='$id_ordine'"));
         //$h_table .=rg_toggable('<input type="checkbox" name="do_bu" id="bu" value="ON" align="left" checked  title="Backup">Crea copia backup',"7_a","CONTENT");
     
     
     $h_table .= '  <input type="hidden" name="do" id="do" value="del_all">
                    <input type="hidden" name="id_ordine" value="'.$id_ordine.'">
                    <center><input type="submit" name="submit" class="awesome red large" id="submit" value="ELIMINA DEFINITIVAMENTE" align="right"  class="awesome">
                    </form> 
                    </div>
                    ';

//Questo ?? il contenuto della pagina
$r->contenuto = schedina_ordine($id_ordine).
                $h_table;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);