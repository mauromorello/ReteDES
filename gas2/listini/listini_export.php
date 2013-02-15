<?php

include_once ("../rend.php"); 

(int)$id;
(int)$type;

function xlsBOF() {
echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
return;
}
function xlsEOF() {
echo pack("ss", 0x0A, 0x00);
return;
}
function xlsWriteNumber($Row, $Col, $Value) {
echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
echo pack("d", $Value);
return;
}
function xlsWriteLabel($Row, $Col, $Value ) {
$L = strlen($Value);
echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
echo $Value;
return;
}
function create_raw_table($id){

    global $db;
    
$query = "SELECT * FROM retegas_articoli WHERE id_listini='$id';";
  $res = $db->sql_query($query);
  
  $t  = '<table>
        ';
  $t .= '<tr>
            <td>Cod.Art.Fornitore</td>
            <td>Descrizione</td>
            <td>Prezzo</td>
            <td>U.Misura</td>
            <td>Misura</td>
            <td>Note brevi</td>
            <td>Qta Scatola</td>
            <td>Minimo Multiplo</td>
            <td>Note Lunghe</td>
            <td>UNIVOCO</td>
            <td>OPZ 1</td>
            <td>OPZ 2</td>
            <td>OPZ 3</td>
         </tr>';
  
  
  while ($row = $db->sql_fetchrow($res)){
     $t .= '<tr>
            <td>'.$row["codice"].'</td>
            <td>'.$row["descrizione_articoli"].'</td>
            <td>'._nf($row["prezzo"]).'</td>
            <td>'.$row["u_misura"].'</td>
            <td>'._nf($row["misura"]).'</td>
            <td>'.$row["ingombro"].'</td>
            <td>'._nf($row["qta_scatola"]).'</td>
            <td>'._nf($row["qta_minima"]).'</td>
            <td>'.$row["articoli_note"].'</td>
            <td>'.$row["articoli_unico"].'</td>
            <td>'.$row["articoli_opz_1"].'</td>
            <td>'.$row["articoli_opz_2"].'</td>
            <td>'.$row["articoli_opz_3"].'</td>
         </tr>';
  
  }
  
  $t .= '</table>';
  return $t;
}  

if($type==2){
  echo create_raw_table($id);  
    
}
if($type==1){
    $filename = "Listino_n_$id"."_".date("d_m_Y").".xls";
    // Send Header
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header("Content-Disposition: attachment;filename=$filename");
    header("Content-Transfer-Encoding: binary");
    xlsBOF();
    xlsWriteLabel(0,0,"Cod.Art.Fornitore");
    xlsWriteLabel(0,1,"Descrizione");
    xlsWriteLabel(0,2,"Prezzo");
    xlsWriteLabel(0,3,"U Misura");
    xlsWriteLabel(0,4,"Misura");
    xlsWriteLabel(0,5,"Ingombro");
    xlsWriteLabel(0,6,"Q Scatola");
    xlsWriteLabel(0,7,"Multiplo minimo");
    xlsWriteLabel(0,8,"Note Lunghe (max 255)");
    xlsWriteLabel(0,9,"Univoco");
    xlsWriteLabel(0,10,"OPZ1");
    xlsWriteLabel(0,11,"OPZ2");
    xlsWriteLabel(0,12,"OPZ3");
    $xlsRow = 1;
    
    $query = "SELECT * FROM retegas_articoli WHERE id_listini='$id';";
    $res = $db->sql_query($query);
    while($row = $db->sql_fetchrow($res)) {
        
        xlsWriteLabel($xlsRow,0,$row["codice"]);
        xlsWriteLabel($xlsRow,1,$row["descrizione_articoli"]);
        xlsWriteNumber($xlsRow,2,$row["prezzo"]);
        xlsWriteLabel($xlsRow,3,$row["u_misura"]);
        xlsWriteNumber($xlsRow,4,$row["misura"]);
        xlsWriteLabel($xlsRow,5,$row["ingombro"]);
        xlsWriteNumber($xlsRow,6,$row["qta_scatola"]);
        xlsWriteNumber($xlsRow,7,$row["qta_minima"]);
        xlsWriteLabel($xlsRow,8,$row["articoli_note"]);
        xlsWriteLabel($xlsRow,9,$row["articoli_unico"]);
        xlsWriteLabel($xlsRow,10,$row["articoli_opz_1"]);
        xlsWriteLabel($xlsRow,11,$row["articoli_opz_2"]);
        xlsWriteLabel($xlsRow,12,$row["articoli_opz_3"]);
        $xlsRow++;
    }
    xlsEOF();
    exit();  
    
}

if($type==3){
 
      
 $values = $db->sql_query("SELECT * FROM retegas_articoli WHERE id_listini='$id'");

 
 //INTESTAZIONE
 $csv_output .= _USER_CSV_DELIMITER."Codice"._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;    
    $csv_output .= _USER_CSV_DELIMITER."Descrizione"._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER."Prezzo"._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER."Unità misura"._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER."Misura"._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER."Note Brevi"._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER."Qtà scatola"._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER."Qtà frazione"._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER."Note"._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER."Univocità"._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER."OPZ 1"._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER."OPZ 2"._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER."OPZ 3"._USER_CSV_DELIMITER;
    $csv_output .= _USER_CSV_EOL;


//RIGHE 
while ($rowr = $db->sql_fetchrow($values)) {

    $note =  preg_replace('#[\r\n]#', '', $rowr["articoli_note"]);
    if($rowr["articoli_unico"]==0){$unico="";}else{$unico=$rowr["articoli_unico"];}  
    
    $csv_output .= _USER_CSV_DELIMITER.$rowr["codice"]._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;    
    $csv_output .= _USER_CSV_DELIMITER.$rowr["descrizione_articoli"]._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER._nf($rowr["prezzo"])._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER.$rowr["u_misura"]._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER._nf($rowr["misura"])._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER.$rowr["ingombro"]._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER._nf($rowr["qta_scatola"])._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER._nf($rowr["qta_minima"])._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER.htmlentities($note)._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER.$unico._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER.$rowr["articoli_opz_1"]._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER.$rowr["articoli_opz_2"]._USER_CSV_DELIMITER._USER_CSV_SEPARATOR;
    $csv_output .= _USER_CSV_DELIMITER.$rowr["articoli_opz_3"]._USER_CSV_DELIMITER;
    $csv_output .= _USER_CSV_EOL;
}
 
$filename = "Listino-$id-".date("d-m-Y_H-i",time());
 
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv".date("Y-m-d").".csv");
header("Content-disposition: filename=".$filename.".csv");
 
print trim($csv_output);    
    
}