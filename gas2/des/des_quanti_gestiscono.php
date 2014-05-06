<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//
include_once ("des_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
	pussa_via();
	exit;     
}    
//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_vedere_retegas)){
     go("sommario",_USER_ID,"Non hai i permessi necessari (Rilasciati dal tuo DES) per vedere questa pagina");
}

	if(!is_empty($min_ord)){
        if(is_numeric($min_ord)){
            CAST_TO_INT($min_ord);
            if($min_ord<1){
                $min_ord=1;
            }
        }else{
            $min_ord=3;    
        }
    }else{
        $min_ord=3;
    }
	 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prenderà come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel men? verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sar? indicata nella barra info 
	$retegas->posizione = "Percentuali di gestori ordine";
	  
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
		  
  
	 	  
	   
	  $retegas->java_scripts_header[]=java_accordion("#accordion",menu_lat::des); // laterale    
	  $retegas->java_scripts_header[]=java_superfish();

      
      for($s=0;$s<50;$s++){
        $colo[$s]=random_color();          
      }
      
      $sql2 = "SELECT
            Count(maaking_users.userid) AS user_totali,
            retegas_gas.descrizione_gas,
            retegas_gas.id_gas
            FROM
            retegas_gas
            Inner Join maaking_users ON maaking_users.id_gas = retegas_gas.id_gas
            WHERE id_des = "._USER_ID_DES."
            GROUP BY
            retegas_gas.id_gas,
            retegas_gas.descrizione_gas";
      $res2 = $db->sql_query($sql2);
      //{ name: 'Firefox', y: 44.2, color: '#4572A7' },
      
      while ($row = mysql_fetch_array($res2)){

            $gas_names .= "'".$row["descrizione_gas"]."',";

            $user_ordinanti =n_gestori_ordini($row["id_gas"],$min_ord);             
            $ut_gest .= $user_ordinanti.",";
            $ut_altri .= (int)$row["user_totali"] - $user_ordinanti.","; 
          
      }
      $ut_gest = rtrim($ut_gest,",");
      $ut_altri = rtrim($ut_altri,",");
      
      $serie = '{name : \'Gestori\',
                        data:['.$ut_gest.']},
                  {name : \'Il resto\',
                        data:['.$ut_altri.']}';      
                        
      
      
      
      

      $retegas->java_scripts_bottom_body[] = "
          <script type=\"text/javascript\"> 
                          var chart;
                $(document).ready(function() {
                   chart = new Highcharts.Chart({
                      chart: {
                         renderTo: 'container_chart',
                         defaultSeriesType: 'column'
                      },
                      title: {
                         text: 'Percentuali Gestori almeno di $min_ord ordini'
                      },
                      xAxis: {
                         categories: [$gas_names]
                      },
                      yAxis: {
                         min: 0,
                         title: {
                            text: 'Totale Utenti Gas'
                         }
                      },
                      tooltip: {
                         formatter: function() {
                            return ''+
                                this.series.name +': '+ this.y +' ('+ Math.round(this.percentage) +'%)';
                         }
                      },
                      plotOptions: {
                         column: {
                            stacking: 'percent'
                         }
                      },
                           series: [$serie]
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
	  $retegas->content  =  '<div class="rg_widget rg_widget_helper"><form method="POST" action="">Minimo di ordini fatti :<input type="text" name="min_ord" size="3" value="'.$min_ord.'"><input type="submit" value="aggiorna"></form>
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