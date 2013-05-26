<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("listini_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if (!(_USER_PERMISSIONS & perm::puo_creare_listini)){
     pussa_via();
}

(int)$id;
(int)$listino;
if(isset($listino)){$id=$listino;}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = 1;
//Assegno il titolo che compare nella barra delle info
$r->title = "Carica listino";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
$r->menu_orizzontale[] = listini_menu($user,$id);

//Assegno le due tabelle a tablesorter
//$r->javascripts[]=java_tablesorter("output_1");


$r->messaggio = $msg;
//Creo la pagina dell'aggiunta

//-------------------------------------------------------------------IMPORT
if(!isset($_FILES)) $_FILES = $HTTP_POST_FILES;
if(!isset($_SERVER)) $_SERVER = $HTTP_SERVER_VARS;
$upload_dir = "./uploads";

// Eventuale nuovo nome da dare al file uploadato
$new_name = time()+rand(0,100000).".xls";
$old_name_csv = $_FILES["upfile"]["name"];

// Se $new_name è vuota, il nome sarà lo stesso del file uploadato
$file_name = ($new_name) ? $new_name : $_FILES["upfile"]["name"];
$filone = $upload_dir."/".$file_name; 

if(trim($_FILES["upfile"]["name"]) == "") {
    die("Non hai indicato il file da uploadare !");
}
//TODO: sostituire con GO
if(@is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
    @move_uploaded_file($_FILES["upfile"]["tmp_name"], "$upload_dir/$file_name") 
or die("Impossibile spostare il file, controlla l'esistenza o i permessi della directory dove fare l'upload.");
} else {
    die("Problemi nell'upload del file " . $_FILES["upfile"]["name"]);
}

$row = 0;

// ---Controllo errori-----------------------------------------------------------------------------------------------------
$erro_vuoti = 0;
$erro_num = 0;
$erro_doppi = 0;
$erro_zero=0;
$warn_string = 0;
$erro_multi =0;
$unici=0;
$array_articoli[0]="";  

// parte per excel
require_once '../lib/excel/reader.php';
//require_once '../lib/excel_2/excel_reader2.php';

// ExcelFile($filename, $encoding);
$data_xls = new Spreadsheet_Excel_Reader();

// Set output Encoding.
$data_xls->setOutputEncoding('CP1251');

/***
* if you want you can change 'iconv' to mb_convert_encoding:
* $data->setUTFEncoder('mb');
*
**/

/***
* By default rows & cols indeces start with 1
* For change initial index use:
* $data->setRowColOffset(0);
*
**/



/***
*  Some function for formatting output.
* $data->setDefaultFormat('%.2f');
* setDefaultFormat - set format for columns with unknown formatting
*
* $data->setColumnFormat(4, '%.3f');
* setColumnFormat - set format for column (apply only to number fields)
*
**/

$data_xls->read($upload_dir."/".$file_name);

/*


 $data->sheets[0]['numRows'] - count rows
 $data->sheets[0]['numCols'] - count columns
 $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

 $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell
    
    $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
        if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
    $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format 
    $data->sheets[0]['cellsInfo'][$i][$j]['colspan'] 
    $data->sheets[0]['cellsInfo'][$i][$j]['rowspan'] 
*/

//for ($i = 1; $i <= $data_xls->sheets[0]['numRows']; $i++) {
//    for ($j = 1; $j <= $data_xls->sheets[0]['numCols']; $j++) {
//        echo "\"".$data_xls->sheets[0]['cells'][$i][$j]."\",";
//    }
//    echo "<br>";

//}
//print_r($data_xls);
//print_r($data_xls->formatRecords);


if($data_xls->sheets[0]['numRows']>1000){
    $erro_troppi++;
}

// initialize a loop to go through each line of the file
for ($i_x = 2; $i_x <= $data_xls->sheets[0]['numRows']; $i_x++) {
    $num = $data_xls->sheets[0]['numRows'];
    $row++;

        if ($data_xls->sheets[0]['cells'][$i_x][1]==""){$erro_vuoti++;}
        if ($data_xls->sheets[0]['cells'][$i_x][2]==""){$erro_vuoti++;}
        if ($data_xls->sheets[0]['cells'][$i_x][3]==""){$erro_vuoti++;}
        if ($data_xls->sheets[0]['cells'][$i_x][4]==""){$erro_vuoti++;}
        if ($data_xls->sheets[0]['cells'][$i_x][5]==""){$erro_vuoti++;}
        //if ($data_xls->sheets[0]['cells'][$i_x][6]==""){$erro_vuoti++;}
        if ($data_xls->sheets[0]['cells'][$i_x][7]==""){$erro_vuoti++;}
        if ($data_xls->sheets[0]['cells'][$i_x][8]==""){$erro_vuoti++;}
        
        // SE LA PRIMA OPZIONE ESISTE MA LE ALTRE MANCANO ALLORA ERRORE
        if (($data_xls->sheets[0]['cells'][$i_x][11]<>"")
            AND (   ($data_xls->sheets[0]['cells'][$i_x][12]=="" | empty($data_xls->sheets[0]['cells'][$i_x][12])) 
                    OR ($data_xls->sheets[0]['cells'][$i_x][13]=="" | empty($data_xls->sheets[0]['cells'][$i_x][13]))
                    )
            ){$erro_opzioni++;}
            
        if ($data_xls->sheets[0]['cells'][$i_x][11]<>""){$articolo_composto= true;}else{$articolo_composto=false;};
        
        
        //if (ereg("[^àèìòùA-Za-z0-9.,-_!$%()= ]",  $data_xls->sheets[0]['cells'][$i_x][1])>0){$warn_string++;}  //codice
        //if (ereg("[^àèìòùA-Za-z0-9.,-_!$%()= ]",  $data_xls->sheets[0]['cells'][$i_x][2])>0){$warn_string++;}
        //if (ereg("[^àèìòùA-Za-z0-9.,-_!$%()= ]",  $data_xls->sheets[0]['cells'][$i_x][4])>0){$warn_string++;}
        //if (ereg("[^àèìòùA-Za-z0-9.,-_!$%()= ]",  $data_xls->sheets[0]['cells'][$i_x][6])>0){$warn_string++;}

        
        if (!is_numeric(trim(str_replace(array(","),array("."),$data_xls->sheets[0]['cells'][$i_x][8])))){$erro_num++;} 
        if (!is_numeric(trim(str_replace(array(","),array("."),$data_xls->sheets[0]['cells'][$i_x][7])))){$erro_num++;} 
            
        //Gestito multiplo e minimo attraverso is_multiplo che restituisce false quando uno dei due è zero
        if (!is_multiplo((trim(str_replace(array(","),array("."),$data_xls->sheets[0]['cells'][$i_x][8]))),(trim(str_replace(array(","),array("."),$data_xls->sheets[0]['cells'][$i_x][7]))))){$erro_multi++;}  // Multiplo errato 
        
        if (!is_numeric(trim(str_replace(array(",","€"),array(".",""),$data_xls->sheets[0]['cells'][$i_x][5])))){$erro_num++;} 
        if (!is_numeric(trim(str_replace(array(",","€"),array(".",""),$data_xls->sheets[0]['cells'][$i_x][3])))){$erro_num++;}
        
        $data_xls->sheets[0]['cells'][$i_x][9]=sanitize($data_xls->sheets[0]['cells'][$i_x][9]);
        
        if (trim($data[9])<>""){$unici++;}
        
        if (!$data_xls->sheets[0]['cells'][$i_x][1]==""){
        $result = $db->sql_query("SELECT retegas_articoli.id_articoli
                                FROM retegas_articoli
                                WHERE (((retegas_articoli.codice)='".$data_xls->sheets[0]['cells'][$i_x][1]."') AND ((retegas_articoli.id_listini)=$listino));");    
        
        if (empty($result)){
            $erro_vuoti++; 
        }else{
        
            if (mysql_numrows($result)>0){
            $doppi[]=$data_xls->sheets[0]['cells'][$i_x][1];    
            $erro_doppi++;   
            }
        }//else empty result    
        }
        
        // COntrollo all'interno della stessa lista
        if (in_array($data_xls->sheets[0]['cells'][$i_x][1],$array_articoli)){
            $doppi[]=$data_xls->sheets[0]['cells'][$i_x][1];    
            $erro_doppi++;    
        }else{
            $array_articoli[$row]=$data_xls->sheets[0]['cells'][$i_x][1];
        }
        
        
         
 }

// ------------------------------------------------------------------------------------------------------------------------

       
        $h_table .= "<div class=\"rg_widget rg_widget_helper\">";
        if(($erro_vuoti+$erro_doppi+$error_msg+$erro_num+$erro_troppi)>0){
            $h_table .="<h3>Upload File ($old_name_csv) avvenuta correttamente, <b>MA...</b>";
        }else{
            $h_table .="<h3>Upload File ($old_name_csv) avvenuta correttamente !....  ";
        }
        
        
        //$h_table .="<td colspan=\"9\" align=\"center\">Importazione File ($old_name_csv) avvenuta correttamente</td></tr>";
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
        $h_table .="<div class=\"qta_inf_1 ui-corner-all\">n.$erro_zero Quantita' inferiori a 1 (il minimo è 1))</div>"; 
        }
        if ($erro_multi>0){ 
        $h_table .="<div class=\"multiplo_errato ui-corner-all\">n.$erro_multi articoli con Q. minima NON multipla di Q scatola.</div>"; 
        }
        if ($warn_string>0){ 
        $h_table .="<div class=\"punteggiatura ui-corner-all\">n.$warn_string campi con punteggiatura che verra' omessa. (Es: ' o \")</div>"; 
        }
        if ($unici>0){ 
        $h_table .="<div class=\"punteggiatura ui-corner-all\">n.$unici articoli UNICI</div>"; 
        }
        if ($erro_opzioni>0){ 
        $h_table .="<div class=\"articoli_doppi ui-corner-all\">n.$erro_opzioni articoli con OPZIONI non corrette (tutte e tre le opzioni devono essere presenti)</div>"; 
        }
        if ($erro_troppi>0){ 
        $h_table .="<div class=\"articoli_doppi ui-corner-all\">Questo listino è troppo grosso. Il limite massimo è 100 articoli.</div>"; 
        }
        
        if ($erro_num + $erro_vuoti + $erro_doppi + $erro_zero + $erro_opzioni + $erro_troppi ==0){
            $h_table .="Sembra tutto OK; <a class =\"large awesome green\" href=\"listini_form.php?do=upload&fname=$filone&listino=$listino&tipo_file=XLS\">Carica questi $row Articoli nel listino $listino</a></h3>"; 
        }else{
            $h_table .="<div class=\"ui-state-error ui-corner-all padding_6px\"><b>Correggere il vostro file su EXCEL e rifare l'upload.
            
            </h3></div>
             ";     
        }
        
        
                
        $row = 0;
        
        $h_table .= "
        <table>
        <tr>
        <td>#</td>
        <td width=\"7%\">Cod. Art. Fornitore</td>
        <td><b>Descrizione</b></td>
        <td width=\"7%\">Prezzo</td>
        <td width=\"5%\">U.Mis.</td>
        <td width=\"5%\">Misura</td>
        <td width=\"5%\">Ingombro</td>
        <td width=\"5%\">Q Scat</td>
        <td width=\"5%\">Q Min</td>
        <td width=\"5%\">Note</td>
        <td width=\"5%\">Univoco</td>
        <td>OPZ 1</td>
        <td>OPZ 2</td>
        <td>OPZ 3</td>
         </tr>";

     
         
$erro = 0;
// initialize a loop to go through each line of the file
for ($i_x = 2; $i_x <= $data_xls->sheets[0]['numRows']; $i_x++) {
    
    $row++;
            if(is_integer($row/2)){
            $h_table .= "<tr class=\"odd\">";    // Colore Riga
            }else{
            $h_table .= "<tr>";    
            }
            
            
    $h_table .="<td width=\"2%\">$row</td>";       // numero riga
            
        if ($data_xls->sheets[0]['cells'][$i_x][1]==""){$bg[1] = "class=\"campo_vuoto\"";}else{$bg[1]="";}
        if ($data_xls->sheets[0]['cells'][$i_x][2]==""){$bg[2] = "class=\"campo_vuoto\"";}else{$bg[2]="";}
        if ($data_xls->sheets[0]['cells'][$i_x][3]==""){$bg[3] = "class=\"campo_vuoto\"";}else{$bg[3]="";}
        if ($data_xls->sheets[0]['cells'][$i_x][4]==""){$bg[4] = "class=\"campo_vuoto\"";}else{$bg[4]="";}
        if ($data_xls->sheets[0]['cells'][$i_x][5]==""){$bg[5] = "class=\"campo_vuoto\"";}else{$bg[5]="";}
        if ($data_xls->sheets[0]['cells'][$i_x][6]==""){$bg[6] = "class=\"campo_vuoto\"";}else{$bg[6]="";}
        if ($data_xls->sheets[0]['cells'][$i_x][7]==""){$bg[7] = "class=\"campo_vuoto\"";}else{$bg[7]="";}
        if ($data_xls->sheets[0]['cells'][$i_x][8]==""){$bg[8] = "class=\"campo_vuoto\"";}else{$bg[8]="";} 
        
        
        if ($erro_doppi>0){if (in_array($data_xls->sheets[0]['cells'][$i_x][1],$doppi)){$bg[1] = "class=\"articoli_doppi\"";}}// e' uno dei doppi
        
        //if (ereg("[^àèìòùA-Za-z0-9.,-_!$%()= ]",  $data_xls->sheets[0]['cells'][$i_x][1])>0){$bg[1] = "class=\"punteggiatura\"";}// Cod art. COntiene caratteri insulsi
        //if (ereg("[^àèìòùA-Za-z0-9.,-_!$%()= ]",  $data_xls->sheets[0]['cells'][$i_x][2])>0){$bg[2] = "class=\"punteggiatura\"";}// Descriz COntiene caratteri insulsi  
        //if (ereg("[^àèìòùA-Za-z0-9.,-_!$%()= ]",  $data_xls->sheets[0]['cells'][$i_x][4])>0){$bg[4] = "class=\"punteggiatura\"";}// U mis caratteri insulsi  
        //if (ereg("[^àèìòùA-Za-z0-9.,-_!$%()= ]",  $data_xls->sheets[0]['cells'][$i_x][6])>0){$bg[6] = "class=\"punteggiatura\"";}// Ingombro caratteri insulsi  
        
        if (!is_numeric(trim(str_replace(array(","),array("."),$data_xls->sheets[0]['cells'][$i_x][8])))){$bg[8] = "class=\"non_riconosciuto\"";}else{$bg[8] = "";} 
        if (!is_numeric(trim(str_replace(array(","),array("."),$data_xls->sheets[0]['cells'][$i_x][7])))){$bg[7] = "class=\"non_riconosciuto\"";}else{$bg[7] = "";}
        
        
        if (trim($data_xls->sheets[0]['cells'][$i_x][10])=="UNICO"){$bg[10] = "class=\"punteggiatura\"";}else{$bg[10] = "";}
        
        // controllo inferiore a 1 !!!
        
        if (!is_multiplo((trim(str_replace(array(","),array("."),$data_xls->sheets[0]['cells'][$i_x][8]))),(trim(str_replace(array(","),array("."),$data_xls->sheets[0]['cells'][$i_x][7]))))){$bg[8] = "class=\"multiplo_errato\"";} // Non è un multiplo 
        
        if (!is_numeric(trim(str_replace(array(",","€","'"),array(".","",""),$data_xls->sheets[0]['cells'][$i_x][5])))){$bg[5] = "class=\"non_riconosciuto\"";}else{$bg[5] = "";}  
        if (!is_numeric(trim(str_replace(array(",","€","'"),array(".","",""),$data_xls->sheets[0]['cells'][$i_x][3])))){$bg[3] = "class=\"non_riconosciuto\"";}else{$bg[3] = "";}    
        
        if (($data_xls->sheets[0]['cells'][$i_x][11]<>"")
            AND (   ($data_xls->sheets[0]['cells'][$i_x][12]=="" | empty($data_xls->sheets[0]['cells'][$i_x][12])) 
                    OR ($data_xls->sheets[0]['cells'][$i_x][13]=="" | empty($data_xls->sheets[0]['cells'][$i_x][13]))
                    )
            ){$bg[11] = "class=\"articoli_doppi\"";}else{$bg[11] = "";}
        if ($data_xls->sheets[0]['cells'][$i_x][11]<>""){$bg[2] = "class=\"qta_inf_1\"";}else{$bg[2] = "";};
        
        
            $h_table .="<td width=\"7%\"><div $bg[1]>".strip_tags($data_xls->sheets[0]['cells'][$i_x][1])."</div></td>";// Codice Articolo
            $h_table .="<td width=\"15%\"><div $bg[2]>".strip_tags($data_xls->sheets[0]['cells'][$i_x][2])."<div></td>";    
            $h_table .="<td><div $bg[3]>".$data_xls->sheets[0]['cells'][$i_x][3]."<div></td>
                 <td width=\"2%\"><div $bg[4]>".$data_xls->sheets[0]['cells'][$i_x][4]."<div></td>
                 <td width=\"5%\"><div $bg[5]>".$data_xls->sheets[0]['cells'][$i_x][5]."<div></td>
                 <td width=\"5%\">".$data_xls->sheets[0]['cells'][$i_x][6]."</td>
                 <td width=\"5%\"><div $bg[7]>".$data_xls->sheets[0]['cells'][$i_x][7]."<div></td>
                 <td width=\"5%\"><div $bg[8]>".$data_xls->sheets[0]['cells'][$i_x][8]."<div></td>
                 <td width=\"5%\"><div $bg[9]>".$data_xls->sheets[0]['cells'][$i_x][9]."<div></td>
                 <td width=\"5%\"><div $bg[10]>".$data_xls->sheets[0]['cells'][$i_x][10]."<div></td>
                 <td><div $bg[11]>".$data_xls->sheets[0]['cells'][$i_x][11]."<div></td>
                 <td><div $bg[12]>".$data_xls->sheets[0]['cells'][$i_x][12]."<div></td>
                 <td><div $bg[13]>".$data_xls->sheets[0]['cells'][$i_x][13]."<div></td>";
            $h_table .="</tr>";

}
$h_table .="</table>
            </div>";
//include("../footer.php");

if ($erro_num + $erro_vuoti + $erro_doppi+ $erro_zero + $erro_opzioni > 0){          // se c'era qualche errore cancello il file'
 if(is_file("$filone")) {
unlink("$filone");
}   
}


//-------------------------------------------------------------------IMPORT

//Questo è¨ il contenuto della pagina
$r->contenuto = listini_form($id).$h_table;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);