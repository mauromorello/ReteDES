<?php
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
} 
		  
	  // creo  gli scripts per la gestione dei menu
	  // MAPPA
	
	 $gas_address = gas_address_from_id(_USER_ID_GAS);
	  





//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::gas;
//Assegno il titolo che compare nella barra delle info
$r->title = "Tutti i gas di ReteDES";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = gas_menu_completo($user);

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$r->javascripts[] = '
      <script type="text/javascript"
    src="http://maps.google.com/maps/api/js?sensor=false">
</script>
<script type="text/javascript">
 
   
  function initialize() {

    var latlng = new google.maps.LatLng(43, 11);
    var image = "'.$RG_addr["img_omino_2"].'";
    var image2 = "'.$RG_addr["img_gas_home"].'";
    var image3 = "'.$RG_addr["img_carrello"].'";
    
    var myOptions = {
      zoom: 6,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.TERRAIN,
      minZoom: 0,
      maxZoom: 16,
      disableDefaultUI: false
    };
    
    var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
    
    
    var locations_gas = ['.build_address_list_gas_2(_USER_ID_GAS).'];
    
    
    var infowindow = new google.maps.InfoWindow();

    var marker, i;


  
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
      
          // qui ci va la pagina vera e proria  
$h  =  "<div class=\"rg_widget rg_widget_helper\"><div id=\"map_canvas\" style=\"width: 100%; height: 42em\"></div></div>";
   


//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>
