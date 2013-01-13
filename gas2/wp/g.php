<?php


 include ("../rend.php");

 //echo $rd_code."<br>";
 //echo $rd_tipo."<br>";
 
  
 if(!isset($rd_code)){echo "MISSING CODE";die();}
 if(!isset($rd_tipo)){echo "MISSING TYPE";die();}

 $id_gas = wp_id_gas_from_code($rd_code);
 //echo $id_gas."<br>";
 
 if($id_gas==0 | $id_gas==""){
    echo "Codice GAS non riconosciuto.";die();    
 }
 
 $id_des = des_id_des_from_id_gas($id_gas);
 

$h = '<script type="text/javascript"
        src="http://maps.googleapis.com/maps/api/js?key='.GOOGLE_KEY.'&sensor=false">
      </script>';

if($wt==1){                            
//LISTA GAS CON UTENTI ATTIVI
$sql2 = "SELECT
            Count(maaking_users.userid) AS user_totali,
            retegas_gas.descrizione_gas,
            retegas_gas.id_gas,
            retegas_gas.gas_gc_lat,
            retegas_gas.gas_gc_lng
            FROM
            retegas_gas
            Inner Join maaking_users ON maaking_users.id_gas = retegas_gas.id_gas
            WHERE isactive='1'
            AND
            id_des ='$id_des'
            GROUP BY
            retegas_gas.id_gas,
            retegas_gas.descrizione_gas";
      $res2 = $db->sql_query($sql2);
      
      
      while ($row = mysql_fetch_array($res2)){                            
      
          $jd .= "
                gas_map['".$row["descrizione_gas"]."'] = {
                center: new google.maps.LatLng(".$row["gas_gc_lat"].", ".$row["gas_gc_lng"]."),
                users: ".$row["user_totali"]."
                };
                ";
                
         $mr .= '
                var marker = new google.maps.Marker({
                position: new google.maps.LatLng('.$row["gas_gc_lat"].', '.$row["gas_gc_lng"].'),
                map: map,
                title:"'.$row["descrizione_gas"].', '.$row["user_totali"].' user attivi"
                });
                ';       
      }                      

//LISTA GAS CON UTENTI DISATTIVI
$sql2 = "SELECT
            Count(maaking_users.userid) AS user_totali,
            retegas_gas.descrizione_gas,
            retegas_gas.id_gas,
            retegas_gas.gas_gc_lat,
            retegas_gas.gas_gc_lng
            FROM
            retegas_gas
            Inner Join maaking_users ON maaking_users.id_gas = retegas_gas.id_gas
            WHERE isactive<>'1'
            AND
            id_des ='$id_des'
            GROUP BY
            retegas_gas.id_gas,
            retegas_gas.descrizione_gas";
      $res2 = $db->sql_query($sql2);
      
      
      while ($row = mysql_fetch_array($res2)){                            
      
          $jd_na .= "
                gas_map_na['".$row["descrizione_gas"]."'] = {
                center: new google.maps.LatLng(".$row["gas_gc_lat"].", ".$row["gas_gc_lng"]."),
                users: ".$row["user_totali"]."
                };
                ";
     
      }


                            
$h .='<script type="text/javascript">
                      
                      var gas_map = {};
                      var gas_map_na ={};
                      
                      '.$jd.'
                      '.$jd_na.'
                      
                      
                      
                      function initialize_gas() {
                      var mapOptions = {
                          center: new google.maps.LatLng('.db_val_q("id_des",$id_des,"des_lat","retegas_des").',
                                                         '.db_val_q("id_des",$id_des,"des_lng","retegas_des").'),
                          zoom: 8,
                          mapTypeId: google.maps.MapTypeId.TERRAIN
                        };
                        var map = new google.maps.Map(document.getElementById("map_canvas_gas"),
                            mapOptions);
                      
                      var gasCircle;
                      
                      for (var gas in gas_map) {
                                // Construct the circle for each value in citymap. We scale population by 20.
                                var populationOptions = {
                                  strokeColor: "#0000FF",
                                  strokeOpacity: 0.8,
                                  strokeWeight: 2,
                                  fillColor: "#000080",
                                  fillOpacity: 0.35,
                                  map: map,
                                  center: gas_map[gas].center,
                                  radius: gas_map[gas].users * 200
                                };
                                gasCircle = new google.maps.Circle(populationOptions);
                              }
                      for (var gas_na in gas_map_na) {
                                // Construct the circle for each value in citymap. We scale population by 20.
                                var populationOptions = {
                                  strokeColor: "#FF8080",
                                  strokeOpacity: 0.8,
                                  strokeWeight: 2,
                                  fillColor: "#FF8080",
                                  fillOpacity: 0.35,
                                  map: map,
                                  center: gas_map_na[gas_na].center,
                                  radius: gas_map_na[gas_na].users * 200
                                };
                                gasCircle = new google.maps.Circle(populationOptions);
                              }        
                              
                      '.$mr.'
                      
                      
                      
                     } 

                          
                    google.maps.event.addDomListener(window, \'load\', initialize_gas);  
                    </script>';


$h .= "<div class=\"map_canvas\" id=\"map_canvas_gas\" style=\"width: 100%; height: 100%\"></div>";
echo $h;
die();
}

if($wt==2){
    
$h .='
<script type="text/javascript">
 
   
  function initialize_ditte() {

    var latlng = new google.maps.LatLng(45.80, 8.400);

    var image3 = "'.$RG_addr["img_carrello"].'";
    
    var myOptions = {
      zoom: 6,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.TERRAIN,
      minZoom: 0,
      maxZoom: 16,
      disableDefaultUI: false
    };
    
    var map = new google.maps.Map(document.getElementById("map_canvas_ditte"),myOptions);
    

    var locations_ditte = ['.build_address_list_ditte().'];
    
    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    var pinIcon = new google.maps.MarkerImage(
    "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|FFFF00",
    null, /* size is determined at runtime */
    null, /* origin is 0,0 */
    null, /* anchor is bottom center of the scaled image */
    new google.maps.Size(12, 20)
    );
  
  
  for (i = 0; i < locations_ditte.length; i++) {  
      marker_ditte = new google.maps.Marker({
        position: new google.maps.LatLng(locations_ditte[i][1], locations_ditte[i][2]),
        map: map,
        icon: pinIcon
      });

      google.maps.event.addListener(marker_ditte, "click", (function(marker_ditte, i) {
        return function() {
          infowindow.setContent(\'<a href="'.$RG_addr["form_ditta_new"].'?id_ditta=\' + locations_ditte[i][4] +\'"><b>Ditta:</b> \' + locations_ditte[i][0] + \'</a><br>\');
          infowindow.open(map, marker_ditte);
        }
      })(marker_ditte, i));   
  }
   
  
  }
  google.maps.event.addDomListener(window, "load", initialize_ditte);

</script>
      
      ';
$h .= "<div class=\"map_canvas\" id=\"map_canvas_ditte\" style=\"width: 99%; height: 99%\"></div>";
echo $h;
die();   
}

if($wt==3){
$h .= '                   
                            <link rel="stylesheet" type="text/css" href="'.$RG_addr["css_rateit"].'" media="screen">
                            <script type="text/javascript" src="'.$RG_addr["js_markercluster"].'"></script>
                          <script type="text/javascript" src="'.$RG_addr["js_oms"].'"></script>
                          <script type="text/javascript" src="'.$RG_addr["js_rateit"].'"></script>';
$h .= '<script type="text/javascript">
                        
                        var bounds = new google.maps.LatLngBounds();
                        var markers = [];
                        var infoList = [];
                        
                        
                        var data='.create_json("ditte","SELECT * from retegas_ditte WHERE ditte_gc_lat>0").';
                      
                      function initialize_G3() {

                        
                        var gm = google.maps;
                        var map = new gm.Map(document.getElementById(\'map_G3\'), {
                          mapTypeId: gm.MapTypeId.TERRAIN,
                          center: new gm.LatLng(45.4419, 8.00), 
                          zoom: 6
                        });
                        var oms = new OverlappingMarkerSpiderfier(map);
                        var iw = new gm.InfoWindow();
                        oms.addListener(\'click\', function(marker) {
                          iw.setContent(marker.desc);
                          iw.open(map, marker);
                        });
                        oms.addListener(\'spiderfy\', function(markers) {
                          iw.close();
                        });
                        
                        for (var i = 0; i < data.dataNRecs; i++) {
                          
                          var dataDitte = data.ditte[i];
                          var latLng = new google.maps.LatLng(dataDitte.objLatitude,dataDitte.objLongitude);
                    
                          var contentTxt = dataDitte.objHtml;
                              
                          var marker = new google.maps.Marker({
                            position: latLng
                          });
                          bounds.extend(latLng);
                          
                          marker.infoWindow = new google.maps.InfoWindow({
                              content: contentTxt
                          });

                           google.maps.event.addListener(marker,\'click\',function() {
                              //InfoClose();
                              infoList.push(this);
                              this.infoWindow.open(map,this);
                              
                              jQuery(function ($) {
                                $(\'.rateit\').rateit();
                              }); 
                                   
                           });
                           
                           google.maps.event.addListener(marker, \'mouseover\', function() {
                                infoList.push(this);
                                this.infoWindow.open(map, this);
                                
                                jQuery(function ($) {
                                    $(\'.rateit\').rateit();
                                });
                                
                           });
                           
                           google.maps.event.addListener(marker, \'mouseout\', function() {
                                this.infoWindow.close();
                           });
                          
                          oms.addMarker(marker);
                          markers.push(marker);
                        }
                        
                        
                        
                        
                        map.fitBounds(bounds);
                        var mcOptions = {maxZoom: 10};
                        var markerCluster = new MarkerClusterer(map, markers,mcOptions);
                      }
                      google.maps.event.addDomListener(window, \'load\', initialize_G3);
                    </script>';    
    
$h .= "<p onclick=\"initialize_G3()\">Ditte in ReteDES v2</p>
        <div class=\"map_canvas\" id=\"map_G3\" style=\"width: 99%; height: 99%\"></div>";
echo $h;
die();    
}    