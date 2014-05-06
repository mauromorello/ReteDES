<?php         


 
// immette i file che contengono il motore del programma
include_once ("rend.php");
include_once ("retegas.class.php");


    // ISTANZIO un nuovo oggetto "retegas"
    // Prender� come variabile globale $user, nel caso di user loggato
    // allora visualizza la barra info ed il menu verticale,
    // nel caso di user non loggato visualizza la pagina con "benvenuto" e
    //nel men� verticale i campi per il login
    $retegas = new sito;

if($q=="do_login"){
    switch (do_login($username,$password,$remember)) {

    case 1:
        //LOGIN OK
        unset($q);
        go("sommario");
        break;
    case 2:
        $msg = _EMPTY_UNAME_OR_PASSWORD;
        unset($q);
        unset($username);
        unset($password);
        break;
    case 3:
        $msg = _UNAME_OR_PWD_NOT_RECOGNIZED.
               '<br>
               <br>
               <a class="awesome red medium" href="'.$RG_addr["user_forgotten_pwd"].'">Password dimenticata ?</a><br>
               <a class="awesome red medium" href="'.$RG_addr["user_forgotten_usn"].'">Username dimenticato ?</a><br>';
               break;
        unset($q);
        unset($username);
        unset($password);
        Logout($user);
        break;
    case 4:
        $msg = _NOT_YET_ACTIVED;
        unset($q);
        unset($username);
        unset($password);
        break;
    case 5:
        $extra = read_option_text($id_user,"_NOTE_SUSPENDED");
        $msg = "Utente momentaneamente sospeso:<br>$extra";
        unset($q);
        unset($username);
        unset($password);
        break;         
    case 6:
        $msg = "Account disattivato.";
        unset($q);
        unset($username);
        unset($password);
        break;              
    default:
        pussa_via();
        exit;    
    }
    
}

   
      

// controlla se l'user ha effettuato il login oppure no
if (_USER_LOGGED_IN){

	//Per mantenere la vecchia compatibilit�
	$id_user  = _USER_ID;
    $gas = _USER_ID_GAS;
    
    //CONTROLLO MOBILE
    $detect = new Mobile_Detect();
    if($nomobile==2){write_option_text(_USER_ID,"_USER_OPT_FORZA_DESKTOP","SI");sleep(1);}
    
    
    if(read_option_text(_USER_ID,"_USER_OPT_FORZA_DESKTOP")<>"SI"){
        if ( $detect->isMobile() ) {
            log_me(0,_USER_ID,"LOG","MOB","Mobile Detect",0,$detect->getUserAgent());
            go("sommario_mobile");
        }
    }
    //CONTROLLO SE E' STATO TOTTLAGTO UN BOOKMARK
    if(isset($page_title) AND isset($page_url)){
        
        if(check_option_exist(_USER_ID,"BOOK_".urldecode($page_title))){
            delete_option_text(_USER_ID,"BOOK_".urldecode($page_title));
        }else{
            write_option_text(_USER_ID,"BOOK_".urldecode($page_title),urldecode($page_url));
        }
    }	
	
    // QUA METTO I MENU PRIVATI
    $mio_menu[]='<li><a class="medium silver awesome" href="'.$RG_addr["pag_users_form_widgets"].'">Elementi visualizzati</a></li>
                  
                 <li><a class="medium silver awesome" href="'.$RG_addr["user_option_sito"].'">Opzioni</a></li>
                 <li><a class="medium red awesome" href="'.$RG_addr["gas_form_geogas"].'">I Gas</a></li>
                 <!--<li><a class="medium green awesome" href="'.$RG_addr["des_geo_ultimi_ordini_all"].'">Ultimi acquisti</a></li>-->
                 <li><a class="medium blue awesome" href="'.$RG_addr["des_soldi_giornaliero"].'">Quanto si spende</a></li>
                 <li><a class="medium green awesome" href="'.$RG_addr["gas_non_siamo_soli"].'">Non siamo soli !!</a></li>
    ';
    
    //CONTROLLO I BOOKMARK DELL'UTENTE
    $sql_book = "SELECT * FROM retegas_options WHERE id_user='"._USER_ID."' AND chiave LIKE 'BOOK_%';";
    $res_book = $db->sql_query($sql_book);
    if($db->sql_numrows($res_book)>0){
        while($row_book = mysql_fetch_assoc($res_book)) {
             if (strpos($row_book["valore_text"],"ord")>0){
                 $tooltip = rg_tooltip("Ordine n.");
             }else{
                 $tooltip ="";
             }
            
             $mio_menu[]='<li><a class="medium beige awesome" '.$tooltip.' href="'.$row_book["valore_text"].'">'.substr($row_book["chiave"],5).'</a></li>';
        }    
    }
    
    
	

    
    
   $contenuto =  '<br>
                <style>
                .column { width: 49%; float:left; padding-bottom: 100px;}
                .ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; height: 6em !important; margin-bottom:1em !important; }
                .ui-sortable-placeholder * { visibility: hidden; }
                </style>';
   
   $elementi_colonna_1 = read_option_integer($id_user,"WG_C");
   
   $wi = read_option_text($id_user,"WGO");
   $WIDGETS = unserialize(base64_decode($wi));
   $total_w = count($WIDGETS);
   (int)$has_widgets = strlen($wi);
   
   //echo $has_widgets; 
   
   
   //$contenuto .= alert_feedback();                 
   $ordini_senza_feedback = alert_feedback_num();
   if($ordini_senza_feedback>0){
        if($ordini_senza_feedback>1){$pre="Ci sono $ordini_senza_feedback ordini ";}else{$pre="C'� un ordine ";} 
        $contenuto .="<h4>$pre senza feedback <a class=\"awesome large red\" href=\"".$RG_addr["bacheca_alert_ordini"]."\">VAI ALLA PAGINA</a></h4>";    
   }
   
   $contenuto .='<div class="column" id="col_1">';
   
   
   // se non ha widgets preimpostati decido io quali mettergli
   if($has_widgets==0){
        
        $contenuto .= rgw_retegas_comunica_new_user($retegas,$id_user);
        $contenuto .= rgw_widget_render($retegas,7,$id_user,$gas);
        $contenuto .= rgw_widget_render($retegas,9,$id_user,$gas);
        $contenuto .= rgw_widget_render($retegas,1,$id_user,$gas);
        $contenuto .= rgw_widget_render($retegas,2,$id_user,$gas);   
   }
   
   

   
   //Creo la prima colonna di widgets
   for($i=0;$i<$elementi_colonna_1;$i++){
       $contenuto .= rgw_widget_render($retegas,$WIDGETS[$i],$id_user,$gas);
   }
   $contenuto .='</div>
                 <div class="column" id="col_2">';
   
   
   //se non ha widgets nella seconda colonna ci metto quelli che voglio io              
   if($has_widgets==0){
        $contenuto .= rgw_widget_render($retegas,3,$id_user,$gas);
   }              
   
   
   //Disegno la seconda colonna              
   for($i=($elementi_colonna_1);$i<=$total_w;$i++){
        $contenuto .= rgw_widget_render($retegas,$WIDGETS[$i],$id_user,$gas);
   }              
   $contenuto .='</div>
                 ';

}else{      // se l'user non ha effettuato il login

    go("start",null,null,"?nomobile=$nomobile");
    die();
	unset($user);
}
	 
 
	 
	// assegno la posizione che sar� indicata nella barra info 
	$retegas->posizione = " Home page";
	  
	// Dico a retegas come sar� composta la pagina, cio� da che sezioni � composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale � pronto ma � vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = $mio_menu;
 
	// dico a retegas quali sono i fogli di stile che dovr� usare
	// uso quelli standar per la maggior parte delle occasioni
	$retegas->css = array_merge($retegas->css, $retegas->css_standard);
	  
	  
	// dico a retegas quali file esterni dovr� caricare
	$retegas->java_headers = array_merge(array("rg"), $retegas->java_headers);
									 
	//DISQUS
    $retegas->disqus_id="MAIN";
    $retegas->disqus_title="Parliamo di ReteDes.it !"; 
	 
	 
	 
	  
	  // creo  gli scripts per la gestione dei menu
	  $retegas->java_scripts_header[]=java_accordion(); // laterale      
	  
    
      //$retegas->java_scripts_bottom_body[]= java_auto_close_accordion(5000);
      $retegas->java_scripts_bottom_body[]='<script>
       
      
                                             $(function() {
                                                $("#icon_rg").hide();
                                                $( "#col_1,#col_2" ).sortable({
                                                    connectWith: ".column",
                                                    distance: 15,
                                                    handle : ".widget_handler",
                                                    cancel: ".not_sortable",
                                                    placeholder: "ui-sortable-placeholder",
                                                    update : function () { 
                                                                          var order_1 = $("#col_1").sortable("serialize");
                                                                          var order_2 = $("#col_2").sortable("serialize");
                                                                          var c1 = $("#col_1 .rg_widget").size();
                                                                              $("#info").load("'.$RG_addr["ajax_widget_order"].'?"+order_1+"&"+order_2+"&c1="+c1);
                                                                              $("#icon_rg").show();
                                                                              $("#icon_rg").fadeOut(1000);
                                                                         } 
                                                
                                                });
                                                $( ".column" ).disableSelection();
                                                
                                            }); 
                                            </script>';                         

	  // assegno l'eventuale messaggio da proporre
	    $retegas->messaggio = $msg;
      if(isset($q)){ 
		$retegas->messaggio .=choose_msg($q);
	  }else{
        $retegas->messaggio .= read_option_text(_USER_ID,"MSG");
                               delete_option_text(_USER_ID,"MSG");
      }
	  
		  // qui ci va la pagina vera e proria  
	  $retegas->content = '<div id="info" class="ui-state-hightlight ui-corner-all"></div>'.$contenuto;
	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  
      
      echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);