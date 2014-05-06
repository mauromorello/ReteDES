<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//
include_once ("gas_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    pussa_via();
    die();    
}    

	
	 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prender? come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel men? verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sar? indicata nella barra info 
	$retegas->posizione = "Attività utenti DES";
	  
	// Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = des_menu_completo(_USER_ID);
 
	// dico a retegas quali sono i fogli di stile che dovr? usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	  
	  
	// dico a retegas quali file esterni dovr? caricare
	$retegas->java_headers = array("rg","highcharts");  // ordinatore di tabelle
		  
	  // creo  gli scripts per la gestione dei menu
	  // MAPPA
	
	 $gas_address = gas_address_from_id($gas);
	  
	 	  
	   
	  $retegas->java_scripts_header[]=java_accordion(null, menu_lat::gas); // laterale    
	  $retegas->java_scripts_header[]=java_superfish();
	  
      $data_activity = gas_render_last_activity_data();
      
      $retegas->java_scripts_bottom_body[] = "
          <script type=\"text/javascript\"> 

          $(document).ready(function() {
                var chart = new Highcharts.Chart({
                   chart: {
                      renderTo: 'container1',
                      zoomType: 'x,y', 
                      defaultSeriesType: 'scatter'
                   },
                   legend: {
                        enabled: false
                    },
                    title: {
                        text: 'Attività recente del sito (zoommabile)'  
                    },
                    tooltip: {
                            formatter: function() {
                                return this.point.name;
                            }
                        },
                   plotOptions: {
                     scatter: {
                        marker: {
                           states: {
                              hover: {
                                 enabled: false
                              }
                           }
                        },
                        states: {
                           hover: {
                              marker: {
                                 enabled: false
                              }
                           }
                        }
                     }
                  },
                  yAxis: {
                        allowDecimals: false,
                        title: {
                            text: 'Ordini'
                        }
                    },
                   xAxis: {
                        type : 'datetime',
                        alternateGridColor: '#FDFFD5',
                        dateTimeLabelFormats: {
                            hour: '%e %b<br>%H:%M'   
                        }
                   }, 
                   series: [{
                      data: [$data_activity]                     
                   }]
                });
          });
          </script> 
      ";
          // orizzontale                         

	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  
		  // qui ci va la pagina vera e proria  
	  $retegas->content  =  '<div class="rg_widget rg_widget_helper">
                             <div style="position:relative; width: 100%; display:table;">
                             <div id="container1" style="width: 100%; height: 30em;   display:table-cell"></div>
                             </div></div>';

	  

	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
	  
	  
	  
?>