<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//
//include_once ("gas_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

	// estraggo dal cookie le informazioni su chi � che sta vedendo la pagina
	$cookie_read     =explode("|", base64_decode($user));
	$id_user  = $cookie_read[0];
	$my_user_level = user_level($id_user);
	
	// Costruisco i menu 
	$mio_menu = des_menu_completo($id_user);
	
   	
	// scopro come si chiama
	$usr = fullname_from_id($id_user);
	// e poi scopro di che gas � l'user
	$id_gas = id_gas_user($id_user);
	
}else{
	pussa_via();
exit;     
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_vedere_retegas)){
     go("sommario",_USER_ID,"Non hai i permessi necessari o questa pagina non � disponibile per il tuo DES");
}
	 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prender� come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel men� verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sar� indicata nella barra info 
	$retegas->posizione = "Panoramica della spesa di ogni GAS";
	  
	// Dico a retegas come sar� composta la pagina, cio� da che sezioni � composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale � pronto ma � vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = $mio_menu;
 
	// dico a retegas quali sono i fogli di stile che dovr� usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	  
	  
	// dico a retegas quali file esterni dovr� caricare
	$retegas->java_headers = array("rg","highcharts");  // ordinatore di tabelle
		  
	  // creo  gli scripts per la gestione dei menu
	  // MAPPA
	
	 $gas_address = gas_address_from_id($gas);
	  
	 	  
	   
	  $retegas->java_scripts_header[]=java_accordion("#accordion",menu_lat::des); // laterale    
	  $retegas->java_scripts_header[]=java_superfish();

      
      for($s=0;$s<50;$s++){
        $colo[$s]=random_color();          
      }
      
      $sql_gas = "SELECT * FROM retegas_gas";
      $res_gas = $db->sql_query($sql_gas);
      while ($row_gas = mysql_fetch_array($res_gas)){

              unset($data_activity_2);
              unset($somma2);
      
              $sql2 = "SELECT
                        Sum(retegas_dettaglio_ordini.qta_arr * prezzo) AS totale_giorno,
                        data_inserimento as data
                        FROM
                        retegas_dettaglio_ordini
                        Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
                        Inner Join maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
                        WHERE
                        maaking_users.id_gas =  '".$row_gas["id_gas"]."'
                        GROUP BY DATE_FORMAT(data_inserimento, '%Y%m%d')
                        ORDER BY data_inserimento ASC";
              $res2 = $db->sql_query($sql2);
              //{ name: 'Firefox', y: 44.2, color: '#4572A7' },
              while ($row = mysql_fetch_array($res2)){
                    $somma2 = $somma2 + CAST_TO_FLOAT($row["totale_giorno"]); 
                    $data_activity_2 .="{ x: Date.UTC(".conv_date_to_javascript($row["data"])."), y: ".round($somma2,0)."}, 
                                          ";
              }
              $data_activity_2 = rtrim($data_activity_2,", ");
              
              $serie .= "{
                        name: '".$row_gas["descrizione_gas"]."',
                        data: [".$data_activity_2."],
                        pointInterval: 7 * 24 * 3600 * 1000 // hourly data
                        },";

      
      }
      $serie = rtrim($serie,",");
      
      $retegas->java_scripts_bottom_body[] = "
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
    
        
    window.chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container_chart',
         defaultSeriesType: 'spline'
        },
        title: {
            text: 'Andamento spesa'
        },
        
        legend: {
            enabled: true,
            align: 'right',
            layout: 'vertical',
            verticalAlign: 'top',
            shadow: true
        },
        
        xAxis: { type: 'datetime',
            maxZoom: 30 * 24 * 3600000 // fourteen days
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Euro'
            }
        },
        plotOptions: {
         spline: {
            stacking: 'normal'
         }
      },
        series: [".$serie."]
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
	  $retegas->content  =  '<div style="position:relative; width: 100%; display:table;">
                             <div id="container_chart" style="width: 100%; height: 36em;   display:table-cell"></div>
                             </div>';

	  

	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
	  
	  
	  
?>