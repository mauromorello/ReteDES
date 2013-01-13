<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "IDS - Indice Di Solidarietà ";


if(!isset($data_da)){
    $sql_data_da="SELECT * from maaking_users WHERE id_gas='"._USER_ID_GAS."' ORDER BY regdate ASC LIMIT 1;";
    $res_data_da=$db->sql_query($sql_data_da);
    $row = $db->sql_fetchrow($res_data_da);
    $data_da=conv_date_from_db($row["regdate"]);
    
}

    
if(!isset($data_a)){$data_a=date("d/m/Y");};


$finestra = CAST_TO_INT($finestra,30,365);

//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = gas_menu_completo();

//Assegno le due tabelle a tablesorter
$r->javascripts_headers[]=java_head_highstocks();
$r->javascripts_header[]=java_head_datetimepicker();
$r->javascripts_header[]=java_head_highstocks();
$r->javascripts[]=java_datepicker("data_da");
$r->javascripts[]=java_datepicker("data_a");

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}

//if(!isset($considera_aiuti)){$considera_aiuti="SI";}
if($considera_aiuti=="SI"){$checked=" CHECKED ";}

$filtro = <<<FFF
            <br>
            <form action="" method="POST">
            <label for="data_da">Da :</label>
            <input id="data_da" type="text" name="data_da" value="$data_da" size="10">
            <label for="data_a">a :</label>
            <input id="data_a" type="text" name="data_a" value="$data_a" size="10">
            <label for="finestra">Finestra temporale :</label>
            <input id="finestra" type="text" name="finestra" value="$finestra" size="2">
            <label for="considera_aiuti">Includi aiuti:</label>
            <input id="considera_aiuti" type="checkbox" name="considera_aiuti" $checked value="SI">
            <input type="submit" name="submit" value="Filtra i dati">
            <input type="hidden" name="do" value="filter">
            </form>
            
FFF;
date_default_timezone_set('UTC');

$filter = rg_toggable("Filtra i dati","filter",$filtro,false);
$gas_nome = gas_nome(_USER_ID_GAS);
//-----------------------------------------ESTRAZIONE DATI
// ECHO "data_da $data_da<br>
//       data_a $data_a;<br>
//       finestra $finestra<br>";

$orig_mask = "d/m/Y"; 
$new_mask = "Y-m-d"; 

$ob = new Date_Time_Converter($data_da, $orig_mask); 
$date = $ob->convert($new_mask);


$ob = new Date_Time_Converter($data_a, $orig_mask); 
$data_to = $ob->convert($new_mask);

$end_date =  date("Y-m-d",strtotime("-$finestra day", strtotime($data_to)));
 
 
//ECHO "date $date<br>
//       end_date $end_date<br";

//$end_date =  strtotime("-$finestra day", $data_to);
 

       
 $dati = "[";
 $dati_oa = "[";
 $dati_ordtot = "[";
 $dati_gest = "[";
 $dati_aiuti = "[";
 
 while (strtotime($date) <= strtotime($end_date)) {
        //Ciclo che passa tutti i giorni tranne i -finestra dalla fine
        
        $num_utenti_gas_alla_data = gas_n_user_data(_USER_ID_GAS,conv_date_to_db(date("d/m/Y",strtotime($date))));
        $utenti_alla_data = $num_utenti_gas_alla_data;
        
        $data_apertura = conv_date_to_db(date("d/m/Y",strtotime($date)));
        $data_chiusura = conv_date_to_db(date("d/m/Y",strtotime("+$finestra day",strtotime($date))));
        
        //$sql_oa = "SELECT * FROM retegas_ordini WHERE data_apertura>'$data_apertura' AND data_apertura<'$data_chiusura';";
        $sql_oa = "SELECT
                    Count(retegas_ordini.id_ordini) AS conteggio_ordini,
                    retegas_referenze.id_utente_referenze
                    FROM
                    retegas_ordini
                    Inner Join retegas_referenze ON retegas_ordini.id_ordini = retegas_referenze.id_ordine_referenze
                    Inner Join maaking_users ON retegas_referenze.id_utente_referenze = maaking_users.userid
                    WHERE
                    maaking_users.id_gas =  '"._USER_ID_GAS."'
                    AND data_apertura BETWEEN '$data_apertura' AND '$data_chiusura'
                    GROUP BY
                    retegas_referenze.id_utente_referenze";
        //echo "SQL - $sql_oa<br>";
        $res_oa = $db->sql_query($sql_oa);
        $ut_gestori = $db->sql_numrows($res_oa); // UTENTI CHE HANNO GESTITO
        unset($ord_tot);
        while ($row = $db->sql_fetchrow($result)){
            $ord_tot =$ord_tot+$row["conteggio_ordini"];
        }

        if($considera_aiuti<>"SI"){
                $num_aiuti=0;
        }else{
                 $sql_aiuti = "SELECT * from retegas_options 
                                Inner Join maaking_users ON retegas_options.id_user = maaking_users.userid
                                WHERE chiave='AIUTO_ORDINI' AND timbro BETWEEN '$data_apertura' AND '$data_chiusura'
                                AND maaking_users.id_gas='"._USER_ID_GAS."'";
                 $res_aiuti = $db->sql_query($sql_aiuti);
                 $num_aiuti = $db->sql_numrows($res_aiuti);   
        };
        
        $is = round((($ut_gestori + $num_aiuti) / $utenti_alla_data)*100,2); 
        
        $dati .= "[".strtotime($date)."000,$utenti_alla_data],";
        $dati_oa .="[".strtotime($date)."000,$is],";
        $dati_ordtot .="[".strtotime($date)."000,$ord_tot],";
        $dati_gest .="[".strtotime($date)."000,$ut_gestori],";
        $dati_aiuti .="[".strtotime($date)."000,$num_aiuti],"; 
        //echo "$date ---> ".strtotime($date)."000 -- $num_utenti_gas_alla_data --> ".conv_date_to_db(date("d/m/Y",strtotime($date)))."<br>";
        
        $date = date ("Y-m-d", strtotime("+$finestra day", strtotime($date)));
 }
 $dati = rtrim($dati,",");
 $dati .= "]";
 
 $dati_oa = rtrim($dati_oa,",");
 $dati_oa .= "]";
 
 $dati_ordtot = rtrim($dati_ordtot,",");
 $dati_ordtot .= "]";
 
 $dati_gest = rtrim($dati_gest,",");
 $dati_gest .= "]";
 
 $dati_aiuti = rtrim($dati_aiuti,",");
 $dati_aiuti .= "]"; 
 //echo $dati;
 //die();


//-----------------------------------------ESTRAZIONE DATI






$r->javascripts[]=<<<JJJ
<script type="text/javascript">
$(function() {
        
        var data = $dati;
        var data_oa = $dati_oa;
        var data_ordtot = $dati_ordtot;
        var data_gest = $dati_gest;
        var data_aiuti = $dati_aiuti; 
        window.chart = new Highcharts.StockChart({
            chart : {
                renderTo : 'ids_grafico'
            },
                        
            rangeSelector : {
                selected : 1
            },
            yAxis: {
                min: 0,
                max: 100  
            },
            title : {
                text : 'Indice di solidarietà $gas_nome su $finestra giorni'
            },
            
            series : [{
                name : 'Ordini Aperti',
                data : data_ordtot,
                
                lineWidth : 0,
                marker : {
                    enabled : true,
                    radius : 1
                },
                tooltip: {
                    valueDecimals: 0,
                    xDateFormat: '%e/%b/%Y'
                }},
                {
                name : 'Gestori',
                data : data_gest,
                
                lineWidth : 0,
                marker : {
                    enabled : true,
                    radius : 3
                },
                tooltip: {
                    valueDecimals: 0,
                    xDateFormat: '%e/%b/%Y'
                }},
                {
                name : 'Aiuti',
                data : data_aiuti,
                
                lineWidth : 0,
                marker : {
                    enabled : true,
                    radius : 3
                },
                tooltip: {
                    valueDecimals: 0,
                    xDateFormat: '%e/%b/%Y'
                    
                }},
                {
                name : 'IdS',
                data : data_oa,
                type: 'areaspline',
                lineWidth : 2,
                tooltip: {
                    valueDecimals: 1,
                    xDateFormat: '%e/%b/%Y'
                },
                fillColor : {
                    linearGradient : {
                        x1: 0, 
                        y1: 0, 
                        x2: 0, 
                        y2: 1
                    },
                    stops : [[0, Highcharts.getOptions().colors[3]], [1, 'rgba(0,0,0,0)']]
                }
                },
                {
                name : 'Utenti Attivi',
                data : data,
                tooltip: {
                    valueDecimals: 0,
                    xDateFormat: '%e/%b/%Y'
                }
                
            }]
        });
    

});
</script>"
JJJ;





$h = "<div class=\"rg_widget rg_widget_helper\">
      <h3>Indice di Solidarietà</h3>
      <p>consulta <a href=\"https://sites.google.com/site/retegasapwiki/meccanismi-di-funzionamento/ids---indice-di-solidarieta\">wiki.retedes.it</a> per maggiori informazioni</p>
      $filter
      <div id=\"ids_grafico\" style=\"width:100%;height:30em\"></div>
      </div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>