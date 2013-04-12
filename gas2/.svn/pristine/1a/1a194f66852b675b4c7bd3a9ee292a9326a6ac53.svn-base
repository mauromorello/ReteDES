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

	// estraggo dal cookie le informazioni su chi è che sta vedendo la pagina
	$cookie_read     =explode("|", base64_decode($user));
	$id_user  =  $cookie_read[0];
	$usr =       $cookie_read[1]; 
	$permission = $cookie_read[6];
										
	// e poi scopro di che gas è l'user
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
	if(id_gas_user(id_referente_ordine_proprio_gas($id_ordine,$id_gas))<>$id_gas){
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
	$retegas->posizione = "Scheda Ordine";
	  
	// Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
	 
    $retegas->menu_sito[] = ordini_menu_visualizza($user,$id_ordine);
	$retegas->menu_sito[] = ordine_menu_operazioni_base($id_user,$id_ordine);
	$retegas->menu_sito[] = ordine_menu_mia_spesa($id_user,$id_ordine);
	$retegas->menu_sito[] = ordine_menu_gas($id_user,$id_ordine,$id_gas);
	$retegas->menu_sito[] = ordine_menu_gestisci_new($id_user,$id_ordine,$id_gas);
    $retegas->menu_sito[] = ordine_menu_cassa($id_user,$id_ordine,$id_gas);
	$retegas->menu_sito[] = ordine_menu_comunica($id_user,$id_ordine,$id_gas);
	
	// dico a retegas quali sono i fogli di stile che dovrà usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	 
	  
	// dico a retegas quali file esterni dovrà caricare
	$retegas->java_headers = array("rg","highcharts");
	
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[] = java_accordion(null,3); // laterale    
	  $retegas->java_scripts_header[] = java_superfish();
      $retegasjava_scripts_bottom_body[] = "
      <script type=\"text/javascript\">
       var chart1; 
            $(document).ready(function() {
                  chart1 = new Highcharts.Chart({
                     chart: {
                        renderTo: 'chart-container-1',
                        defaultSeriesType: 'spline'
                     },
                     title: {
                        text: 'Andamento ordine',
                        align: 'left'
                     },
                     xAxis: {
                        categories: ['Operazioni']
                     },
                     yAxis: {
                        title: {
                           text: 'Valore'
                        }
                     },
                      tooltip: {
                         formatter: function() {        
                                                 return 'Eu. '+ this.y;   
                         }
                      },
                     plotOptions: {
                         areaspline: {
                            
                            marker: {
                               enabled: false,
                               symbol: 'circle',
                               radius: 5,
                               states: {
                                  hover: {
                                     enabled: true
                                  }
                               }
                            }
                         }
                      },
                     series: [{
                        name: 'Ordine $id_ordine',
                        data: [".crea_grafico_highcharts_1($id_ordine)."]
                     }]
                  });
               });
      
      
      
      </script>
      ";
      
      // GRAFICO 1 (VECCHIA VERSIONE)
      $retegasjava_scripts_bottom_body[] = "
      <script type=\"text/javascript\">
       var chart1; 
            $(document).ready(function() {
                  chart1 = new Highcharts.Chart({
                     chart: {
                        renderTo: 'container1',
                        defaultSeriesType: 'areaspline',
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
                               enabled: false,
                               symbol: 'circle',
                               radius: 2,
                               states: {
                                  hover: {
                                     enabled: true
                                  }
                               }
                            }
                         }
                      },
                     series: [{
                        name: 'Ordine $id_ordine',
                        data: [".crea_grafico_highcharts_1($id_ordine)."]
                     }]
                  });
               });
      
      
      
      </script>
      ";
      
           // GRAFICO 1_bis
      $retegas->java_scripts_bottom_body[] = "
      <script type=\"text/javascript\">
       var chart1; 
            $(document).ready(function() {
                  chart1 = new Highcharts.Chart({
                     chart: {
                        renderTo: 'container1',
                        defaultSeriesType: 'spline',
                        shadow:true,
                        margin: [ 40, 20, 70, 40],
                        zoomType: 'x,y'
                     },
                     legend :{
                            enabled: false
                     },                     
                     title : {
                            text: 'Andamento giornaliero',
                            style: 'font-size:.7em;',
                            align: 'left'
                     },
                     yAxis: {
                         min: 0,
                        title: {
                           text: ''
                        }
                     },
                      xAxis: {
                                 type: 'datetime',
                                 title: {
                                    text: null
                                 },
                                 labels: {
                                    rotation: -80,
                                    align: 'right',
                                    style: {
                                        font: 'normal 10px Verdana, sans-serif'
                                    }
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
                         spline: {
                           enabled: false, 
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

      // GRAFICO 2
      
          $bacino_tot_mio_gas = gas_n_user($id_gas);
          $bacino_part_mio_gas = ordine_bacino_utenti_part_gas($id_ordine,$id_gas);
          $bacino_non_part_mio_gas = $bacino_tot_mio_gas-$bacino_part_mio_gas;
      
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
                            text: 'Partecipazione mio GAS',
                            style: 'font-size:.7em;',
                            align: 'left'
                     },
                xAxis: {
                },
                tooltip: {
                     formatter: function() {
                        return '<b>'+ this.point.name +'</b>: '+ this.y;
                     }
                  },
                plotOptions: {
                         pie: {
                            size: '70%',
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                               enabled: true,
                               distance: 0,
                               connectorWidth: 0,
                               connectorPadding: 0
                                 
                            }
                         }
                      },                  
                series: [{
                     type: 'pie',
                     data: [
                        ['Part.',  ".$bacino_part_mio_gas."],
                        {
                           name: 'Non Part.',    
                           y: ".$bacino_non_part_mio_gas.",
                           sliced: true,
                           selected: true
                        }
                        ]
                  }]
            });
       });
      
      
      </script>
      ";		
        
      
      // GRAFICO 3
      
     $bacino_tot = ordine_bacino_utenti($id_ordine);
     $bacino_part = ordine_bacino_utenti_part($id_ordine);
     $bacino_non_part = $bacino_tot-$bacino_part;
      
      $retegas->java_scripts_bottom_body[] = "
      <script type=\"text/javascript\">
               var chart3; 
               $(document).ready(function() {          
               chart3 = new Highcharts.Chart({
                chart: {
                    renderTo: 'container3',
                    defaultSeriesType: 'pie',
                    shadow:true
                    
                },
                 credits: {
                        enabled: false
                 },                                
                title : {
                            text: 'Partecipazione Globale',
                            style: 'font-size:.7em;',
                            align: 'left'
                     },
                xAxis: {
                },
                tooltip: {
                     formatter: function() {
                        return '<b>'+ this.point.name +'</b>: '+ this.y;
                     }
                  },
                plotOptions: {
                         pie: {
                            size: '70%',
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                               enabled: true,
                               distance: 0,
                               connectorWidth: 0,
                               connectorPadding: 0
                                 
                            }
                         }
                      },                  
                series: [{
                     type: 'pie',
                     data: [
                        ['Part.',  ".$bacino_part."],
                        {
                           name: 'Non Part.',    
                           y: ".$bacino_non_part.",
                           sliced: true,
                           selected: true
                        }
                        ]
                  }]
            });
      }); 
      
      
      </script>
      ";
      
      // GRAFICO 4
      

      
      $retegas->java_scripts_bottom_body[] = "
      <script type=\"text/javascript\">
               var chart4; 
        $(document).ready(function() {
           chart4 = new Highcharts.Chart({
              chart: {
                 renderTo: 'container4',
                 defaultSeriesType: 'column',
                 
                 shadow: true,
                 zoomType :'y'
              },
              title : {
                            text: 'Composizione spesa',
                            style: 'font-size:.7em;',
                            align: 'left'
              },
              legend: {
                        enabled: false
              },
              credits: {
                        enabled: false
              }, 
              xAxis: {
                 categories: ['Spesa']
              },
              yAxis: {
                    title: {
                        text :'',
                        enabled:false
                    }
              },
              tooltip: {
                 formatter: function() {
                    return ''+
                        this.series.name +': '+ this.y +' ('+ Math.round(this.percentage) +'%)';
                 }
              },
              plotOptions: {
                size: '80%',
                 column: {
                    stacking: 'percent'
                 }
              },
              series: [
              {
                 name: '<b>Netto</b>',
                 data: [".valore_totale_ordine_qarr($id_ordine)."]
              },
              {
                 name: 'Costo GAS',
                 data: [".valore_assoluto_costo_mio_gas($id_ordine,$id_gas)."]
              },
              {
                 name: 'Maggiorazione GAS',
                 data: [".valore_maggiorazione_mio_gas($id_ordine,$id_gas,valore_totale_ordine_qarr($id_ordine))."]
              }, 
              {
                 name: 'Costo Trasporto',
                 data: [".valore_trasporto($id_ordine,100)."]
              }, 
              {
                 name: 'Costo Gestione',
                 data: [".valore_gestione($id_ordine,100)."]  
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
                <div id="container1" style="width: 25%; height: 20em;   display:table-cell"></div>
	            <div id="container2" style="width: 25%; height: 20em;   display:table-cell"></div>
	            <div id="container3" style="width: 25%; height: 20em;   display:table-cell"></div>
                <div id="container4" style="width: 25%; height: 20em;   display:table-cell"></div>
             
             </div>';
		
          // qui ci va la pagina vera e proria  
	  $retegas->content  =  $h;
	  
	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
	  
	  //echo "<br>Scheda : ".(array_sum(explode(' ', microtime())) - $start);
	  
?>