<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");
include_once ("../ordini_renderer.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    


if(!isset($id_ordine)){
    pussa_via();
}




//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Grafici ordine";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);

//Assegno le due tabelle a tablesorter
//$r->javascripts[]=java_tablesorter("output_1");
$r->javascripts_header[]= java_head_highcharts();
$r->javascripts_header[]= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$RG_addr["css_grid_3"]."\">";

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}


//GRAFICO 1


$bacino_tot_mio_gas = gas_n_user(_USER_ID_GAS);
$bacino_part_mio_gas = ordine_bacino_utenti_part_gas($id_ordine,_USER_ID_GAS);
$bacino_non_part_mio_gas = $bacino_tot_mio_gas-$bacino_part_mio_gas;
      
      $r->javascripts[] = "
      <script type=\"text/javascript\">
               var chart2; 
               $(document).ready(function() {
               chart2 = new Highcharts.Chart({
                chart: {
                    renderTo: 'grafico_2',
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
                            size: '80%',
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                               enabled: true,
                               distance: 20,
                               connectorWidth: 2,
                               connectorPadding: 10
                                 
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
            }, function(chart2){

    var setHeight = function() {
        chart2.setSize(chart2.chartWidth, chart2.chartWidth);
    }
    
    $(window).resize(setHeight);
       
    setHeight(); 
        
       });
    });  
    
</script>
";


// GRAFICO 3
      
     $bacino_tot = ordine_bacino_utenti($id_ordine);
     $bacino_part = ordine_bacino_utenti_part($id_ordine);
     $bacino_non_part = $bacino_tot-$bacino_part;
      
     $r->javascripts[] = "
      <script type=\"text/javascript\">
               var chart3; 
               $(document).ready(function() {          
               chart3 = new Highcharts.Chart({
                chart: {
                    renderTo: 'grafico_3',
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
                            size: '80%',
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                               enabled: true,
                               distance: 20,
                               connectorWidth: 2,
                               connectorPadding: 10
                                 
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
            }, function(chart3){

    var setHeight = function() {
        chart3.setSize(chart3.chartWidth, chart3.chartWidth);
    }
    
    $(window).resize(setHeight);
       
    setHeight(); 
        
       });
    });
</script>
      ";      
      
      // GRAFICO 4
      

      
      $r->javascripts[] = "
      <script type=\"text/javascript\">
               var chart4; 
        $(document).ready(function() {
           chart4 = new Highcharts.Chart({
              chart: {
                 renderTo: 'grafico_4',
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
           }, function(chart4){

    var setHeight = function() {
        chart4.setSize(chart4.chartWidth, chart4.chartWidth);
    }
    
    $(window).resize(setHeight);
       
    setHeight(); 
        
       });
   });
</script>
      ";


           // GRAFICO 1_bis
      $r->javascripts[] = "
      <script type=\"text/javascript\">
       var chart1; 
            $(document).ready(function() {
                  chart1 = new Highcharts.Chart({
                     chart: {
                        renderTo: 'grafico_1',
                        defaultSeriesType: 'areaspline',
                        shadow:true,
                        margin: [ 50, 10, 50, 10],
                        zoomType: 'x,y',
                        height: 200
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
                         min: 0
                     },
                      xAxis: {
                                 type: 'datetime',
                                 title: {
                                    text: null
                                 },
                                 labels: {
                                    enabled: false
                                 },
                                 alternateGridColor: '#FDFFD5'
                              },
                     credits: {
                            enabled: false
                     },    
                     plotOptions: {
                         spline: {
                           enabled: false, 
                            marker: {
                               
                               symbol: 'circle',
                               radius: 5,
                               states: {
                                  hover: {
                                     radius: 10,
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
                        data: [".crea_grafico_highcharts_3($id_ordine,
                                                   conv_date_from_db(ordini_field_value($id_ordine,"data_apertura")),
                                                   intval((gas_mktime(conv_date_from_db(ordini_field_value($id_ordine,"data_chiusura")))-gas_mktime(conv_date_from_db(ordini_field_value($id_ordine,"data_apertura"))))/(60*60*24)),
                                                   valore_totale_ordine($id_ordine))."]
                     }]
                  });
                  });      
   
</script>
      ";


      
//Contenuto
$h .= "<div class=\"container_3\">";
    
        $h .= "<p class=\"grid_3\" id=\"grafico_1\"></p>";
        $h .= "<div class=\"clear\" style=\"clear:none\"></div>";
        $h .= "<p class=\"grid_1\" id=\"grafico_2\"></p>";
        $h .= "<p class=\"grid_1\" id=\"grafico_3\"></p>";
        $h .= "<p class=\"grid_1\" id=\"grafico_4\"></p>";

$h .= "</div>";
//Questo ?? il contenuto della pagina
$r->contenuto = schedina_ordine($id_ordine,_USER_ID).$h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>