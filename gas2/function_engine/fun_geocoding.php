<?php
function utenti_con_geocode_ok($gas = null){
global $db;

if(!empty($gas)){
	  $qry = "SELECT * FROM maaking_users WHERE (city <>'') AND (user_gc_lat > 0) AND id_gas='$gas';";
	  
}else{
	$qry = "SELECT * FROM maaking_users WHERE (city <>'') AND (user_gc_lat > 0);";
	  
} 
$res = $db->sql_query($qry);    
  return $db->sql_numrows($res);
  

	
}
function ditte_con_geocode_ok(){
global $db;
                                                                       
      $qry = "SELECT * FROM retegas_ditte WHERE  (ditte_gc_lat > 0);";

$res = $db->sql_query($qry);    
  return $db->sql_numrows($res);
  

    
}
function gas_con_geocode_ok(){
global $db;
                                                                       
      $qry = "SELECT * FROM retegas_gas WHERE  (gas_gc_lat > 0);";

$res = $db->sql_query($qry);    
  return $db->sql_numrows($res);
  

    
}
function geocode_users_table($query){

$log .= "QUERY : ".$query."<br>";

global $db;





$result = $db->sql_query($query);
if (!$result) {
  die("Parametro query geolocalizzazione non valido " . mysql_error());
}

// Initialize delay in geocode speed
$delay = 0;
$base_url = "http://maps.googleapis.com/maps/api/geocode/xml?sensor=false";

// Iterate through the rows, geocoding each address
while ($row = @mysql_fetch_assoc($result)) {
  $geocode_pending = true;

  while ($geocode_pending) {
	$address = $row["country"].", ".$row["city"];
	$log .= "ADDRESS : ".$address."<br>";
	$id = $row["userid"];
	$request_url = $base_url . "&address=" . urlencode($address);
	$log .= "REQUEST URL : ".$request_url."<br>";
	$xml = simplexml_load_file($request_url) or die("url not loading");
	//$log .= "XML : ".print_r($xml)."<br>"; 
	$status = $xml->status;
	$log .= "STATUS: ".$status."<br>"; 
	
    if (strcmp($status, "OK") == 0) {
	  // Successful geocode
      
	  $geocode_pending = false;
	  $lat = $xml->result->geometry->location->lat;
	  $lng = $xml->result->geometry->location->lng;
	  $log .= "LAT LON : $lat $lng  <br>"; 
	  
	  $query = sprintf("UPDATE maaking_users " .
			 " SET user_gc_lat = '%s', user_gc_lng = '%s' " .
			 " WHERE userid = '%s' LIMIT 1;",
			 mysql_real_escape_string($lat),
			 mysql_real_escape_string($lng),
			 mysql_real_escape_string($id));
	  $update_result = $db->sql_query($query);
	  $log .= "QUERY UPDATE : ".$query."<br>"; 
	  if (!$update_result) {
		die("Invalid query: " . mysql_error());
	  }
	} else if ($status<>"OK") {
	  // sent geocodes too fast
      $failed++;
      if($failed>10){echo "TOO MANY FAILS";die;}
	  $delay += 100000;
	} else {
		
	//altrimenti lo metto a zero    
	$query = "UPDATE maaking_users " .
			 " SET user_gc_lat = '0', user_gc_lng = '0' " .
			 " WHERE userid = '$id' LIMIT 1;";

	  $update_result = $db->sql_query($query);
	  // failure to geocode
	  $geocode_pending = false;
	  $log .= "Address " . $address . " failed to geocoded. ";
	  $log .= "Received status " . $status . "//\n";
	}
	usleep($delay);
  }
}
return $log;
  
}
function geocode_gas_table($query){

//echo "QUERY : ".$query."<br>";

global $db;




$result = $db->sql_query($query);
if (!$result) {
  die("Parametro query geolocalizzazione non valido " . mysql_error());
}

// Initialize delay in geocode speed
$delay = 0;
$base_url = "http://maps.googleapis.com/maps/api/geocode/xml?sensor=false";

// Iterate through the rows, geocoding each address
while ($row = @mysql_fetch_assoc($result)) {
  $geocode_pending = true;

  while ($geocode_pending) {
    $address = $row["sede_gas"];
    
    $id = $row["id_gas"];
    $request_url = $base_url . "&address=" . urlencode($address);
    
    $xml = simplexml_load_file($request_url) or die("url not loading");
     
    $status = $xml->status;
    
    if ($status=="OK") {
      // Successful geocode
      $geocode_pending = false;
      $lat = $xml->result->geometry->location->lat;
      $lng = $xml->result->geometry->location->lng;

      $query = sprintf("UPDATE retegas_gas " .
             " SET gas_gc_lat = '%s', gas_gc_lng = '%s' " .
             " WHERE id_gas = '%s' LIMIT 1;",
             mysql_real_escape_string($lat),
             mysql_real_escape_string($lng),
             mysql_real_escape_string($id));
      $update_result = $db->sql_query($query);
      $log =  "QUERY UPDATE : ".$query."<br>"; 
      if (!$update_result) {
        die("Invalid query: " . mysql_error());
      }
    } else if ($status<>"OK") {
      // sent geocodes too fast
      $log_fail .= "$address -> $status <br>";
      $failed++;
      if($failed>10){echo "TOO MANY FAILS<br>$log_fail";die;}
      $delay += 100000;
    } else {
        
    //altrimenti lo metto a zero    
    $query = "UPDATE retegas_gas " .
             " SET gas_gc_lat = '0', gas_gc_lng = '0' " .
             " WHERE id_gas = '$id' LIMIT 1;";

      $update_result = $db->sql_query($query);
      $geocode_pending = false;

    }
    usleep($delay);
  }
}
return $query;
  
}
function geocode_ditte_table($query){

global $db;

$h = "Input query : ". $query."<br>";
$result = $db->sql_query($query);

//$h .= "Result query : ". print_r($result)."<br>";

if (!$result) {
  $h .= "Errore DB : " . mysql_error()."<br>";
  return $h;
  exit;
}

// Initialize delay in geocode speed
$delay = 0;
$base_url = "http://maps.googleapis.com/maps/api/geocode/xml?sensor=false";

$h .="Base url: ".$base_url."</br>";

// Iterate through the rows, geocoding each address
while ($row = @mysql_fetch_assoc($result)) {
  $geocode_pending = true;

  while ($geocode_pending) {
    $address = $row["indirizzo"];
    $h.= "ADDRESS : ".$address."<br>";
    $id = $row["id_ditte"];
    $request_url = $base_url . "&address=" . urlencode($address);
    $h.= "REQUEST URL : ".$request_url."<br>"; 
    $xml = simplexml_load_file($request_url) or $h.= "URL not loading";
    $status = $xml->status;
    $h.= "STATUS: ".$status."<br>"; 
    if ($status=="OK") {
      // Successful geocode
      $geocode_pending = false;
      $lat = $xml->result->geometry->location->lat;
      $lng = $xml->result->geometry->location->lng;
      $h.= "LAT LON :$lat $lng <br>"; 
      
      
      $query = sprintf("UPDATE retegas_ditte " .
             " SET ditte_gc_lat = '%s', ditte_gc_lng = '%s' " .
             " WHERE id_ditte = '%s' LIMIT 1;",
             mysql_real_escape_string($lat),
             mysql_real_escape_string($lng),
             mysql_real_escape_string($id));
      $update_result = $db->sql_query($query);
      $h .= "QUERY UPDATE : ".$query."<br>"; 
      if (!$update_result) {
        die("Invalid query: " . mysql_error());
      }
    } else if ($status<>"OK") {
      // sent geocodes too fast
      $log_fail .= "FAIL :$address -> $status <br>";
      $failed++;
      if($failed>10){echo "TOO MANY FAILS<br>$log_fail"; die();}
      $delay += 100000;
    } else {
        
    //altrimenti lo metto a zero    
    $query = "UPDATE retegas_ditte " .
             " SET ditte_gc_lat = '0', ditte_gc_lng = '0' " .
             " WHERE id_ditte = '$id' LIMIT 1;";

      $update_result = $db->sql_query($query);
      // failure to geocode
      $geocode_pending = false;
      $h .= "Address " . $address . " failed to geocoded. ";
      $h .= "Received status " . $status . "//\n";
    }
    usleep($delay);
  }
}
return $h;
  
}

function build_address_list_gas($gas){

      global $db;

      $qry = "SELECT * FROM maaking_users WHERE (city<>'') AND (user_gc_lat > 0) AND (id_gas='$gas');";

      $res = $db->sql_query($qry);

      while ($row = $db->sql_fetchrow($res)){

          

      //["Maroubra Beach", -33.950198, 151.259302, 1]     

      $out .='["Utente", '.$row["user_gc_lat"].', '.$row["user_gc_lng"].',1], '; 

      }

      $out = rtrim($out,", ");

      

  return $out;

  

  }
function build_address_list_gas_2($gas){

      global $db;
      $qry = "SELECT * FROM retegas_gas WHERE (sede_gas<>'') AND (gas_gc_lat > 0) AND id_des = "._USER_ID_DES.";";
      $qry = "SELECT * FROM retegas_gas WHERE (sede_gas<>'') AND (gas_gc_lat > 0);";

      $res = $db->sql_query($qry);

      while ($row = $db->sql_fetchrow($res)){

          

      //["Maroubra Beach", -33.950198, 151.259302, 1]     

      $out .='["'.$row["descrizione_gas"].'", '.$row["gas_gc_lat"].', '.$row["gas_gc_lng"].',1], '; 

      }

      $out = rtrim($out,", ");

      

  return $out;

  

  } 

function build_address_list_ditte(){

      global $db;
      $qry = "SELECT * FROM retegas_ditte WHERE (indirizzo<>'') AND (ditte_gc_lat > 0);";
      $res = $db->sql_query($qry);
      while ($row = $db->sql_fetchrow($res)){$out .='["'.addslashes($row["descrizione_ditte"]).'", '.$row["ditte_gc_lat"].', '.$row["ditte_gc_lng"].',1 ,'.$row["id_ditte"].'], ';}
      $out = rtrim($out,", ");

  return $out;

  }   

function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2) {
    $theta = $longitude1 - $longitude2;
    $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    $feet = $miles * 5280;
    $yards = $feet / 3;
    $kilometers = $miles * 1.609344;
    $meters = $kilometers * 1000;
    return $kilometers; 
}


function geo_des_center($id_des){
    return db_val_q("id_des",$id_des,"des_lat","retegas_des").','.db_val_q("id_des",$id_des,"des_lng","retegas_des");
}

function geo_ditta_center($id_ditta){
    return db_val_q("id_ditte",$id_ditta,"ditte_gc_lat","retegas_ditte").','.db_val_q("id_ditte",$id_ditta,"ditte_gc_lng","retegas_ditte");
}

function gmap3_addmarker($lat,$lng,$info){
    return '{ action: \'addMarker\',
                                  latLng:['.$lat.', '.$lng.'],
                                  options:{ icon: {
                                                    path: google.maps.SymbolPath.CIRCLE,
                                                    scale: 2
                                                  },                                        
                                            animation: google.maps.Animation.DROP
                                  },
                                  events:   {
                                                mouseover: function(marker, event, data){
                                                    var map = $(this).gmap3(\'get\'),
                                                    infowindow = $(this).gmap3({action:\'get\', name:\'infowindow\'});
                                                    if (infowindow){
                                                            infowindow.open(map, marker);
                                                            infowindow.setContent(\''.$info.'\');
                                                        } else {
                                                            $(this).gmap3({action:\'addinfowindow\', anchor:marker, options:{content: \''.$info.'\'}});
                                                        }
                                                    },
                                                    mouseout: function(){
                                                        var infowindow = $(this).gmap3({action:\'get\', name:\'infowindow\'});
                                                        if (infowindow){
                                                            infowindow.close();
                                                        }       
                                                }
                                            }
                                }';
}

function gmap3_addcircle($lat,$lng,$radius,$color_hex){
    return '{ action: \'addCircle\',
            center: ['.$lat.', '.$lng.'],
            radius : '.$radius.',
            fillColor : "rgba('.hex2rgb($color_hex).', .6)",
            strokeColor : "rgba('.hex2rgb($color_hex).', .9)"
            }';
}

function gmap3_init($center,$zoom){
   return '{action: \'init\',
                                options:{
                                    center:['.$center.'],
                                    zoom:'.$zoom.',
                                    maxZoom: 10,
                                    mapTypeId: google.maps.MapTypeId.TERRAIN,
                                    mapTypeControl: false,
                                    mapTypeControlOptions: {
                                            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                                       },
                                navigationControl: false,
                                scrollwheel: true,
                                streetViewControl: false
                                }
                                    
                            }'; 
}

function create_json($tipo,$query){
    
    global $db;
    global $RG_addr;
    //MOLTO PERICOLOSO
    $res=$db->sql_query($query);
    while ($row = $db->sql_fetchrow($res)){
        $out .='["'.addslashes($row["descrizione_ditte"]).'", '.$row["ditte_gc_lat"].', '.$row["ditte_gc_lng"].',1 ,'.$row["id_ditte"].'], ';
        $n_rows++;
        
        $n_comm = bacheca_n_messaggi_ditta($row["id_ditte"],ruoli::partecipante);
        $n_cert = bacheca_n_messaggi_ditta($row["id_ditte"],ruoli::certificante);
        $n_rela = bacheca_n_messaggi_ditta($row["id_ditte"],ruoli::relazionante);
        $n_valu = bacheca_n_messaggi_ditta($row["id_ditte"],ruoli::referente);
        
        $test_html = "<strong><a>{$row["descrizione_ditte"]}</a></strong><p>{$row['indirizzo']}</p><p>Commenti $n_comm, Valutazioni $n_valu, Relazioni $n_rela, Certificazioni $n_cert.</p><span>Media <span class=\"small_link\">(su ".conteggio_opinione_ditta($row["id_ditte"])." opinioni)</span></span><div class=\"rateit\" data-rateit-value=\"".media_opinione_ditta($row["id_ditte"])."\" data-rateit-ispreset=\"true\" data-rateit-readonly=\"true\"></div>";
        
        $json_internal[] = array(
        'objId' => "rd_".$row['id_ditte'],
        'objTitle' => $row['descrizione_ditte'],
        'objSubtitle' => '',
        'objLatitude' => $row['ditte_gc_lat'],
        'objLongitude' => $row['ditte_gc_lng'],
        'objAddress' => $row['indirizzo'],
        'objUrl' => $RG_addr["form_ditta"]."?id_ditta=".$row['id_ditte'],
        'objHtml' => $test_html
        );
    }
    
    $json= ARRAY("dataOwner" => "rd",
                 "dataType" => "_MAP",
                 "dataObj" => $tipo,
                 "dataNRecs" =>$n_rows,
                 $tipo => $json_internal );
                   
    
    

    return json_encode($json);
}
