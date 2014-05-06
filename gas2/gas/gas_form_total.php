<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//
include_once ("gas_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

	// estraggo dal cookie le informazioni su chi è che sta vedendo la pagina
	$cookie_read     =explode("|", base64_decode($user));
	$id_user  = $cookie_read[0];
	$my_user_level = user_level($id_user);
	
	// Costruisco i menu 
	$mio_menu = des_menu_completo($id_user);
	

	
	// scopro come si chiama
	$usr = fullname_from_id($id_user);
	// e poi scopro di che gas è l'user
	$gas = id_gas_user($id_user);
	
}else{
	pussa_via();
	exit;     
}    

	
	 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prenderà come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel menù verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sarà indicata nella barra info 
	$retegas->posizione = "Mappa utenti retegas";
	  
	// Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = $mio_menu;
 
	// dico a retegas quali sono i fogli di stile che dovrà usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	  
	  
	// dico a retegas quali file esterni dovrà caricare
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
	var image = "'.$RG_addr["img_omino_2"].'";
    var image2 = "'.$RG_addr["img_gas_home"].'";
    var image3 = "'.$RG_addr["img_carrello"].'";
    
	var myOptions = {
	  zoom: 9,
	  center: latlng,
	  mapTypeId: google.maps.MapTypeId.TERRAIN,
	  minZoom: 0,
	  maxZoom: 16,
	  disableDefaultUI: true
	};
	
	var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
	
	var locations = ['.build_address_list_total().'];
    var locations_gas = ['.build_address_list_gas_2($gas).'];
    var locations_ditte = ['.build_address_list_ditte().'];
	
	var infowindow = new google.maps.InfoWindow();

	var marker, i;

	for (i = 0; i < locations.length; i++) {  
	  marker = new google.maps.Marker({
		position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		map: map,
		icon: image
	  });

	  google.maps.event.addListener(marker, "click", (function(marker, i) {
		return function() {
		  infowindow.setContent(locations[i][0]);
		  infowindow.open(map, marker);
		}
	  })(marker, i));   
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
  
  
  for (i = 0; i < locations_ditte.length; i++) {  
      marker_ditte = new google.maps.Marker({
        position: new google.maps.LatLng(locations_ditte[i][1], locations_ditte[i][2]),
        map: map,
        icon: image3
      });

      google.maps.event.addListener(marker_ditte, "click", (function(marker_ditte, i) {
        return function() {
          infowindow.setContent(locations_ditte[i][0]);
          infowindow.open(map, marker_ditte);
        }
      })(marker_ditte, i));   
  }
   
  
  }
  google.maps.event.addDomListener(window, "load", initialize);

</script>
	  
	  ';
	  
	   
	  $retegas->java_scripts_header[]=java_accordion(null, menu_lat::gas); // laterale    
	 
	  $retegas->java_scripts_header[]=java_superfish();
		  // orizzontale                         

	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  
		  // qui ci va la pagina vera e proria  
	  $retegas->content  =  "<div class=\"rg_widget rg_widget_helper\"><div id=\"map_canvas\" style=\"width:100%; height:460px\"></div></div>";

	  

	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);