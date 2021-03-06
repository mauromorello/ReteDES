<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//
include_once ("gas_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    pussa_via();
    die;     
}    

    
     
    // ISTANZIO un nuovo oggetto "retegas"
    // Prender? come variabile globale $user, nel caso di user loggato
    // allora visualizza la barra info ed il menu verticale,
    // nel caso di user non loggato visualizza la pagina con "benvenuto" e
    //nel men? verticale i campi per il login
    $retegas = new sito; 
     
    // assegno la posizione che sar? indicata nella barra info 
    $retegas->posizione = "Scheda mio GAS";
      
    // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
    
    //DISQUS
    $retegas->disqus_id = "GAS-"._USER_ID_GAS;
    $retegas->disqus_title = "Parliamo del ".gas_nome(_USER_ID_GAS);
      
    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito = gas_menu_completo();
 
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
      
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");  // ordinatore di tabelle
          
      // creo  gli scripts per la gestione dei menu
      // MAPPA
    
     $gas_address = gas_address_from_id(_USER_ID_GAS);
      
      $retegas->java_scripts_header[] = '
      <script type="text/javascript"
    src="http://maps.google.com/maps/api/js?sensor=false">
</script>
<script type="text/javascript">
 
   
  function initialize() {

    var latlng = new google.maps.LatLng('.db_val_q("id_gas",_USER_ID_GAS,"gas_gc_lat","retegas_gas").',
                                        '.db_val_q("id_gas",_USER_ID_GAS,"gas_gc_lng","retegas_gas").');
    var image = "'.$RG_addr["img_omino"].'";
    var image2 = "'.$RG_addr["img_gas_home"].'";
    var myOptions = {
      zoom: 12,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.TERRAIN,
      minZoom: 9,
      maxZoom: 12,
      disableDefaultUI: true
    };
    

    
    
    var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
    
    var locations = ['.build_address_list_gas(_USER_ID_GAS).'];
    var locations_gas = ['.build_address_list_gas_2(_USER_ID_GAS).'];
    
    var infowindow = new google.maps.InfoWindow();

    var marker,marker_gas, i;
    
    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
        icon: image
      });

      /*google.maps.event.addListener(marker, "click", (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));*/   
  }
  
     for (i = 0; i < locations_gas.length; i++) {  
      marker_gas = new google.maps.Marker({
        position: new google.maps.LatLng(locations_gas[i][1], locations_gas[i][2]),
        map: map,
        icon: image2
      });

      google.maps.event.addListener(marker_gas, "click", (function(marker_gas, i) {
        return function() {
          infowindow.setContent(locations_gas[i][0]);
          infowindow.open(map, marker_gas);
        }
      })(marker_gas, i));   
  } 
  
  
  }
  google.maps.event.addDomListener(window, "load", initialize);

</script>
      
      ';
      
       
      $retegas->java_scripts_header[]=java_accordion(null, menu_lat::gas); // laterale    
      $retegas->java_scripts_header[]=java_tablesorter("gas_table");
      $retegas->java_scripts_header[]=java_superfish();
          // orizzontale                         

      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }else{
        $retegas->messaggio .= read_option_text(_USER_ID,"MSG");
        delete_option_text(_USER_ID,"MSG");
      }
      
          // qui ci va la pagina vera e proria  
      $retegas->content  =  gas_render_scheda(_USER_ID_GAS);

      

      
      //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
      $html = $retegas->sito_render();
      // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
      echo $html;
      
      
      //distruggo retegas per recuperare risorse sul server
      unset($retegas);