<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//
include_once ("utenti_render.php");

 
// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

	// estraggo dal cookie le informazioni su chi è che sta vedendo la pagina
	$cookie_read     =explode("|", base64_decode($user));
	$id_user  =  $cookie_read[0];
	$usr =       $cookie_read[1]; 
	$permission = $cookie_read[6];
										
	// e poi scopro di che gas è l'user
	$gas = id_gas_user($id_user);
	
}else{
	pussa_via();
	exit;     
}    

   
    
    
if ($do=="mod"){
            
     
      
      $fullname     =         strip_tags(sanitize($fullname));
      $indirizzo    =         strip_tags(sanitize($indirizzo));
      $citta        =         strip_tags(sanitize($citta));
      $mail         =         strip_tags(sanitize($mail));
      $telefono     =         strip_tags(sanitize($telefono));           
      
      if (empty($fullname)){$msg.="..sei l'innominato ? <br>";$e_empty++;};
      if (empty($mail)){$msg.="La mail è necessaria<br>";$e_empty++;};
      if (empty($telefono)){$msg.="Il telefono è necessario<br>";$e_empty++;};
      
      if (!checkmail($mail)){$msg.="La mail non è in un formato valido<br>";$e_logical++;};
   
      $sql_email_check = $db->sql_query("SELECT email FROM maaking_users WHERE email='$mail' AND userid<>'$id_user'");
      $sql_username_check = $db->sql_query("SELECT fullname FROM maaking_users WHERE fullname='$fullname' AND userid<>'$id_user'");
      $email_check = $db->sql_numrows($sql_email_check);
      $username_check = $db->sql_numrows($sql_username_check);

      

      if($email_check > 0){
              $msg .= "Hai inserito una mail che esiste già<br>"; 
              $e_logical++;
              unset($email);
      }

      if($username_check > 0){
              $msg .= "C'è già una persona registrata con questo nome<br>"; 
              $e_logical++;
              unset($username);
      }   
    
   
   
   
      $msg.="<br>Verifica i dati immessi e riprova";
      
      
      $e_total = $e_empty + $e_logical + $e_numerical;
      
      if($e_total==0){
      //echo "ZERO ERRORI !!!";
        //DEBUG
      //echo "Data_1:(".$data_1.") ";
      //echo "Data_2:(".$data_2.") ";
      //echo "Data_3:(".$data_3.") <BR>";
      //echo "Data_4:(".$data_4.") ";
      //echo "Data_5:(".$data_5.") ";
      //echo "Data_6:(".$data_6.") <BR>";
      //DEBUG
        // QUERY EDIT
        $sql = "UPDATE maaking_users 
              SET 
              maaking_users.fullname = '$fullname',
              maaking_users.country = '$indirizzo',
              maaking_users.city = '$citta',
              maaking_users.email = '$mail',
              maaking_users.tel = '$telefono',
              user_gc_lat ='$lat',
              user_gc_lng ='$lng'
              WHERE 
              maaking_users.userid = $id_user LIMIT 1;";
              
        $result = $db->sql_query($sql);
        
        //$res_geocode = geocode_users_table("SELECT * FROM maaking_users WHERE (userid='$id_user')");
        log_me(0,$id_user,"USR","MOD","Modificati dati personali",null,$sql);
        
        
                 
         if (is_null($result)){
            $msg = "Errore nella modifica dei dati";
            include ("../index.php");
            exit;  
        }else{
            $msg = "Dati modificati";
            include("utenti_form_mia.php");
            exit;  
        };
        
        //EDIT END --------------------------------------------------------- 
        
        
        
        include("ditte_table.php");
        exit; 
          
      } // se non ci sono errori
      
}
		 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prenderà come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel menù verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sarà indicata nella barra info 
	$retegas->posizione = "Modifica i tuoi dati personali";
	  
	// Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito[] = menu_visualizza_user($id_user);
    $retegas->menu_sito[] = menu_gestisci_user($id_user,$id);
 
	// dico a retegas quali sono i fogli di stile che dovrà usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	 
	  
	// dico a retegas quali file esterni dovrà caricare
	$retegas->java_headers = array( "rg");  // editor di testo
	
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[] = java_accordion(null,1); // laterale    
	  $retegas->java_scripts_header[] = java_superfish();
	  $retegas->java_scripts_bottom_body[] = java_qtip(".retegas_form h5[title]");

      $retegas->java_scripts_header[] = '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>';
      $retegas->java_scripts_bottom_body[] ="<script>
                                                var geocoder;
                                                var map;
                                                function initialize() {
                                                  geocoder = new google.maps.Geocoder();
                                                  var latlng = new google.maps.LatLng(45,8);
                                                  var mapOptions = {
                                                    zoom: 6,
                                                    center: latlng,
                                                    mapTypeId: google.maps.MapTypeId.ROADMAP
                                                  }
                                                  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
                                                }

                                                function codeAddress() {
                                                  var address = document.getElementById('address1').value + ', ' + document.getElementById('address2').value;
                                                  geocoder.geocode( { 'address': address}, function(results, status) {
                                                    if (status == google.maps.GeocoderStatus.OK) {
                                                      map.setCenter(results[0].geometry.location);
                                                      
                                                      var marker = new google.maps.Marker({
                                                          map: map,
                                                          position: results[0].geometry.location
                                                      });
                                                      
                                                      $('#lat').val (results[0].geometry.location.lat());
                                                      $('#lng').val (results[0].geometry.location.lng());
                                                      $('#ir').html('INDIRIZZO RICONOSCIUTO');
                                                    } else {
                                                      //alert('Geocode was not successful for the following reason: ' + status);
                                                      $('#ir').html('INDIRIZZO NON RICONOSCIUTO ('+ status +')');
                                                    }
                                                  });
                                                }

                                                google.maps.event.addDomListener(window, 'load', initialize);
                                                </script>";
		  // orizzontale                         

	  
		   
	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  
		
	  //$h =ordine_render_add_simple($id);
	  
	  
	  // qui ci va la pagina vera e proria  
	  $retegas->content  =  utenti_render_form_edit($id_user);
	  
	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
