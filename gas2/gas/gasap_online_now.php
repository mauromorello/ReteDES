<?php
session_start(); 
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
//
include_once ("gas_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

	// estraggo dal cookie le informazioni su chi ? che sta vedendo la pagina
	$cookie_read     =explode("|", base64_decode($user));
	$id_user  = $cookie_read[0];
	$my_user_level = user_level($id_user);
	
    
	// Costruisco i menu 
	$mio_menu = gas_menu_completo($user);
	
	if ($my_user_level==5){
	$mio_menu[] = gas_zeus($user);    
	}
	
	// scopro come si chiama
	$usr = fullname_from_id($id_user);
    $options = leggi_opzioni_sito_utente($id_user);
    

	// e poi scopro di che gas ? l'user
	$gas = id_gas_user($id_user);
	
}else{
	pussa_via();
	exit;     
}    

	
	 
	// ISTANZIO un nuovo oggetto "retegas"
	// Prender? come variabile globale $user, nel caso di user loggato
	// allora visualizza la barra info ed il menu verticale,
	// nel caso di user non loggato visualizza la pagina con "benvenuto" e
	//nel men? verticale i campi per il login
	$retegas = new sito; 
	 
	// assegno la posizione che sar? indicata nella barra info 
	$retegas->posizione = "Utenti Online";
	  
	// Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
	// Queste sono contenute in un array che ho chiamato HTML standard
	
	$retegas->sezioni = $retegas->html_standard;
	  
	// Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
	$retegas->menu_sito = $mio_menu;
 
	// dico a retegas quali sono i fogli di stile che dovr? usare
	// uso quelli standard per la maggior parte delle occasioni
	$retegas->css = $retegas->css_standard;
	  
	  
	// dico a retegas quali file esterni dovr? caricare
	$retegas->java_headers = array("rg");  // ordinatore di tabelle
		  
	  // creo  gli scripts per la gestione dei menu
	  // MAPPA
	
	 $gas_address = gas_address_from_id($gas);
	  
	 	  
	   
	  $retegas->java_scripts_header[]=java_accordion(null,menu_lat::gas); // laterale    
	  $retegas->java_scripts_header[]=java_superfish();
	  
      if($options & opti::visibile_a_tutti){
          $retegas->java_scripts_bottom_body[]='<script type="text/javascript">
          $("#submitmsg").click(function(){  
                var clientmsg = $("#usermsg").val();  
                $.post("post.php", {text: clientmsg});  
                $("#usermsg").attr("value", "");  
                return false;  
            });
          function loadLog(){

                 

             $.ajax({  
                url: "gasap_chat.php",  
                cache: false,  
                success: function(html){  
                    $("#chatbox").html(html); //Insert chat log into the #chatbox div
                    
                //Auto-scroll  
                //$("#chatbox").animate({ scrollTop: $("#chatbox").attr("scrollHeight") },2000); 
                $("#chatbox").attr("scrollTop", $("#chatbox").attr("scrollHeight"));      
                },  
            });  

          }
          setInterval (loadLog, 3000); 
          
          </script> 
          ';
      }
          // orizzontale                         

	  // assegno l'eventuale messaggio da proporre
	  if(isset($msg)){ 
		$retegas->messaggio=$msg;
	  }
	  
              //SEZIONE CONTEGGIO UTENTI ATTIVI
        $h3        .= '<div class="" style="margin-top:10px; padding:2px;font-size:.9em">
                            <b>Presenze GAS :<br></b>
                            '.crea_lista_gas_attivi(2).'
                            </div>';
        //SEZIONE CONTEGGIO UTENTI ATTIVI
        $h3        .= '<div class="" style="margin-top:10px; padding:2px;font-size:.9em">
                            <b>User OnLine (Tutta Retegas.AP):<br></b>
                            '.crea_lista_user_attivi_pubblica(2).'
                            </div>';
        //SEZIONE VISUALIZZAZIONE USER ONLINE

        //SEZIONE CONTEGGIO UTENTI ATTIVI PROPRIO GAS
        $h3        .= '<div class="" style="margin-top:10px; padding:2px;font-size:.9em">
                            <b>'.gas_nome($gas).'<br></b>
                            '.crea_lista_user_attivi_pubblica_gas(2,$gas).'
                            </div>';
                            
                            
       if($options & opti::visibile_a_tutti){
        $form ='                    <form name="message" action="">
            
                                        <input name="usermsg" type="text" id="usermsg" size="70" />
                                        <input class="awesome medium green destra" name="submitmsg" type="submit"  id="submitmsg" value="Invia" />  
                                    </form>';   
       }else{
        $h3='Per poter vedere questa pagina, gli utenti connessi, e partecipare alla chat, ? necessario abilitare l\'opzione <b> visibile a tutti </b>. (men? Profilo - I miei dati)';   
        $form = '';
       }
      
      
		  // qui ci va la pagina vera e proria  
	  $retegas->content  =  '<div class="ui-corner-all padding_6px">
                             
                             <table>
                             <tr>
                             <td width="30%" class="ui-widget-content ui-corner-all padding_6px"> 
                             <h3>Utenti Online</h3>
                             '.$h3.'
                             </td>
                             <td width="70%" class="ui-widget-content ui-corner-all padding_6px">
                                 <h3>Chat condivisa</h3>
                                 <div id="chatbox"  
                                                    style="  text-align:left;  
                                                            margin-right:10px;
                                                            margin-bottom:15px;  
                                                            padding:10px;  
                                                              
                                                            height:200px;    
                                                            overflow:auto;">
                                 '.$contents.'                           
                                 </div>  
                                 '.$form.'
                                 </div>
                             </td>                             
                             </tr>
                             </table>
                             </div>';

	  

	  
	  //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
	  $html = $retegas->sito_render();
	  // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
	  echo $html;
	  
	  
	  //distruggo retegas per recuperare risorse sul server
	  unset($retegas);	  
	  
	  
	  
?>