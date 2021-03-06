<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
include_once ("../ordini_renderer.php");

//Ricevo come GET id = id_ordine
// Lo obbligo ad essere un intero
(int)$id_ordine;
(int)$id;
if(isset($id)){$id_ordine=$id;}

// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

	// estraggo dal cookie le informazioni su chi � che sta vedendo la pagina
	$cookie_read     =explode("|", base64_decode($user));
	$id_user  =  $cookie_read[0];
	$usr =       $cookie_read[1]; 
	$permission = $cookie_read[6];
										
	// e poi scopro di che gas � l'user
	$id_gas = id_gas_user($id_user);
	
}else{
	pussa_via();
	exit;     
}    

	//COntrollo permessi
  
	
	if(ordine_inesistente($id_ordine)){
		pussa_via();
		exit;
	}
	
	 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prender� come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel men� verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sar� indicata nella barra info 
	$retegas->posizione = "Scheda Ordine";
	  
	// Dico a retegas come sar� composta la pagina, cio� da che sezioni � composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale � pronto ma � vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito[] = ordini_menu_pacco($id_ordine); 
    $retegas->menu_sito[] = ordini_menu_visualizza($user,$id_ordine);
	$retegas->menu_sito[] = ordine_menu_operazioni_base($id_user,$id_ordine);
	$retegas->menu_sito[] = ordine_menu_mia_spesa($id_user,$id_ordine);
	$retegas->menu_sito[] = ordine_menu_gas($id_user,$id_ordine,$id_gas);
	$retegas->menu_sito[] = ordine_menu_gestisci_new($id_user,$id_ordine,$id_gas);
	$retegas->menu_sito[] = ordine_menu_comunica($id_user,$id_ordine,$id_gas);
	
	// dico a retegas quali sono i fogli di stile che dovr� usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	 
	  
	// dico a retegas quali file esterni dovr� caricare
	$retegas->java_headers = array("rg","highcharts");
	
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[] = java_accordion(null,3); // laterale    
	  $retegas->java_scripts_header[] = java_superfish();

      
      // GRAFICO 1_bis
      $retegas->java_scripts_bottom_body[] = "
      <script type=\"text/javascript\">
       var chart1; 
            $(document).ready(function() {
                  chart1 = new Highcharts.Chart({
                     chart: {
                        renderTo: 'container1',
                        defaultSeriesType: 'column',
                        shadow:true
                     },
                     legend :{
                            enabled: false
                     },                     
                     title : {
                            text: 'Andamento',
                            style: 'font-size:.7em;',
                            align: 'left'
                     },
                     yAxis: {
                        title: {
                           text: ''
                        }
                     },
                      xAxis: {
                                 type: 'datetime',
                                 title: {
                                    text: null
                                 }
                              },
                     credits: {
                            enabled: false
                     },                   
                     tooltip: {
                         formatter: function() {        
                                                 return '<b>Eu.</b> '+ this.y;   
                         }
                     },    
                     plotOptions: {
                         areaspline: {
                            
                            marker: {
                               symbol: 'circle',
                               radius: 2,
                               states: {
                                  hover: {
                                     radius: 5,
                                     enabled: true
                                  }
                               }
                            }
                         }
                      },
                     series: [{
                        name: 'Ordine $id_ordine',
                        pointInterval: 24 * 3600 * 1000,
                        pointStart: Date.UTC(".date("Y",gas_mktime(conv_date_from_db(ordini_field_value($id_ordine,"data_apertura")))).",
                                             ".intval(date("m",gas_mktime(conv_date_from_db(ordini_field_value($id_ordine,"data_apertura"))))-1).",
                                             ".date("d",gas_mktime(conv_date_from_db(ordini_field_value($id_ordine,"data_apertura"))))."
                                             ),
                        data: [".crea_grafico_highcharts_2($id_ordine,
                                                   conv_date_from_db(ordini_field_value($id_ordine,"data_apertura")),
                                                   intval((gas_mktime(conv_date_from_db(ordini_field_value($id_ordine,"data_chiusura")))-gas_mktime(conv_date_from_db(ordini_field_value($id_ordine,"data_apertura"))))/(60*60*24)),
                                                   valore_totale_ordine($id_ordine))."]
                     }]
                  });
               });
      
      
      
      </script>
      ";

    
	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){
      	$retegas->messaggio=$msg;
	  }
		
	  $h =  schedina_ordine($id_ordine,$id_user).
            '<div style="position:relative; width: 100%; display:table;">
                <div id="container1" style="width: 100%; height: 25em;   display:table-cell"></div>        
             </div>';
		
          // qui ci va la pagina vera e proria  
	  $retegas->content  =  $h;
	  
	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  
      echo $html;
      //echo $id_ordine;
      //echo ordini_field_value($id_ordine,"data_apertura");
     // echo convertiDataTime_from_db(ordini_field_value($id_ordine,"data_apertura")); 
	 // echo date("Y",gas_mktime(conv_date_from_db(ordini_field_value($id_ordine,"data_apertura"))));
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
	  
	  //echo "<br>Scheda : ".(array_sum(explode(' ', microtime())) - $start);
	  
?>