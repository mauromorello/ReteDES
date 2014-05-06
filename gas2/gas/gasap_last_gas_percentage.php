<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//
include_once ("gas_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    pussa_via();
    die;     
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_vedere_retegas)){
     go("sommario",_USER_ID,"Non hai i permessi necessari (Rilasciati dal tuo DES) per vedere questa pagina");
}	
	 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prenderà come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel menù verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sarà indicata nella barra info 
	$retegas->posizione = "Percentuali di utilizzo sito";
	  
	// Dico a retegas come sarà composta la pagina, cioè da che sezioni è composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale è pronto ma è vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = des_menu_completo(_USER_ID);
 
	// dico a retegas quali sono i fogli di stile che dovrà usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	  
	  
	// dico a retegas quali file esterni dovrà caricare
	$retegas->java_headers = array("rg","highcharts");  // ordinatore di tabelle
		  
	  // creo  gli scripts per la gestione dei menu
	  // MAPPA
	
	 $gas_address = gas_address_from_id(_USER_ID_GAS);
	  
	 	  
	   
	  $retegas->java_scripts_header[]=java_accordion(null,menu_lat::des); // laterale    
	  $retegas->java_scripts_header[]=java_superfish();
	  
      for($s=0;$s<50;$s++){
        $colo[$s]=random_color();          
      }
      $sql1 = "SELECT
                    Count(maaking_users.id_gas) as Contgas,
                    maaking_users.id_gas,
                    retegas_gas.descrizione_gas
                    FROM  maaking_users
                    Inner Join retegas_gas ON maaking_users.id_gas = retegas_gas.id_gas
                    WHERE maaking_users.last_activity >=  DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)
                    AND retegas_gas.id_des = '"._USER_ID_DES."'
                    GROUP BY maaking_users.id_gas
                    ORDER BY Contgas ASC";
      $res = $db->sql_query($sql1);
      //{ name: 'Firefox', y: 44.2, color: '#4572A7' },
      while ($row = mysql_fetch_array($res)){
            $data_activity_1 .="{ name: '".$row["descrizione_gas"]."', y: ".$row["Contgas"].", color:'#".$colo[$row["id_gas"]]."'}, 
            ";
      }
      $data_activity_1 = rtrim($data_activity_1,", ");
      
      
      $sql2= "SELECT
                    Count(maaking_users.id_gas) as Contgas,
                    maaking_users.id_gas,
                    retegas_gas.descrizione_gas
                    FROM
                    maaking_users
                    Inner Join retegas_gas ON maaking_users.id_gas = retegas_gas.id_gas
                    WHERE
                    maaking_users.last_activity >=  DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
                    AND retegas_gas.id_des = '"._USER_ID_DES."'
                    GROUP BY
                    maaking_users.id_gas
                    ORDER BY Contgas ASC";
      $res = $db->sql_query($sql2);
      //{ name: 'Firefox', y: 44.2, color: '#4572A7' },
      while ($row = $db->sql_fetchrow($res)){
            $data_activity_2 .="{ name: '".$row["descrizione_gas"]."', y: ".$row["Contgas"].", color: '#".$colo[$row["id_gas"]]."'}, 
            ";
 
      }
      $data_activity_2 = rtrim($data_activity_2,", ");
      
      
      
      $retegas->java_scripts_bottom_body[] = "
          <script type=\"text/javascript\"> 
 
 $(document).ready(function() {
   
   chart = new Highcharts.Chart({
      chart: {
         renderTo: 'container_chart',
         margin: [50, 0, 0, 0],
         plotBackgroundColor: 'none',
         plotBorderWidth: 0,
         plotShadow: false            
      },
      title: {
         text: 'Utilizzo sito',
         align: 'left'
      },
      subtitle: {
         text: 'Interno: ultima settimana, Esterno : ultimo mese<br>I dati sono calcolati in base<br>agli accessi univoci eseguiti',
         align: 'left'
      },
      legend: {
         layout: 'vertical',
         align: 'right',
         verticalAlign: 'bottom'
         
      },
      tooltip: {
         formatter: function() {
            return '<b>'+ this.series.name +'</b><br/>'+ 
               this.point.name +': '+ this.y +' accessi';
         }
      },
       series: [{
         type: 'pie',
         name: 'Ultima settimana',
         size: '40%',
         innerSize: '20%',
         data: [".$data_activity_1."
         ],
         dataLabels: {
            enabled: false
         }
      }, {
         type: 'pie',
         allowPointSelect: true,
         name: 'Ultimo mese',
         innerSize: '50%',
         data: [".$data_activity_2."],
         dataLabels: {
            enabled: true
         },
         showInLegend: true
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
                             <div id="container_chart" style="width: 100%; height: 30em;   display:table-cell"></div>
                             </div></div>';

	  

	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
	  
	  
	  
?> 