<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("ditte_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){

	pussa_via();
	exit;     
}    
   
   if($id_ditta==""){
       pussa_via();
   }
   
   // ISTANZIO un nuovo oggetto "retegas"

	$retegas = new sito;

	$retegas->posizione = "Scheda ditta statistiche articoli";
	
 
	$ref_table ="output";


	$retegas->sezioni = $retegas->html_standard;


	$retegas->menu_sito = ditte_menu_completo($id_ditta);
	//$retegas->menu_sito[]=$h_menu;
 
	// dico a retegas quali sono i fogli di stile che dovrà usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	 
	  
	// dico a retegas quali file esterni dovrà caricare
	$retegas->java_headers = array("rg","highcharts"); 
		  
	  // creo  gli scripts per la gestione dei menu
	  
	  $retegas->java_scripts_header[] = java_accordion(null,menu_lat::anagrafiche); // laterale    
	  $retegas->java_scripts_header[] = java_superfish(); 	  
 
 
	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  
      
      for($s=0;$s<50;$s++){
        $colo[$s]=random_color();          
      }
      
      $sql_gas = "SELECT
                        retegas_ordini.id_ordini,
                        retegas_ordini.id_utente,
                        maaking_users.id_gas,
                        retegas_ordini.data_chiusura
                        FROM
                        retegas_ordini
                        Inner Join retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini
                        Inner Join maaking_users ON retegas_ordini.id_utente = maaking_users.userid
                        WHERE
                        retegas_listini.id_ditte =  '$id_ditta'";
      $res_gas = $db->sql_query($sql_gas);
      while ($row = mysql_fetch_array($res_gas)){

                    $val_ord = valore_totale_ordine_qarr($row["id_ordini"]); 
                    $data_activity_2 .="{   x: Date.UTC(".conv_date_to_javascript(($row["data_chiusura"]))."), 
                                            y: ".round($val_ord,0).", 
                                            color: '#".$colo[$row["id_gas"]]."'
                                            }, 
                                          ";
      }
              $data_activity_2 = rtrim($data_activity_2,",");
              
              $serie .= "{
                        type: 'column',
                        data: [".$data_activity_2."],
                        pointInterval: 7 * 24 * 3600 * 1000                         
                        },";

              $serie = rtrim($serie,",");
              
              
                    $retegas->java_scripts_bottom_body[] = "
          <script type=\"text/javascript\"> 
 
 jQuery(function() {
 
    window.chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container_chart'
        },
  
         
        title: {
            text: 'Ordini ditta ".ditta_nome($id_ditta)."'
        },

        
        xAxis: {
         type: 'datetime'
       },
        yAxis: {
            min: 0,
            title: {
                text: 'Euro'
            }
        },
        plotOptions: {
         column: {
            pointWidth: 10,
            pointPadding: 0.2,
            borderWidth: 0
         }
       },
        series: [".$serie."]
    });
});
   
          </script> 
      ";
          // orizzontale  
      
	  
	  
			// qui ci va la pagina vera e proria
	   
	  $retegas->content  =  '<div style="position:relative; width: 100%; display:table;">
                             <div id="container_chart" style="width: 100%; height: 36em;   display:table-cell"></div>
                             </div>'; 
		
	  $html = $retegas->sito_render();
	  echo $html;
	  exit;

	  unset($retegas);	  
  
?>