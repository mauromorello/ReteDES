<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//
include_once ("gas_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    go("sommario");
}   

	
	 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prender? come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel men? verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sar? indicata nella barra info 
	$retegas->posizione = "Mappa ditte";
	  
	// Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = ditte_menu_completo(null);
 
	// dico a retegas quali sono i fogli di stile che dovr? usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	  
	  
	// dico a retegas quali file esterni dovr? caricare
	$retegas->java_headers = array("rg");  // ordinatore di tabelle
		  
	  // creo  gli scripts per la gestione dei menu
	  // MAPPA
	
	 $gas_address = gas_address_from_id($gas);
	  
	  $retegas->java_scripts_header[] = '
	  <script type="text/javascript"
	src="http://maps.google.com/maps/api/js?sensor=false">
</script>
<script type="text/javascript">
 
   
  function initialize() {

	var latlng = new google.maps.LatLng(45.80, 8.400);

    var image3 = "'.$RG_addr["img_carrello"].'";
    
	var myOptions = {
	  zoom: 5,
	  center: latlng,
	  mapTypeId: google.maps.MapTypeId.TERRAIN,
	  minZoom: 0,
	  maxZoom: 16,
	  disableDefaultUI: false
	};
	
	var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
	

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
          infowindow.setContent(\'<a href="'.$RG_addr["form_ditta"].'?id_ditta=\' + locations_ditte[i][4] +\'"><b>Ditta:</b> \' + locations_ditte[i][0] + \'</a><br>\');
          infowindow.open(map, marker_ditte);
        }
      })(marker_ditte, i));   
  }
   
  
  }
  google.maps.event.addDomListener(window, "load", initialize);

</script>
	  
	  ';
	  
	   
	  $retegas->java_scripts_header[]=java_accordion("#accordion",menu_lat::anagrafiche); // laterale    
	 
	  $retegas->java_scripts_header[]=java_superfish();
		  // orizzontale                         

	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  
		  // qui ci va la pagina vera e proria  
	  $retegas->content  =  "<div id=\"map_canvas\" style=\"width:100%; height:460px\"></div>";

	  

	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);