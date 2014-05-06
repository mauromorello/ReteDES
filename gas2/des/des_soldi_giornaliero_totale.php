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
     //pussa_via();
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = menu_lat::des;
//Assegno il titolo che compare nella barra delle info
$r->title = "Euro al giorno";
//Assegno anche la libreria HighCharts
$r->javascripts_header[]=java_head_highstocks();
//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà essere associato alla pagina.
//$r->menu_orizzontale = des_menu_completo(_USER_ID);


       $sql1 = "SELECT
                Sum(retegas_dettaglio_ordini.qta_arr * retegas_dettaglio_ordini.prz_dett_arr) AS totale_giorno,
                data_inserimento as data
                FROM
                retegas_dettaglio_ordini 
                GROUP BY DATE_FORMAT(data_inserimento, '%Y%m%d')
                ORDER BY data_inserimento ASC";
      $res = $db->sql_query($sql1);
      //{ name: 'Firefox', y: 44.2, color: '#4572A7' },
      while ($row = mysql_fetch_array($res)){
            $somma +=  CAST_TO_FLOAT($row["totale_giorno"]); 
            $data_activity_1 .="{ x: Date.UTC(".conv_date_to_javascript($row["data"])."), y: ".round($somma,0)."},";
      }
      $data_activity_1 = rtrim($data_activity_1,",");


//Disegno il grafico
$r->javascripts[]="
<script type=\"text/javascript\"> 
 
 jQuery(function() {
    // Create the chart
              Highcharts.setOptions({
                    lang: {
                        months: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'MAggio', 'Giugno', 
                            'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'],
                        weekdays: ['Domenica', 'Lunedi', 'Martedi', 'Mercoledi', 'Giovedi', 'Venerdi', 'Sabato']
                    }
                });
    
        
    window.chart = new Highcharts.StockChart({
        chart: {
            renderTo: 'container_chart',
            backgroundColor : 'rgba(255,255,255,.6)',
            style: {
                    fontSize: '9px'
                    }
        },
        
        rangeSelector: {
            selected: 2
            
        },
        
        title: {
            text: 'Andamento spesa Globale'
        },
        
        xAxis: {
            maxZoom: 30 * 24 * 3600000 // fourteen days
        },
        yAxis: {
            min:0,
            title: {
                text: 'Euro'
            }
        },
        
        series: [{
            name: 'Spesa ReteDes',
            type: 'line',
            turboThreshold : 3000,
            data: [".$data_activity_1."]
        }]
    });
});
   
          </script> 
      ";




//Questo è il contenuto della pagina
$r->contenuto = '<div class="rg_widget rg_widget_helper"><div style="position:relative; width: 100%; display:table;">
                             <div id="container_chart" style="width: 100%; height: 30em;   display:table-cell"></div>
                             </div></div>';
//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);