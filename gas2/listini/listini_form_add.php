<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//controlla se l'utente ha i permessi necessari
if(!(_USER_PERMISSIONS & perm::puo_creare_listini)){
     pussa_via();
}

//Mi assicuro che sia un numero
$id_ditta = $id;
(int)$id_ditta;

//MI assicuro che ci sia una ditta che corrisponda all'id
if (db_nr_q("id_ditte",$id_ditta,"retegas_ditte")==0){
    pussa_via();
}


$nome_ditta = ditta_nome($id_ditta);


//se il comando è quello di aggiungere la ditta
if($do=="add"){
      
          
      // se è vuoto
      if (empty($descrizione_listini)){$msg.="Devi almeno inserire il nome del listino<br>";$e_empty++;};
      if (empty($id_tipologie)){$msg.="Devi associare una tipologia di merce<br>";$e_empty++;};
      if (empty($data_valido)){$msg.="Devi inserire la data di scadenza<br>";$e_empty++;};
   
      // data di scadenza maggiore di oggi
      if (!controllodata($data_valido)){
        $e_logical ++;
        $msg.="Formato della data non riconosciuto<br>";    
      };
      
    
      //SE E' SCADUTO
      if (gas_mktime($data_valido,null)<gas_mktime(date("d/m/Y"),date("H:i:s"))){
      $msg.="Data antecedente ad oggi<br>";
      $e_logical ++;             
      }
      
      $msg.="<br>Verifica i dati immessi e riprova";
      
      
      $e_total = $e_empty + $e_logical + $e_numerical;
      
      if($e_total==0){
        //echo "ZERO ERRORI !!!";
        $data_valido = conv_date_to_db($data_valido);
        $descrizione_listini = sanitize($descrizione_listini);
        (int)$tipo_listino;
        (int)$is_privato;
        
        
        
        // QUERY INSERT
        $my_query="INSERT INTO retegas_listini 
                (descrizione_listini,
                 id_tipologie,
                 data_valido,
                 id_ditte,
                 id_utenti,
                 tipo_listino,
                 is_privato) VALUES (
                 '$descrizione_listini',
                 '$id_tipologie',
                 '$data_valido',
                 '$id_ditta',
                 '"._USER_ID."',
                 '$tipo_listino',
                 '$is_privato');";
        
        //INSERT BEGIN ---------------------------------------------------------
         $result = $db->sql_query($my_query);
         if (is_null($result)){
            go("sommario",_USER_ID,"Errore inserimento");  
        }else{
            
            $sql = "SELECT MAX(id_listini) FROM retegas_listini;";
            $res = $db->sql_query($sql);
            $row = $db->sql_fetchrowset($res);
            $id_listino = $row[0][0];
            
            log_me(0,_USER_ID,"LIS","ADD","Creato il listino ($id_listino - $descrizione_listini) riferito alla ditta ($nome_ditta)",0,$my_query);
            $msg = "Nuovo listino aggiunto";
            go("listini_form_2",_USER_ID,"Listino correttamente inserito","?id_listino=$id_listino");  
        };

    
}
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = menu_lat::anagrafiche;
//Assegno il titolo che compare nella barra delle info
$r->title = "Aggiungi un listino alla ditta ".$nome_ditta ;
//Carico il 
$r->javascripts_header[]=java_head_datetimepicker();
$r->javascripts[] = java_qtip(".retegas_form h5[title]");

//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menÃ¹ orizzontale dovrÃ  essere associato alla pagina.
$r->menu_orizzontale = "";

//Disegno il grafico
$r->javascripts[]=java_datepicker("datepicker");

$r->messaggio = $msg;
//Creo la pagina dell'aggiunta
        $help_descrizione_listini='Il nome del listino.';
        $help_id_tipologie       ='Categoria merceologica'; 
        $help_data_valido        ='Valido per proporre ordini fino a questa data.';
        $help_tipo_listino       ='Se è un listino standard oppure magazzino';
        $help_is_privato         ='Se è visibile solo dal tuo gas oppure da tutti.';
        $help_partenza           ='Gli articoli dovrai aggiungerli in seguito';
          $input_id_tipologie = "<select name= \"id_tipologie\"> ";
          $input_id_tipologie .= "<option value=\"0\">Selezionare Tipologia merce</option> ";
          $result = mysql_query("SELECT * FROM retegas_tipologia");
            while ($row = mysql_fetch_array($result)){
                    $T1 = $row[0];
                    $T2 = $row[1];
          $input_id_tipologie .= "<option value=\"".$T1 ."\">".$T2 ."  </option>";   
             }//end while
          $input_id_tipologie.="</select>";
     
     
          $input_tipo_listino = "<select name= \"tipo_listino\"> ";
          $input_tipo_listino .= "<option value=\"0\">Standard</option> ";
          $input_tipo_listino .= "<option value=\"1\">Magazzino</option> ";
          $input_tipo_listino.="</select>";

          $input_is_privato = "<select name= \"is_privato\"> ";
          $input_is_privato .= "<option value=\"0\">Pubblico</option> ";
          $input_is_privato .= "<option value=\"1\">Privato (solo per il tuo GAS)</option> ";
          $input_is_privato.="</select>";        
     

        $h = '<div class="rg_widget rg_widget_helper">
        <h3>Inserisci un nuovo listino della ditta '.$nome_ditta.'</h3>

        <form name="nuovo_listino" method="POST" action="" class="retegas_form">

        
        <div>
        <h4>1</h4>
        <label for="descrizione_listini">Scrivi il nome del nuovo listino</label>
        <input type="text" name="descrizione_listini" value="'.$descrizione_listini.'" size="50"></input>
        <h5 title="'.$help_descrizione_listini.'">Inf.</h5>
        </div>

        <div>
        <h4>2</h4>
        <label for="id_tipologie">Categoria merceologica</label>
        '.$input_id_tipologie.'
        <h5 title="'.$help_id_tipologie.'">Inf.</h5>
        </div>
        
        <div>
        <h4>3</h4>
        <label for="data_valido">Inserisci la sua data di scadenza</label>
        <input type="text" name="data_valido" value="'.$data_valido.'" size="10" id="datepicker"></input>
        <h5 title="'.$help_data_valido.'">Inf.</h5>
        </div>

        <div>
        <h4>4</h4>
        <label for="tipo_listino">Tipo listino</label>
        '.$input_tipo_listino.'
        <h5 title="'.$help_tipo_listino.'">Inf.</h5>
        </div>        
        
        <div>
        <h4>4</h4>
        <label for="is_privato">Visibilità</label>
        '.$input_is_privato.'
        <h5 title="'.$help_is_privato.'">Inf.</h5>
        </div>       
                        
        <div>
        <h4>5</h4>
        <label for="submit">e infine... </label>
        <input type="submit" name="submit" value="Aggiungi questo listino" align="center" >
        <input type="hidden" name="do" value="add">
        <h5 title="'.$help_partenza.'">Inf.</h5>
        </div> 


        </form>
        </div>';




//Questo è¨ il contenuto della pagina
$r->contenuto = $h;
//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?> 