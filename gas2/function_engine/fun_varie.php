<?php


//NUMERI
function numero_lettere($numero){ 
    if (($numero < 0) || ($numero > 999999999)) 
    { 
        return "$numero"; 
    } 

    $milioni = floor($numero / 1000000);  // Milioni   
    $numero -= $milioni * 1000000; 
    $migliaia = floor($numero / 1000);    // Migliaia  
    $numero -= $migliaia * 1000; 
    $centinaia = floor($numero / 100);     // Centinaia  
    $numero -= $centinaia * 100; 
    $decine = floor($numero / 10);       // Decine  
    $unita = $numero % 10;               // Unit?  

    $cifra_lettere = ""; 

    if ($milioni) 
    { 
            if ($milioni == 1)             
            $cifra_lettere .= numero_lettere($milioni) . "Milione"; 
            else 
            $cifra_lettere .= numero_lettere($milioni) . "Milioni"; 
    } 

    if ($migliaia) 
    { 
            if ($migliaia == 1)             
            $cifra_lettere .= numero_lettere($migliaia) . "Mille"; 
            else 
            $cifra_lettere .= numero_lettere($migliaia) . "Mila"; 
         
    } 

    if ($centinaia) 
    { 
          $cifra_lettere .= numero_lettere($centinaia) . "Cento"; 
    } 

    $array_primi = array("", "Uno", "Due", "Tre", "Quattro", "Cinque", "Sei", 
        "Sette", "Otto", "Nove", "Dieci", "Undici", "dodici", "Tredici", 
        "Quattordici", "Quindici", "Sedici", "Diciassette", "Diciotto", 
        "Diciannove"); 
    $array_decine = array("", "", "Venti", "Trenta", "Quaranta", "Cinquanta", "Sessanta", 
        "Settanta", "Ottanta", "Novanta"); 

    if ($decine || $unita) 
    { 
        if ($decine < 2) 
        { 
            $cifra_lettere .= $array_primi[$decine * 10 + $unita]; 
        } 
        else 
        { 
            $cifra_lettere .= $array_decine[$decine]; 

            if ($unita) 
            { 
                $cifra_lettere .= $array_primi[$unita]; 
            } 
        } 
    } 

    if (empty($cifra_lettere)) 
    { 
        $cifra_lettere = "Zero"; 
    } 

    return $cifra_lettere; 
} 
function _nf($numero){
    return number_format(round($numero,_USER_OPT_DECIMALS),_USER_OPT_DECIMALS,_USER_CARATTERE_DECIMALE,"");
}

//DATE
function datetime_to_timestamp($str) {

list($date, $time) = explode(' ', $str);
list($day, $month, $year) = explode('-', $date);
list($hour, $minute, $second) = explode(':', $time);

$timestamp = mktime($hour, $minute, $second, $month, $day, $year);

return $timestamp;
}

//VARIE
function is_chrome(){
    if (stristr($_SERVER['HTTP_USER_AGENT'], "chrome")) {
        return true;
    }else{
        if (stristr($_SERVER['HTTP_USER_AGENT'], "chromeframe")) {
         return true;    
        }
        
    return false;    
    }  
    
    //return(eregi("chrome", $_SERVER['HTTP_USER_AGENT']));
}
function is_ie(){
    
    //CONTROLLO user agent
    if (isset($_SERVER['HTTP_USER_AGENT']) && 
    (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){
    
        //Se usa chromeframe allora ? ok
        if ( stristr($_SERVER['HTTP_USER_AGENT'], 'chromeframe') ) {
            return false;
        }else{
            return true;   
        }
    
    }else
        return false;
}
function random_color(){
    mt_srand((double)microtime()*1000000);
    $c = '';
    while(strlen($c)<6){
        $c .= sprintf("%02X", mt_rand(0, 255));
    }
    return $c;
}
function random_color_2(){
    mt_srand((double)microtime()*1000000);
    return mt_rand(0, 255).", ".mt_rand(0, 255).', '.mt_rand(0, 255);
}
function random_string($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890'){
    // Length of character list
    $chars_length = (strlen($chars) - 1);

    // Start our string
    $string = $chars{rand(0, $chars_length)};
    
    // Generate random string
    for ($i = 1; $i < $length; $i = strlen($string))
    {
        // Grab a random character from our list
        $r = $chars{rand(0, $chars_length)};
        
        // Make sure the same two characters don't appear next to each other
        if ($r != $string{$i - 1}) $string .=  $r;
    }
    
    // Return the string
    return $string;
}

//---------------CODIFICA
function mimmo_encode($str){
    
$data = date("d/m/Y H:i:s");
return base64_encode("$data|$str|$data|$data");   
    
}
function mimmo_decode($str){
    
$cookie_read =explode("|", base64_decode($str));
return $cookie_read[1];   
    
}
function mimmo_decode_data($str){
    
$cookie_read =explode("|", base64_decode($str));
return $cookie_read[3];   
    
}



//VARIABLES
function vname(&$var, $scope=false, $prefix='unique', $suffix='value'){
    if($scope) $vals = $scope;
    else      $vals = $GLOBALS;
    $old = $var;
    $var = $new = $prefix.rand().$suffix;
    $vname = FALSE;
    foreach($vals as $key => $val) {
      if($val === $new) $vname = $key;
    }
    $var = $old;
    return $vname;
  }
function array_insert($arr, $insert, $position) {
    foreach ($arr as $key => $value) {
            if ($i == $position) {
                    foreach ($insert as $ikey => $ivalue) {
                            $ret[$ikey] = $ivalue;
                    }
            }
            $ret[$key] = $value;
            $i++;
    }
    return $ret;
}
//-------------CASTING 
function CAST_TO_INT($var, $min = FALSE, $max = FALSE){
    $var = is_int($var) ? $var : (int)(is_scalar($var) ? $var : 0);
    if ($min !== FALSE && $var < $min)
        return $min;
        
    elseif($max !== FALSE && $var > $max)
        return $max;
        
    return $var;
        
}
function CAST_TO_FLOAT($var, $min = FALSE, $max = FALSE){
    $var = is_float($var) ? $var : (float)(is_scalar($var) ? $var : 0);
    if ($min !== FALSE && $var < $min)
        return $min;
    elseif($max !== FALSE && $var > $max)
        return $max;
    return $var;
}
function CAST_TO_BOOL($var){
    return (bool)(is_bool($var) ? $var : is_scalar($var) ? $var : FALSE);
}
function CAST_TO_STRING($var, $length = FALSE){
    if ($length !== FALSE && is_int($length) && $length > 0)
        return substr(trim(is_string($var)
                    ? $var
                    : (is_scalar($var) ? $var : '')), 0, $length);

    return trim(
                is_string($var)
                ? $var
                : (is_scalar($var) ? $var : ''));
}
function CAST_TO_ARRAY($var){
    return is_array($var)
            ? $var
            : is_scalar($var) && $var
                ? array($var)
                : is_object($var) ? (array)$var : array();
}
function CAST_TO_OBJECT($var){
    return is_object($var)
            ? $var
            : is_scalar($var) && $var
                ? (object)$var
                : is_array($var) ? (object)$var : (object)NULL;
}


//MESSAGES
function convert_message($msg){
if(strlen($msg)==2){
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
}  
}      
return $msg;    
}
function go($where,$id_user=NULL,$msg=NULL,$opt=NULL){
    global $RG_addr;
    $msg = sanitize($msg);
    
    if(!is_empty($id_user)){
        write_option_text($id_user,"MSG",$msg);
        sleep(1);    
    }
    
    
    //TOPHOST con PHP 5.3 ha problemi; sembra che non mandi l'header, senza dare nessun errore.
    //Risolto con un redirect da parte del browser;
    if( sizeof( $_POST ) == 0 ){
        header ('Location : '. $RG_addr["$where"].$opt);
    }
    else{
        //header ('Location : '. $RG_addr["$where"].$opt);
        echo '<html><head><meta http-equiv="refresh" content="0;url=' . $RG_addr["$where"].$opt . '"/></head></html>';
    }
    exit();
}    
function l($var){
    global $class_debug;
    $class_debug->debug_msg[]=microtime()." ".$var;
}

//UPLOAD
function do_upload($fil,$lis){
 global $db;   
$row = 0;
$fd = fopen ($fil, "r");

//SALTO LA PRIMA RIGA
$data = fgetcsv($fd, 1000, _USER_CSV_SEPARATOR,_USER_CSV_DELIMITER);

// initialize a loop to go through each line of the file
 while (($data = fgetcsv($fd, 1000, _USER_CSV_SEPARATOR,_USER_CSV_DELIMITER)) !== FALSE){
    $num = count($data);
    $row++;
    
    $data[0]=ereg_replace("[^àèéìòùA-Za-z0-9.,-_!$%()= ]"," ",$data[0]);
    $data[1]=ereg_replace("[^àèéìòùA-Za-z0-9.,-_!$%()= ]"," ",$data[1]);
    $data[2]=floatval(trim(str_replace(array(",","?"),array(".",""),$data[2]))); 
    $data[3]=ereg_replace("[^àèéìòùA-Za-z0-9.,-_!$%()= ]"," ",$data[3]);
    $data[4]=trim(str_replace(array(",","?"),array(".",""),$data[4]));    
    $data[5]=ereg_replace("[^àèéìòùA-Za-z0-9.,-_!$%()= ]"," ",$data[5]); 
    $data[6]=floatval(trim(str_replace(array(","),array("."),$data[6]))); 
    $data[7]=floatval(trim(str_replace(array(","),array("."),$data[7])));
    $data[8]=sanitize(html_entity_decode($data[8]));
    if(trim($data[9])<>""){$data[9]="1";}else{$data[9]="";}
    $data[10]=sanitize($data[10]);
    $data[11]=sanitize($data[11]);
    $data[12]=sanitize($data[12]);
    
    $result = $db->sql_query("INSERT INTO retegas_articoli
     (id_listini,codice,descrizione_articoli,prezzo,u_misura,misura,ingombro,qta_scatola,qta_minima,articoli_note,articoli_unico)
     VALUES
     ($lis,'$data[0]','$data[1]',$data[2],'$data[3]','$data[4]','$data[5]','$data[6]','$data[7]','$data[8]','$data[9]')");                               
    
 if (!$result){$err++;}
 }
 fclose ($fd);
 if ($err<>0){
     $msg="Problemi nell'inserimento articoli";
 }else{
 $msg="$row Articoli inseriti nel listino $lis";   
 }
    
return $msg;
}
function do_upload_xls($fil,$lis){
    
$row = 0;

require_once '../lib/excel/reader.php';
$data_xls = new Spreadsheet_Excel_Reader();
$data_xls->setOutputEncoding('CP1251');
$data_xls->read($fil);


for ($i_x = 2; $i_x <= $data_xls->sheets[0]['numRows']; $i_x++) {
    
    $row++;
    
    $data[0]=strip_tags(ereg_replace("[^àèéìòùA-Za-z0-9.,-_!$%()= ]"," ",$data_xls->sheets[0]['cells'][$i_x][1]));
    $data[1]=strip_tags(ereg_replace("[^àèéìòùA-Za-z0-9.,-_!$%()= ]"," ",$data_xls->sheets[0]['cells'][$i_x][2]));
    $data[2]=floatval(trim(str_replace(array(",","?"),array(".",""),$data_xls->sheets[0]['cells'][$i_x][3]))); 
    $data[3]=ereg_replace("[^àèéìòùA-Za-z0-9.,-_!$%()= ]"," ",$data_xls->sheets[0]['cells'][$i_x][4]);
    $data[4]=trim(str_replace(array(",","€"),array(".",""),$data_xls->sheets[0]['cells'][$i_x][5]));    
    $data[5]=strip_tags(sanitize($data_xls->sheets[0]['cells'][$i_x][6])); 
    $data[6]=floatval(trim(str_replace(array(","),array("."),$data_xls->sheets[0]['cells'][$i_x][7]))); 
    $data[7]=floatval(trim(str_replace(array(","),array("."),$data_xls->sheets[0]['cells'][$i_x][8])));
    $data[8]=strip_tags(sanitize($data_xls->sheets[0]['cells'][$i_x][9]));
    if(trim($data_xls->sheets[0]['cells'][$i_x][10])=="UNICO"){$data[9]="1";}else{$data[9]="";}
    $data[10]=strip_tags(sanitize($data_xls->sheets[0]['cells'][$i_x][11]));
    $data[11]=strip_tags(sanitize($data_xls->sheets[0]['cells'][$i_x][12]));
    $data[12]=strip_tags(sanitize($data_xls->sheets[0]['cells'][$i_x][13]));
    
    
    
    
    $result = mysql_query("INSERT INTO retegas_articoli
     (id_listini,codice,descrizione_articoli,prezzo,u_misura,misura,ingombro,qta_scatola,qta_minima,articoli_note,articoli_unico,articoli_opz_1,articoli_opz_2,articoli_opz_3)
     VALUES
     ($lis,'$data[0]','$data[1]',$data[2],'$data[3]','$data[4]','$data[5]','$data[6]','$data[7]','$data[8]','$data[9]','$data[10]','$data[11]','$data[12]')");                               
    
 if (!$result){$err++;}
 }

 if ($err<>0){
     $msg="Problemi nell'inserimento articoli";
 }else{
 $msg="$row Articoli inseriti nel listino $lis";   
 }
    
return $msg;
}
function do_upload_goo($fil,$lis,$quanti_caricarne){
    
$row = 0;
$fd = fopen ($fil, "r");

//SALTA LA PRIMA RIGA
$data = fgetcsv($fd, 1000, ",");

// initialize a loop to go through each line of the file
 while (($data = fgetcsv($fd, 1000, ",")) !== FALSE){
    $num = count($data);
    $row++;
    
    $data[0]=ereg_replace("[^àèéìòùA-Za-z0-9.,-_!$%()= ]"," ",$data[0]);
    $data[1]=ereg_replace("[^àèéìòùA-Za-z0-9.,-_!$%()= ]"," ",$data[1]);
    $data[2]=floatval(trim(str_replace(array(",","?"),array(".",""),$data[2]))); 
    $data[3]=ereg_replace("[^àèéìòùA-Za-z0-9.,-_!$%()= ]"," ",$data[3]);
    $data[4]=trim(str_replace(array(",","?"),array(".",""),$data[4]));    
    $data[5]=ereg_replace("[^àèéìòùA-Za-z0-9.,-_!$%()= ]"," ",$data[5]); 
    $data[6]=floatval(trim(str_replace(array(","),array("."),$data[6]))); 
    $data[7]=floatval(trim(str_replace(array(","),array("."),$data[7])));
    $data[8]=sanitize($data[8]);
    if(trim($data[9])=="UNICO"){$data[9]="1";}else{$data[9]="";}
    $data[10]=ereg_replace("[^àèéìòùA-Za-z0-9.,-_!$%()= ]"," ",$data[10]);
    $data[11]=ereg_replace("[^àèéìòùA-Za-z0-9.,-_!$%()= ]"," ",$data[11]);
    $data[12]=ereg_replace("[^àèéìòùA-Za-z0-9.,-_!$%()= ]"," ",$data[12]);
    
    
    
    $result = mysql_query("INSERT INTO retegas_articoli
                                (id_listini,
                                 codice,
                                 descrizione_articoli,
                                 prezzo,
                                 u_misura,misura,
                                 ingombro,
                                 qta_scatola,
                                 qta_minima,
                                 articoli_note,
                                 articoli_unico,
                                 articoli_opz_1,
                                 articoli_opz_2,
                                 articoli_opz_3)
                                 VALUES
                                ($lis,
                                '$data[0]',
                                '$data[1]',
                                 $data[2],
                                '$data[3]',
                                '$data[4]',
                                '$data[5]',
                                '$data[6]',
                                '$data[7]',
                                '$data[8]',
                                '$data[9]',
                                '$data[10]',
                                '$data[11]',
                                '$data[12]')");                               
    
    if (!$result){$err++;}
    if(($row-1)>$quanti_caricarne){
        $err++;
        break;
    }
 }

 if ($err<>0){
     $msg="Problemi nell'inserimento $row articoli";
 }else{
 $msg="$row Articoli inseriti nel listino $lis";   
 }
fclose($fd);    
return $msg;
}


//LOG
function html_log($limite,$usr,$id_user){
    global $db;
 $qry="SELECT * FROM retegas_messaggi
      WHERE retegas_messaggi.tipo2='MOD'
      ORDER BY
      retegas_messaggi.timbro DESC
      LIMIT $limite";     
$res = $db->sql_query($qry);


while ($row = mysql_fetch_array($res)){
//if (ordini_gas_user() $row[2]){}
//echo $row[2]."-".$id_user."<br>";
$quando = conv_datetime_from_db($row[4]);
$level = ordine_io_cosa_sono($row[2],$id_user) ;
if ($level>0){

if ($level==1){
$stile="color:#888888";    
}
if ($level==2){
$stile="color:#808080";    
}
if ($level==3){
$stile="color:#505050";    
}
if ($level==4){
$stile="color:#101010";    
}
if(stato_from_id_ord($row[2])==2){
$href="<a href=\"ordini_aperti/ordini_aperti_form.php?id=$row[2]\" style=\"$stile\">";    
}else{
$href="<a href=\"ordini_chiusi/ordini_chiusi_form.php?id=$row[2]\" style=\"$stile\">";        
}


    
$hi .= "<div class=\"ui-widget ui-corner-all\" style=\"$stile;margin-bottom:3px;\">
        $href
        <b style=\"color:#636363;\">$quando</b>, $row[3]
        </a>
        </div>";     
}

    
}    
    
return $hi;    
}
function log_me($id_ordine,$id_user,$tipo,$tipo2=null,$messaggio,$valore=null,$query_ins=null){
 
    global $db;
    $messaggio = sanitize($messaggio);
    $query_ins=sanitize($query_ins);
    //echo "SONO QUI ".$messaggio." ".$id_user; 
 $query = "INSERT INTO  retegas_messaggi (
                        id_ordine,                    
                        id_user,
                        tipo,
                        tipo2,
                        valore,
                        messaggio,
                        query,
                        timbro
            )
            VALUES (    '$id_ordine',                    
                        '$id_user',
                        '$tipo',
                        '$tipo2',
                        '$valore',
                        '$messaggio',
                        '$query_ins',
                         NOW()
                    );"; 
$result = $db->sql_query($query);  
    
}
function show_log_ordine($id_ordine,$tipo,$n_res){
global $db;
$qry="SELECT * FROM retegas_messaggi
      WHERE
      retegas_messaggi.tipo LIKE '$tipo%'
      AND
      retegas_messaggi.id_ordine= '$id_ordine'
      ORDER BY
      retegas_messaggi.timbro DESC
      LIMIT $n_res;";     
$res = $db->sql_query($qry);

$hi = "<div class=\"ui-widget-header ui-corner-all padding_6px\" style = \"margin-bottom:6px;\">Cronologia ordine</div>";

while ($row = mysql_fetch_array($res)){

$quando = conv_datetime_from_db($row[4]);    
$hi .= "<div class=\"ui-widget ui-corner-all\" style=\"color:#D0D0D0\">
        <b style=\"color:#969696\">$quando</b>, $row[3]</div>";     
    
}
return $hi;    
    
}

//DATABASE

function db_splat_table($table,$filter=null,$lenght = 10){
    global $db;
    
    $sql = "SELECT * FROM $table WHERE $filter;";
    $res = $db->sql_query($sql);
    $fields = $db->sql_numfields($res);
    
    $t .="<strong>";
    for($i=0;$i<$fields;$i++){
            $t .= $db->sql_fieldname($i,$res)." - ";     
        } 
    $t .="</strong>
            <br>";
              
    while ($row = mysql_fetch_array($res)){
    
        for($i=0;$i<$fields;$i++){
            $t .= strip_tags(myTruncate($row[$i],$lenght," "))." - ";      
        }    
        $t .="<br>";
    }
    
    return $t;
}
function db_nr_q($field,$key,$table){
    global $db;
    return (int)$db->sql_numrows($db->sql_query("SELECT * FROM $table WHERE $field='$key';"));
}
function db_nr_q_2($field,$key,$field2,$key2,$table){
    global $db;
    return (int)$db->sql_numrows($db->sql_query("SELECT * FROM $table WHERE $field='$key' AND $field2='$key2';"));
}
function db_nr_q_3($field,$key,$field2,$key2,$field3,$key3,$table){
    global $db;
    return (int)$db->sql_numrows($db->sql_query("SELECT * FROM $table WHERE $field='$key' AND $field2='$key2' AND $field3='$key3';"));
}

function db_nr_q_condition($condition,$table){
    global $db;
    return (int)$db->sql_numrows($db->sql_query("SELECT * FROM $table WHERE $condition;"));
}
function db_val_q($field,$key,$field_target,$table){
    global $db;
    $res = $db->sql_query("SELECT $field_target FROM $table WHERE $field='$key';");
    //echo "SELECT $field_target FROM $table WHERE $field='$key';";
    
    $nrow = $db->sql_numrows($res);
    
    if($nrow>1){
        return "* NON UNIVOCO *";
        exit;
    }else{
      if($nrow<1){
            return "* NESSUN RECORD *";
            exit;    
        }else{
            $row = $db->sql_fetchrow($res);
            
            return $row[0];
            exit;    
        }  
    }
    
}
function db_insert_cassa_utenti($id_utente,
                                $id_ordine,
                                $id_cassiere,
                                $id_gas,
                                $id_ditta,
                                $importo,
                                $segno,
                                $tipo_movimento,
                                $escludi_gas,
                                $descrizione_movimento=null,
                                $note_movimento = null,
                                $numero_documento = null,
                                $registrato = null,
                                $contabilizzato = null
                                ){
global $db;

(int)$id_utente;
(int)$tipo_movimento;
(int)$id_ordine;
(int)$id_cassiere;
(int)$escludi_gas;
(int)$id_gas;
(int)$id_ditta;

$importo = sanitize($importo);
$descrizione_movimento = sanitize($descrizione_movimento);
$note_movimento = sanitize($note_movimento);
$numero_documento = sanitize($numero_documento);

if($registrato == "si"){
    $r = "'si'";
    $d_r = "NOW()";
}else{
    $r = "'no'";
    $d_r = "NULL";
}

if($contabilizzato == "si"){
    $c = "'si'";
    $d_c = "NOW()";
}else{
    $c = "'no'";
    $d_c = "NULL";
}

if(is_empty($note_movimento)){
    $note_movimento = "NULL";
}else{
    $note_movimento = "'".$note_movimento."'";
}

if(is_empty($descrizione_movimento)){
    $descrizione_movimento = "NULL";
}else{
    $descrizione_movimento = "'".$descrizione_movimento."'";
}

if(is_empty($numero_documento)){
    $numero_documento = "NULL";
}else{
    $numero_documento = "'".$numero_documento."'";
}




$sql = 'INSERT INTO `retegas_cassa_utenti` 
                                    (`id_cassa_utenti`, 
                                     `id_utente`, 
                                     `id_gas`, 
                                     `id_ditta`, 
                                     `importo`, 
                                     `segno`, 
                                     `tipo_movimento`, 
                                     `escludi_gas`, 
                                     `descrizione_movimento`, 
                                     `note_movimento`, 
                                     `data_movimento`, 
                                     `numero_documento`, 
                                     `id_ordine`, 
                                     `id_cassiere`, 
                                     `registrato`, 
                                     `data_registrato`, 
                                     `contabilizzato`, 
                                     `data_contabilizzato`) 
                                     VALUES 
                                     (NULL, 
                                     \''.$id_utente.'\', 
                                     \''.$id_gas.'\', 
                                     \''.$id_ditta.'\', 
                                     \''.$importo.'\', 
                                     \''.$segno.'\', 
                                     \''.$tipo_movimento.'\', 
                                     \''.$escludi_gas.'\', 
                                     '.$descrizione_movimento.', 
                                     '.$note_movimento.', 
                                     NOW(), 
                                     '.$numero_documento.', 
                                     \''.$id_ordine.'\', 
                                     \''.$id_cassiere.'\', 
                                     '.$r.', 
                                     '.$d_r.', 
                                     '.$c.', 
                                     '.$d_c.');';

//echo $sql."<br>";
                                     
$res = $db->sql_query($sql);
if (is_null($res)){
    return false;
}else{
    return true;
}                                     
    
    
}

//ARRAYS
function moveDown($input,$index) {
      $new_array = $input;
      
       if((count($new_array)>$index) && ($index>0)){
                 array_splice($new_array, $index-1, 0, $input[$index]);
                 array_splice($new_array, $index+1, 1);
             } 

       return $new_array;
}
function moveUp($input,$index) {
       $new_array = $input;
         
       if(count($new_array)>$index) {
                 array_splice($new_array, $index+2, 0, $input[$index]);
                 array_splice($new_array, $index, 1);
             } 
   
       return $new_array;
 }
 
 
//TEXT
function myTruncate($string, $limit, $break=".", $pad="..."){
  // return with no change if string is shorter than $limit
  if(strlen($string) <= $limit) return $string;

  // is $break present between $limit and the end of the string?
  if(false !== ($breakpoint = strpos($string, $break, $limit))) {
    if($breakpoint < strlen($string) - 1) {
      $string = substr($string, 0, $breakpoint) . $pad;
    }
  }
    
  return $string;
}


//URLS
function getCurrentPageURL(){ 
    $pageURL = 'http'; 
 
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") { 
      $pageURL .= "s"; 
    } 
 
    $pageURL .= "://"; 
 
    if ($_SERVER["SERVER_PORT"] != "80") { 
      $pageURL .= $_SERVER["SERVER_NAME"].":" 
            .$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
    } 
    else { 
      $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; 
    } 
 
    return $pageURL;     
  } 
function file_exists_2($dir, $file) 
   { 
       $ret = exec("ls ".$dir." | grep ".$file); 
       return (!empty($ret)); 
   } 
   
//OUTPUTS
function query_to_csv($query, $filename, $attachment = false, $headers = true) {
        
    global $db;    
    
        if($attachment) {
            // send response headers to the browser
            header( 'Content-Type: text/csv' );
            header( 'Content-Disposition: attachment;filename='.$filename);
            $fp = fopen('php://output', 'w');
        } else {
            $fp = fopen($filename, 'w');
        }
        
        $result = $db->sql_query($query);
        
        if($headers) {
            // output header row (if at least one row exists)
            $row = mysql_fetch_assoc($result);
            if($row) {
                fputcsv($fp, array_keys($row));
                // reset pointer back to beginning
                mysql_data_seek($result, 0);
            }
        }
        
        while($row = mysql_fetch_assoc($result)) {
            fputcsv($fp, $row);
        }
        
        fclose($fp);
    }

   
function select_language($id_user){
    global $ROOT_DIR,$db;
    $language = read_option_text($id_user,"LANG");
    
        switch($language){
            case "italian":
                include_once("$ROOT_DIR/lang/italian.php");
                break;
            case "valsesian":
                include_once ("$ROOT_DIR/lang/valsesian.php");
                break;
            default:
                include_once ("$ROOT_DIR/lang/italian.php");
                break;    
        }    
}   

class chip_password_generator {
    
    /*
    |---------------------------
    | Properties
    |---------------------------
    */
    
    private $args = array(
                        'length'                =>    8,
                        'alpha_upper_include'    =>    TRUE,
                        'alpha_lower_include'    =>    TRUE,                        
                        'number_include'        =>    TRUE,
                        'symbol_include'        =>    TRUE,    
                    );
    
    private $alpha_upper = array( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z" );
    private $alpha_lower = array( "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z" );
    private $number = array( 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 );
    private $symbol = array( "-", "_", "^", "~", "@", "&", "|", "=", "+", ";", "!", ",", "(", ")", "{", "}", "[", "]", ".", "?", "%", "*", "#" );
    private $input = 4;

    /*
    |---------------------------
    | Constructor
    |
    | @public
    | @param array $args
    |
    |---------------------------
    */
    
    public function __construct( $args = array() ) {
        
        $this->set_args( $args );
                        
    }
    
    /*
    |---------------------------
    | Print variable in readable format
    |
    | @public
    | @param string|array|object $var
    |
    |---------------------------
    */
    
    public function chip_print( $var ) { 
        
        echo "<pre>";
        print_r($var);
            echo "</pre>";
    
    }
    
    /*
    |---------------------------
    | Update default arguments
    | It will update default array of class i.e $args
    |
    | @private
    | @param array $args - input arguments
    | @param array $defatuls - default arguments 
    | @return array
    |
    |---------------------------
    */
    
    private function chip_parse_args( $args = array(), $defaults = array() ) { 
        return array_merge( $defaults, $args );     
    }
    
    /*
    |---------------------------
    | Set default arguments
    | It will set default array of class i.e $args
    |
    | @private
    | @param array $args
    | @return 0
    |
    |---------------------------
    */
    
    private function set_args( $args = array() ) { 
        
        $defaults = $this->get_args();
        $args = $this->chip_parse_args( $args, $defaults );
        $this->args = $args;     
    }
    
    /*
    |---------------------------
    | Get default arguments
    | It will get default array of class i.e $args
    |
    | @public
    | @return array
    |
    |---------------------------
    */
    
    public function get_args() { 
        return $this->args;     
    }
    
    /*
    |---------------------------
    | Get Alpha Upper Array
    | It will get default array of $alpha_upper
    |
    | @private
    | @return array
    |
    |---------------------------
    */
    
    private function get_alpha_upper() { 
        return $this->alpha_upper;     
    }
    
    /*
    |---------------------------
    | Get Alpha Lower Array
    | It will get default array of $alpha_lower
    |
    | @private
    | @return array
    |
    |---------------------------
    */
    
    private function get_alpha_lower() { 
        return $this->alpha_lower;     
    }
    
    /*
    |---------------------------
    | Get Number Array
    | It will get default array of $number
    |
    | @private
    | @return array
    |
    |---------------------------
    */
    
    private function get_number() { 
        return $this->number;     
    }
    
    /*
    |---------------------------
    | Get Symbol Array
    | It will get default array of $symbol
    |
    | @private
    | @return array
    |
    |---------------------------
    */
    
    private function get_symbol() { 
        return $this->symbol;     
    }
    
    /*
    |---------------------------
    | Generate Password
    | It will generate password
    |
    | @private
    | @return array
    |
    |---------------------------
    */
    
    private function set_password() { 
        
        /* Temporary Array(s) */
        $temp = array();
        $exec = array();
        
        /* Arguments */
        $args = $this->get_args();     
        extract($args);
        
        /* Minimum Validation */        
        if( $length <= 0 ) {
            return 0;
        }
        
        /* Execution Array Logic */
        
        /* Alpha Upper */
        if( $alpha_upper_include == TRUE ) {
            $alpha_upper = $this->get_alpha_upper();
            $exec[] = 1;
        }
        
        /* Alpha Lower */
        if( $alpha_lower_include == TRUE ) {
            $alpha_lower = $this->get_alpha_lower();
            $exec[] = 2;
        }
        
        /* Number */
        if( $number_include == TRUE ) {
            $number = $this->get_number();
            $exec[] = 3;
        }
        
        /* Symbol */
        if( $symbol_include == TRUE ) {
            $symbol = $this->get_symbol();
            $exec[] = 4;
        }
        
        /* Unique and Random Loop */
        $exec_count = count( $exec ) - 1;
        $input_index = 0;
        //$this->chip_print( $exec );
        
        for ( $i = 1; $i <= $length; $i++ ) {
            
            switch( $exec[$input_index] ) {
                
                case 1:                
                shuffle( $alpha_upper );
                $temp[] = $alpha_upper[0];
                unset( $alpha_upper[0] );                
                break;
                
                case 2:                
                shuffle( $alpha_lower );
                $temp[] = $alpha_lower[0];
                unset( $alpha_lower[0] );                
                break;
                
                case 3:                
                shuffle( $number );
                $temp[] = $number[0];
                unset( $number[0] );                
                break;
                
                case 4:                
                shuffle( $symbol );
                $temp[] = $symbol[0];
                unset( $symbol[0] );                
                break;
                
            }
            
            if ( $input_index < $exec_count ) {
                $input_index++;
            } else {
                $input_index = 0;
            }
        
        } // for ( $i = 1; $i <= $length; $i++ )
        
        /* Shuffle */
        shuffle($temp);
        
        /* Make Password */        
        $password = implode( $temp );
        
        return $password;
        
    }    
    
    /*
    |---------------------------
    | Generate Password
    | It will generate password
    |
    | @public
    | @return array
    |
    |---------------------------
    */
    
    public function get_password() {         
        return $this->set_password();        
    }    
    

    /*
    |---------------------------
    | Destructor
    |---------------------------
    */
    
    public function __destruct() {
    }
}

function calcola_cf($nome,$cognome,$comune,$sesso,$nascita){
    
    return file_get_contents("http://webservices.dotnethell.it/codicefiscale.asmx/CalcolaCodiceFiscale?Nome=".$nome."&Cognome=".$cognome."&ComuneNascita=".$comune."&DataNascita=".$nascita."&Sesso=".$sesso);
    
}

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);
 
   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   return implode(",", $rgb); // returns the rgb values separated by commas
   //return $rgb; // returns an array with the rgb values
}

function lista_assonanze($fullname,$percentuale_max){
    global $db;
    //ASSONANZE 
    $sql = "SELECT * FROM maaking_users";
    $res = $db->sql_query($sql);
    $assonanza = array();
    while ($row = $db->sql_fetchrow($res)){
        similar_text($row["fullname"], $fullname, $percent);   
        if($percent>$percentuale_max){ 
            $assonanza[$row["fullname"]]= _nf($percent);
        }  
    }
    arsort($assonanza);    
    foreach ($assonanza as $key => $value) {
        $ass .= "$key : $value<br/>";
    }
 //ASSONANZE
 return $ass;
  
}

function quante_assonanze($fullname,$percentuale_max){
    global $db;
    //ASSONANZE 
    $sql = "SELECT * FROM maaking_users";
    $res = $db->sql_query($sql);
    $assonanza = array();
    while ($row = $db->sql_fetchrow($res)){
        similar_text($row["fullname"], $fullname, $percent);   
        if($percent>$percentuale_max){ 
            $assonanza[$row["fullname"]]= _nf($percent);
        }  
    }
    
 //ASSONANZE
 $n = count($assonanza); 
 return  $n;
}


//CURL for GOOGLE MAPS
function curl_request($sURL,$sQueryString=null)
{
        $cURL=curl_init();
        curl_setopt($cURL,CURLOPT_URL,$sURL.'?'.$sQueryString);
        //echo $sURL.'?'.$sQueryString;
        
        curl_setopt($cURL,CURLOPT_RETURNTRANSFER, TRUE);
        $cResponse=trim(curl_exec($cURL));
        curl_close($cURL);
        return $cResponse;
}

// Declare the class
class GoogleUrlApi {
  
  // Constructor
  function GoogleURLAPI($key,$apiURL = 'https://www.googleapis.com/urlshortener/v1/url') {
    // Keep the API Url
    $this->apiURL = $apiURL.'?key='.$key;
  }
  
  // Shorten a URL
  function shorten($url) {
    // Send information along
    $response = $this->send($url);
    // Return the result
    return isset($response['id']) ? $response['id'] : false;
  }
  
  // Expand a URL
  function expand($url) {
    // Send information along
    $response = $this->send($url,false);
    // Return the result
    return isset($response['longUrl']) ? $response['longUrl'] : false;
  }
  
  // Send information to Google
  function send($url,$shorten = true) {
    // Create cURL
    $ch = curl_init();
    // If we're shortening a URL...
    if($shorten) {
      curl_setopt($ch,CURLOPT_URL,$this->apiURL);
      curl_setopt($ch,CURLOPT_POST,1);
      curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode(array("longUrl"=>$url)));
      curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/json"));
    }
    else {
      curl_setopt($ch,CURLOPT_URL,$this->apiURL.'&shortUrl='.$url);
    }
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    // Execute the post
    $result = curl_exec($ch);
    // Close the connection
    curl_close($ch);
    // Return the result
    return json_decode($result,true);
  }    
}


function IDS($id_gas,$giorni_indietro=30,$finestra=30){
    
     global $db;
     $data_da =strtotime("-$giorni_indietro day", strtotime("now"));
     $data_a = strtotime("+$finestra day",$data_da);
   
     $num_utenti_gas_alla_data = gas_n_user_data($id_gas,conv_date_to_db(date("d/m/Y",$data_a)));
     //echo "Data_da -> $data_da<br>
     //      Data_a -> $data_a<br>
     //      User attivi -> $num_utenti_gas_alla_data<br>";
    
     $data_partenza = conv_date_to_db(date("d/m/Y",$data_da));
     $data_fine =  conv_date_to_db(date("d/m/Y",$data_a));
     //echo "Data_da -> $data_partenza<br>
     //      Data_a -> $data_fine<br>
     //      User attivi -> $num_utenti_gas_alla_data<br>";
     $sql_oa = "SELECT
                    Count(retegas_ordini.id_ordini) AS conteggio_ordini,
                    retegas_referenze.id_utente_referenze
                    FROM
                    retegas_ordini
                    Inner Join retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze
                    Inner Join maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid
                    WHERE
                    maaking_users.id_gas =  '$id_gas'
                    AND data_apertura BETWEEN '$data_partenza' AND '$data_fine'
                    GROUP BY
                    retegas_referenze.id_utente_referenze";
                    //echo "SQL - $sql_oa<br>";
     $res_oa = $db->sql_query($sql_oa);
     $ut_gestori = $db->sql_numrows($res_oa);
     
     $sql_aiuti = "SELECT * from retegas_options 
                                Inner Join maaking_users ON retegas_options.id_user = maaking_users.userid
                                WHERE chiave='AIUTO_ORDINI' AND timbro BETWEEN '$data_partenza' AND '$data_fine'
                                AND maaking_users.id_gas='$id_gas'";
     $res_aiuti = $db->sql_query($sql_aiuti);
     $num_aiuti = $db->sql_numrows($res_aiuti);
     
     $ids = round((($ut_gestori + $num_aiuti) / $num_utenti_gas_alla_data)*100,1);
     
    return $ids;       
     
    
}