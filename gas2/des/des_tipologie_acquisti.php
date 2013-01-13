<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//controlla se l'utente ha i permessi necessari
if(!(_USER_PERMISSIONS & perm::puo_vedere_retegas)){
     pussa_via();
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = menu_lat::des;
//Assegno il titolo che compare nella barra delle info
$r->title = "Distribuzione tipologie acquisti";
//Assegno anche la libreria HighCharts
$r->javascripts_header[]=java_head_highcharts();
//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà essere associato alla pagina.
$r->menu_orizzontale = des_menu_completo(_USER_ID);


//Calcolo i valori del grafico
   $query = "SELECT
                Count(retegas_tipologia.id_tipologia) as count_tipo,
                retegas_tipologia.descrizione_tipologia
                FROM
                retegas_ordini
                Inner Join retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini
                Inner Join retegas_tipologia ON retegas_listini.id_tipologie = retegas_tipologia.id_tipologia
                GROUP BY
                retegas_tipologia.id_tipologia
                ORDER BY
                count_tipo DESC";
                
   $result = $db->sql_query($query);
    
   while ($row = mysql_fetch_array($result)){

   $ditta = $row["descrizione_tipologia"];
   $totale_ordine = $row["count_tipo"];
   
   $o.=       '[\''.substr(strip_tags($ditta),0,30)." ...".'\', '.number_format($totale_ordine,2,",","").'], 
   ';

   }
   $o = rtrim($o,",");


//Disegno il grafico
$r->javascripts[]="
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
                            text: 'Tipologie acquisti DES',
                            style: 'font-size:.7em;',
                            align: 'left'
                     },
                xAxis: {
                },
                tooltip: {
                     formatter: function() {
                        return '<b>'+ this.point.name +'</b>: '+ this.y +' Ordini';
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
                                        return this.y +' Ordini';
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
                     data: [".$o."
                             ]
                     
                     
                     
                  }]
            });
       });
      
      
      </script>
      ";



//Questo è il contenuto della pagina
$r->contenuto = '<div style="position:relative; width: 100%; display:table;">
                             <div id="container2" style="width: 100%; height: 30em;   display:table-cell"></div>
                             </div>';
//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>