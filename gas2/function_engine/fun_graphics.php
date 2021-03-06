<?php

function crea_grafico_google_1($id){
    
$query = "SELECT
retegas_messaggi.id_ordine,
retegas_messaggi.timbro,
retegas_messaggi.valore,
retegas_messaggi.tipo2
FROM
retegas_messaggi
WHERE
retegas_messaggi.id_ordine =  '$id' AND
retegas_messaggi.tipo2 =  'ART'
ORDER BY
retegas_messaggi.timbro ASC";    
$ret = mysql_query($query);
$nrow = mysql_numrows($ret);
if($nrow==0){
    return "";
    exit;
}
$max=0;
while ($row = mysql_fetch_array($ret)){
//echo intval($row[2])."<br>"; 
$valori[] = intval($row[2]);
if(intval($row[2])>$max){
        $max = intval($row[2]);
    }
}

$max_3_4 = intval(($max/4)*3);
$max_1_2 = intval(($max/2));
$max_1_4 = intval(($max/4));

//echo $valori[1];

// daily visitors 
//$data = array(1,21,3,150); 
$data=$valori;
$GphpChart = new GphpChart('lc'); // 'lc' stands for a line chart 
//$GphpChart->title = 'Valore ordine'; // this title will be on the chart image 
$GphpChart->add_data(array_values($data)); // adding values 
//$GphpChart->add_labels('x',array_keys($data)); // adding x labels (bottom axis) 
$GphpChart->add_labels('y',array(0,200,400,600,800,1000,1200,1400)); // adding y labels (left axis) 
return $GphpChart->get_Image_String();



    
}
function crea_grafico_highcharts_1($id){
    
$query = "SELECT
retegas_messaggi.id_ordine,
retegas_messaggi.timbro,
retegas_messaggi.valore,
retegas_messaggi.tipo2
FROM
retegas_messaggi
WHERE
retegas_messaggi.id_ordine =  '$id' AND
retegas_messaggi.tipo2 =  'ART'
ORDER BY
retegas_messaggi.timbro ASC";    
$ret = mysql_query($query);
$nrow = mysql_numrows($ret);
if($nrow==0){
    return "";
    exit;
}
$max=0;
while ($row = mysql_fetch_array($ret)){
//echo $valori;
$valori .= round($row[2],2).", ";

}
$valori = rtrim($valori,",");

return $valori;



    
}

function crea_grafico_sparkline($id,$min_data,$max_giorni,$max_valore){
    
//$query = "SELECT
//retegas_messaggi.id_ordine,
//retegas_messaggi.timbro,
//retegas_messaggi.valore,
//retegas_messaggi.tipo2
//FROM
//retegas_messaggi
//WHERE
//retegas_messaggi.id_ordine =  '$id' AND
//retegas_messaggi.tipo2 =  'ART'
//ORDER BY
//retegas_messaggi.timbro ASC";    
$query="SELECT
retegas_messaggi.id_ordine,
retegas_messaggi.timbro,
Avg(retegas_messaggi.valore) as media_ordine,
retegas_messaggi.tipo2
FROM
retegas_messaggi
WHERE
retegas_messaggi.id_ordine =  '$id' AND
retegas_messaggi.tipo2 =  'ART'
GROUP BY
retegas_messaggi.id_ordine,
DayOfYear(retegas_messaggi.timbro),
retegas_messaggi.tipo2
ORDER BY
retegas_messaggi.timbro ASC";

$ret = mysql_query($query);
$nrow = mysql_numrows($ret);
if($nrow==0){
    return "";
    exit;
}
$valori="0,";

for($i=0;$i<=$max_giorni;$i++){
   
   $array_date[$i]=date("d/m/Y", gas_mktime($min_data)+($i * 24 * 60 * 60));
   $array_valori[$i]="null";
   
   //echo "Indice : $i Giorno: ".$array_date[$i].", valore = ".$array_valori[$i]."<br>";  
}  

while ($row = mysql_fetch_array($ret)){
    
    $data_search =  conv_only_date_from_db($row["timbro"]);
    
    for($i=0;$i<=$max_giorni;$i++){
    //echo "data search $data_search ; Arraydate[$i] = $array_date[$i]<br>";     
        if($array_date[$i] == $data_search){
            //echo "trovato :  $array_date[$i] = $data_search, valore :  $array_valori[$i]=$row[2];";
            $array_valori[$i]=$row[2];
            break;
        }
        
    }
    
    
    //echo "Indice ->".$i."  Val = $row[2]  data search = $data_search <br>";  

}

for($i=0;$i<=$max_giorni;$i++){
   
   $valori .=intval($array_valori[$i]).",";   
   //echo "$i -> ".$valori."<br>";
   //echo "ARRAY FINALE Indice : $i Giorno: ".$array_valori[$i]["giorno"]."<br>";  
}



$valori = rtrim($valori,",");

//echo $valori."<br>";

return $valori;
}
function crea_grafico_highcharts_2($id,$min_data,$max_giorni,$max_valore){
    
//$query = "SELECT
//retegas_messaggi.id_ordine,
//retegas_messaggi.timbro,
//retegas_messaggi.valore,
//retegas_messaggi.tipo2
//FROM
//retegas_messaggi
//WHERE
//retegas_messaggi.id_ordine =  '$id' AND
//retegas_messaggi.tipo2 =  'ART'
//ORDER BY
//retegas_messaggi.timbro ASC";    
$query="SELECT
retegas_messaggi.id_ordine,
retegas_messaggi.timbro,
max(retegas_messaggi.valore) as media_ordine,
retegas_messaggi.tipo2
FROM
retegas_messaggi
WHERE
retegas_messaggi.id_ordine =  '$id' AND
retegas_messaggi.tipo2 =  'ART'
GROUP BY
retegas_messaggi.id_ordine,
DayOfYear(retegas_messaggi.timbro),
retegas_messaggi.tipo2
ORDER BY
retegas_messaggi.timbro ASC";

$ret = mysql_query($query);
$nrow = mysql_numrows($ret);
if($nrow==0){
    return "";
    exit;
}
$valori="0,";
$last_value= 0;
$position=0;
for($i=0;$i<=$max_giorni;$i++){
   
   $array_date[$i]=date("d/m/Y", gas_mktime($min_data)+($i * 24 * 60 * 60));
   $array_valori[$i]="null";
   
   //echo "Indice : $i Giorno: ".$array_date[$i].", valore = ".$array_valori[$i]."<br>";  
}  

while ($row = mysql_fetch_array($ret)){
    
    $data_search =  conv_only_date_from_db($row["timbro"]);
    
    
    
    for($i=0;$i<=$max_giorni;$i++){
    //echo "data search $data_search ; Arraydate[$i] = $array_date[$i]<br>";     
        if($array_date[$i] == $data_search){
            //echo "trovato :  $array_date[$i] = $data_search, valore :  $array_valori[$i]=$row[2];";
            $array_valori[$i]=$row[2];
            $position = $i;
            $last_value =  $row[2];
            break;
        }          
}
    
    
    //echo "Indice ->".$i."  Val = $row[2]  data search = $data_search <br>";  

}



for($i=0;$i<=$max_giorni;$i++){
   
   if($i>1){ 
    if($array_valori[$i]==0) $array_valori[$i] = $array_valori[$i-1]; 
   }
   $valori .=intval($array_valori[$i]).",";   
   //echo "$i -> ".$valori."<br>";
   //echo "ARRAY FINALE Indice : $i Giorno: ".$array_valori[$i]["giorno"]."<br>";  
}



$valori = rtrim($valori,",");

//echo $valori."<br>";

return $valori;
}
function crea_grafico_highcharts_3($id,$min_data,$max_giorni,$max_valore){
 global $db;   
 $sql2 = "SELECT
                Sum(retegas_dettaglio_ordini.qta_arr * prezzo) AS totale_giorno,
                data_inserimento as data
                FROM
                retegas_dettaglio_ordini
                Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
                Inner Join maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
                WHERE
                id_ordine='$id'
                GROUP BY DATE_FORMAT(data_inserimento, '%Y%m%d%h')
                ORDER BY data_inserimento ASC";
      $res2 = $db->sql_query($sql2);
      //{ name: 'Firefox', y: 44.2, color: '#4572A7' }, 
      while ($row = mysql_fetch_array($res2)){
            $somma2 = $somma2 + CAST_TO_FLOAT($row["totale_giorno"]); 
            $valori .="{ x: Date.UTC(".conv_datetime_to_javascript($row["data"])."), y: ".round($somma2,0)."}, 
                                  ";
      }
$valori = rtrim($valori,",");

//echo $valori."<br>";

return $valori;
}


function crea_json_soldi_retegas(){
global $db;

$query = "SELECT * FROM maaking_users WHERE userid='2';";
$result = $db->sql_query($query);
$num = $db->sql_numrows($query);
$objJSON=new mysql2json();
$content = (trim($objJSON->getJSON($result,$num))); 

$filename = '../grafici/data.txt';
unlink($filename);
$fp = fopen($filename, 'w');
fwrite($fp, $content);
fclose($fp);

   

};

   