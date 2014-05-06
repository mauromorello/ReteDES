<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
include_once ("../storici_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

	// estraggo dal cookie le informazioni su chi ? che sta vedendo la pagina
	$cookie_read     =explode("|", base64_decode($user));
	$id_user  =  $cookie_read[0];
	$usr =       $cookie_read[1]; 
	$permission = $cookie_read[6];
										
	// e poi scopro di che gas ? l'user
	$id_gas = id_gas_user($id_user);
	
}else{
	pussa_via();
	exit;     
}    

	//COntrollo permessi
  
   
		 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prender? come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel men? verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sar? indicata nella barra info 
	$retegas->posizione = "Distribuzione spesa";
	  
	// Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito = storici_menu_completo();

	
	// dico a retegas quali sono i fogli di stile che dovr? usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	 
	  
	// dico a retegas quali file esterni dovr? caricare
	$retegas->java_headers = array("rg","highcharts","datepicker_loc");
	
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[] = java_accordion(null,3); // laterale    
	  $retegas->java_scripts_header[] = java_superfish();
      $retegas->java_scripts_header[] = java_datepicker("dat_1");
      $retegas->java_scripts_header[] = java_datepicker("dat_2");
      
    if($do=="filter"){
          sanitize($filter);
          if($filter=="date"){    
          $arg1 = sanitize($data_da);
          $arg2 = sanitize($data_a);
        }   
      }
 
 $serie_dati = storici_ditte_gas_grafico($id_user,$filter,$arg1,$arg2);
      // GRAFICO 2
        
      $retegas->java_scripts_bottom_body[] = "
      <script type=\"text/javascript\">
               var chart2; 
               $(document).ready(function() {
               chart2 = new Highcharts.Chart({
                chart: {
                    renderTo: 'container2',
                    defaultSeriesType: 'pie',
                    shadow:true
                },
                 credits: {
                        enabled: false
                 },                              
                title : {
                            text: 'Distribuzione spesa su ditte',
                            style: 'font-size:.7em;',
                            align: 'left'
                     },
                xAxis: {
                },
                tooltip: {
                     formatter: function() {
                        return '<b>'+ this.point.name +'</b>: '+ this.y +' Eu.';
                     }
                  },
                plotOptions: {
                         pie: {
                            size: '80%',
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                               enabled: true,
                               distance: 15,
                               connectorWidth: 1,
                               connectorPadding: 2,
                               formatter: function() {
                                        return this.y +' Eu.';
                                        }
                                          
                            },
                         showInLegend: true
                         }
                      },
                legend: {
                    align: 'right',
                    verticalAlign: 'top',
                    floating: false,
                    layout: 'vertical',
                    borderWidth: 0
                },                  
                series: [{
                     type: 'pie',
                     data: [".$serie_dati."
                             ]
                     
                     
                     
                  }]
            });
       });
      
      
      </script>
      ";		
        
      
      // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){
      	$retegas->messaggio=$msg;
	  }
		
	  $h =  '<div id="filter">
                    <form class="RG_form" method="POST">
                    <label for="data_da">Data iniziale (compresa)</label>
                    <input name="data_da" type="text" id="dat_1" size="10" value="'.$arg1.'"></input>
                    <label for="data_a">Data finale (esclusa)</label>
                    <input name="data_a" type="text" id="dat_2" size="10" value="'.$arg2.'"></input>
                    <input type="hidden" name="do" value="filter">
                    <input type="hidden" name="filter" value="date">
                    <input type="submit" name="submit" value="Filtra i dati" align="left" >
                    </form>           
                </div>'.
      '<div style="position:relative; width: 100%; display:table;">
                <div id="container2" style="width: 100%; height: 40em;   display:table-cell"></div>
       </div>';
		
          // qui ci va la pagina vera e proria  
	  $retegas->content  =  "<div class=\"rg_widget rg_widget_helper\">".$h."</div>";
	  
	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
	  
	  //echo "<br>Scheda : ".(array_sum(explode(' ', microtime())) - $start);
	  
?>