<?php


  
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_vedere_retegas)){
     go("sommario",_USER_ID,"Non hai i permessi necessari (Rilasciati dal tuo DES) per vedere questa pagina");
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::des;
//Assegno il titolo che compare nella barra delle info
$r->title = "Dimensioni Gas";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = des_menu_completo(_USER_ID);

//Assegno le due tabelle a tablesorter
$r->javascripts_header[]='<script type="text/javascript"
                              src="http://maps.googleapis.com/maps/api/js?key='.GOOGLE_KEY.'&sensor=false">
                            </script>';

                            
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
            id_des ="._USER_ID_DES."
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
            id_des ="._USER_ID_DES."
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


                            
$r->javascripts[]='<script type="text/javascript">
                      
                      var gas_map = {};
                      var gas_map_na ={};
                      
                      '.$jd.'
                      '.$jd_na.'
                      
                      function initialize() {
                        var mapOptions = {
                          center: new google.maps.LatLng('.db_val_q("id_des",_USER_ID_DES,"des_lat","retegas_des").',
                                                         '.db_val_q("id_des",_USER_ID_DES,"des_lng","retegas_des").'),
                          zoom: 9,
                          mapTypeId: google.maps.MapTypeId.TERRAIN
                        };
                        var map = new google.maps.Map(document.getElementById("map_canvas"),
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
                      
                      
                      
                    </script>';
                    
                    
$r->body_tags =' onload="initialize()" ';

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h = "  <div class=\"rg_widget rg_widget_helper\">
        <h3>Dimensioni GAS</h3>
        <p>Blu = Utenti attivi; Rosso = Utenti sospesi o non attivi</p>
        <div id=\"map_canvas\" style=\"width: 100%; height: 30em\"></div>
        </div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>