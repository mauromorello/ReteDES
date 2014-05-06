<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("listini_renderer.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_creare_listini)){
     pussa_via();
}

//SE NON E' IL MIO LISTINO
if(listino_proprietario($id_listino)<>_USER_ID){
    pussa_via();
}


if($do=="check_data"){
    if(!isset($_FILES)) $_FILES = $HTTP_POST_FILES;
    if(!isset($_SERVER)) $_SERVER = $HTTP_SERVER_VARS;    
    $upload_dir = "./uploads";
    $new_name = time()+rand(0,100000).".csv";
    $old_name_csv = $_FILES["upfile"]["name"];
    $file_name = ($new_name) ? $new_name : $_FILES["upfile"]["name"];
    $filone = $upload_dir."/".$file_name;
    if(trim($_FILES["upfile"]["name"]) == "") {
        $err++;
        $msg .= "Nessun file da caricare !!";
    }
    if(@is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
    @move_uploaded_file($_FILES["upfile"]["tmp_name"], "$upload_dir/$file_name") 
        or die("Impossibile spostare il file, controlla l'esistenza o i permessi della directory dove fare l'upload.");
    } else {
        $err++;    
        $msg .= ("Problemi nell'upload del file " . $_FILES["upfile"]["name"]);
    }
    $row = 0;
    
    
    if($err==0){
        
        $fd = fopen ($upload_dir."/".$file_name, "r");
        // ---Controllo errori-----------------------------------------------------------------------------------------------------
        $erro_vuoti = 0;
        $erro_num = 0;
        $erro_doppi = 0;
        $erro_zero=0;
        $warn_string = 0;
        $erro_multi =0;
        $unici=0;
        $array_articoli[0]="";
        //SALTA LA PRIMA RIGA
        $data = fgetcsv($fd, 1000, _USER_CSV_SEPARATOR,_USER_CSV_DELIMITER);
        while (($data = fgetcsv($fd, 1000, _USER_CSV_SEPARATOR,_USER_CSV_DELIMITER)) !== FALSE) {
            $num = count($data);
            $row++;
            
            //CONTROLLO VUOTI
            for ($i=0; $i<=4; $i++){if(is_null($data[$i]) or ($data[$i]=="")){$erro_vuoti++;}} // Se i campi sono vuoti
            for ($i=6; $i<=7; $i++){if(is_null($data[$i]) or ($data[$i]=="")){$erro_vuoti++;}} // Se i campi sono vuoti saltano l'ingombro 
            
            //WARNING CARATTERI STRANI
            //if (ereg("[^A-Za-z0-9.,-_!$%()= ]",  $data[0])>0){$warn_string++;}  //codice
            //if (ereg("[^A-Za-z0-9.,-_!$%()= ]",  $data[1])>0){$warn_string++;}  //descrizione
            //if (ereg("[^A-Za-z0-9.,-_!$%()= ]",  $data[3])>0){$warn_string++;}  //u misura
            //if (ereg("[^A-Za-z0-9.,-_!$%()= ]",  $data[5])>0){$warn_string++;}  //ingombro
            
            //NUMERI ?
            if (!is_numeric(trim(str_replace(array(","),array("."),$data[7])))){$erro_num++;} 
            if (!is_numeric(trim(str_replace(array(","),array("."),$data[6])))){$erro_num++;}
            
            //MULTIPLO E VALUTA
            if (!is_multiplo((trim(str_replace(array(","),array("."),$data[7]))),(trim(str_replace(array(","),array("."),$data[6]))))){$erro_multi++;}  // Multiplo errato 
            if (!is_numeric(trim(str_replace(array(",","€"),array(".",""),$data[4])))){$erro_num++;} 
            if (!is_numeric(trim(str_replace(array(",","€"),array(".",""),$data[2])))){$erro_num++;}
            
            //UNIVOCO
            if (trim($data[9])<>""){$unici++;}
            
            //CAMPI TESTO
            //$data[10]=sanitize($data[10]); //OPZ1
            //$data[11]=sanitize($data[11]); //OPZ2
            //$data[12]=sanitize($data[12]); //OPZ3
            
            //Controllo articoli doppi
            if (!$data[0]==""){
                $result = $db->sql_query("SELECT retegas_articoli.id_articoli
                                        FROM retegas_articoli
                                        WHERE (((retegas_articoli.codice)='$data[0]') AND ((retegas_articoli.id_listini)=$listino));");    
                
                if (empty($result)){
                    $erro_vuoti++; 
                }else{
                
                    if ($db->sql_numrows($result)>0){
                    $doppi[]=$data[0];    
                    $erro_doppi++;   
                    }
                }  
            }
            
            //Anche nella stessa lista
            // COntrollo all'interno della stessa lista
            if (in_array($data[0],$array_articoli)){
                $doppi[]=$data[0];    
                $erro_doppi++;    
            }else{
                $array_articoli[$row]=$data[0];
            }
        }//END WHILE LOOP    
        
        //CHIUDO IL PRIMO GIRO
        fclose ($fd);

        
        //CREO LA TABELLA DI CONTROLLO
        $fd = fopen ($upload_dir."/".$file_name, "r");
           
        
        if(($erro_vuoti+$erro_doppi+$error_msg+$erro_num)>0){
            $h_table .="Upload File ($old_name_csv) avvenuta correttamente, <strong>MA...</strong>";
        }else{
            $h_table .="Upload File ($old_name_csv) avvenuta correttamente !....  ";
        }

        //WARNINGS
        if ($erro_vuoti>0){
        $h_table .="<div class=\"campo_vuoto ui-corner-all\">Sono stati trovati n.$erro_vuoti campi vuoti</div>";
        }
        if ($erro_num>0){ 
        $h_table .="<div class=\"non_riconosciuto ui-corner-all\">n.$erro_num valori  non sono stati riconosciuti.</div>"; 
        }
        if ($erro_doppi>0){ 
        $h_table .="<div class=\"articoli_doppi ui-corner-all\">n.$erro_doppi articoli gia' presenti nel listino.</div>"; 
        }
        if ($erro_zero>0){ 
        $h_table .="<div class=\"qta_inf_1 ui-corner-all\">n.$erro_zero Quantita' inferiori a 1 (il minimo ? 1))</div>"; 
        }
        if ($erro_multi>0){ 
        $h_table .="<div class=\"multiplo_errato ui-corner-all\">n.$erro_multi articoli con Q. minima NON multipla di Q scatola.</div>"; 
        }
        if ($warn_string>0){ 
        $h_table .="<div class=\"punteggiatura ui-corner-all\">n.$warn_string campi con punteggiatura che verra' omessa. (Es: ' o \")</div>"; 
        }
        if ($unici>0){ 
        $h_table .="<div class=\"punteggiatura ui-corner-all\">n.$unici articoli UNIVOCI</div>"; 
        }
        
        if ($erro_num + $erro_vuoti + $erro_doppi + $erro_zero ==0){
            
            //CASO BUONO
            $h_table .="Sembra tutto OK;                
                                 <a class =\"large awesome green\" href=\"".$RG_addr["listini_form_2"]."?do=upload&fname=$filone&id_listino=$id_listino&tipo_file=CSV\">Carica questi $row Articoli nel listino $listino</a>
             <br />"; 
        }else{
            
            //CASO CATTIVO
            $h_table .="<div class=\"ui-state-error ui-corner-all padding_6px\"><b>Correggere il vostro file CSV e rifare l'upload.
            </div>
            </br> ";     
        }
        
        
        //testata
        $row = 0;
        $data = fgetcsv($fd, 1000, _USER_CSV_SEPARATOR,_USER_CSV_DELIMITER);
        $h_table .= "
        <table id=\"output_1\">
            <thead>
            <tr>
                <th>{$data[0]}</th>
                <th>{$data[1]}</th>
                <th>{$data[2]}</th>
                <th>{$data[3]}</th>
                <th>{$data[4]}</th>
                <th>{$data[5]}</th>
                <th>{$data[6]}</th>
                <th>{$data[7]}</th>
                <th>{$data[8]}</th>
                <th>{$data[9]}</th>
                <th>{$data[10]}</th>
                <th>{$data[11]}</th>
                <th>{$data[12]}</th>
                
            </tr>
            </thead>
        <tbody>";
        while (($data = fgetcsv($fd, 1000, _USER_CSV_SEPARATOR,_USER_CSV_DELIMITER)) !== FALSE) {
            $num = count($data);
            
            for ($i=0; $i<=7; $i++){ 
                if(is_null($data[$i]) or ($data[$i]=="")){                  // E' nullo il valore              
                    $bg[$i] = " class=\"campo_vuoto\" ";
                 }else{
                    $bg[$i] = "";     
                 }
            }   
            if ($erro_doppi>0){if (in_array($data[0],$doppi)){$bg[0] = "class=\"articoli_doppi\"";}}// e' uno dei doppi
        
            //if (ereg("[^A-Za-z0-9.,-_!$%()= ]",  $data[0])>0){$bg[0] = "class=\"punteggiatura\"";}// Cod art. COntiene caratteri insulsi
            //if (ereg("[^A-Za-z0-9.,-_!$%()= ]",  $data[1])>0){$bg[1] = "class=\"punteggiatura\"";}// Descriz COntiene caratteri insulsi  
            //if (ereg("[^A-Za-z0-9.,-_!$%()= ]",  $data[3])>0){$bg[3] = "class=\"punteggiatura\"";}// U mis caratteri insulsi  
            //if (ereg("[^A-Za-z0-9.,-_!$%()= ]",  $data[5])>0){$bg[5] = "class=\"punteggiatura\"";}// Ingombro caratteri insulsi  
            
            if (!is_numeric(trim(str_replace(array(","),array("."),$data[7])))){$bg[7] = "class=\"non_riconosciuto\"";} 
            if (!is_numeric(trim(str_replace(array(","),array("."),$data[6])))){$bg[6] = "class=\"non_riconosciuto\"";}
            
            $data[8]=html_entity_decode($data[8]);
            
            if (trim($data[9])<>""){$bg[9] = "class=\"non_riconosciuto\"";}else{$bg[9] = "";}
            
            if (!is_multiplo((trim(str_replace(array(","),array("."),$data[7]))),(trim(str_replace(array(","),array("."),$data[6]))))){$bg[7] = "class=\"multiplo_errato\"";} // Non ? un multiplo 
            
            if (!is_numeric(trim(str_replace(array(",","€","'"),array(".","",""),$data[4])))){$bg[4] = "bgcolor=\"#FF4477\"";} 
            if (!is_numeric(trim(str_replace(array(",","€","'"),array(".","",""),$data[2])))){$bg[2] = "bgcolor=\"#FF4477\"";}   
            $h_table .= "<tr>";
            $h_table .="<td><div $bg[0]>$data[0]<div></td>";
            $h_table .="<td><div $bg[1]>$data[1]<div></td>";    
            $h_table .="<td><div $bg[2]>$data[2]<div></td>
                         <td><div $bg[3]>$data[3]<div></td>
                         <td><div $bg[4]>$data[4]<div></td>
                         <td>$data[5]</td>
                         <td><div $bg[6]>$data[6]<div></td>
                         <td><div $bg[7]>$data[7]<div></td>
                         <td><div $bg[8]>$data[8]<div></td>
                         <td><div $bg[9]>$data[9]<div></td>
                         <td><div $bg[10]>$data[10]<div></td>
                         <td><div $bg[11]>$data[11]<div></td>
                         <td><div $bg[12]>$data[12]<div></td>";
            $h_table .="</tr>";
            
            
        }//SECONDO GIRO PER DISEGNARE LA TABELLA    
        $h_table .="    </tbody>
                    </table>";
        fclose ($fd);
        if ($erro_num + $erro_vuoti + $erro_doppi+ $erro_zero  > 0){          // se c'era qualche errore cancello il file'
            if(is_file("$filone")) {
            unlink("$filone");
            }   
        }
        
        
    }//SE NON CI SONO ERRORI DI CARICAMENTO FILE
}




//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::anagrafiche;
//Assegno il titolo che compare nella barra delle info
$r->title = "Importazione CSV";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = listini_menu_completo($id_listino);

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h = "<div class=\"rg_widget rg_widget_helper\">"; 
$h .= "<h3>Importazione listino CSV</h3>";
$h .= listini_form($id_listino);
$h .= $h_table; 
$h .= "</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);