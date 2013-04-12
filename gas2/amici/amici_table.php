<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("amici_renderer.php");


// controlla se l'user ha effettuato il login oppure no
if (is_logged_in($user)){

    // estraggo dal cookie le informazioni su chi ? che sta vedendo la pagina
    $cookie_read     =explode("|", base64_decode($user));
    $id_user  =  $cookie_read[0];
    $permission = $cookie_read[6]; 
                               
    // e poi scopro di che gas ? l'user
    $gas = id_gas_user($id_user);
    
}else{
    pussa_via();
    exit;     
}    

    
    if(!($permission & perm::puo_avere_amici)){
        go("sommario",_USER_ID,"Non puoi avere amici");            
    }
      
    if($do=="do_mod"){
       if(is_array($box_amici_on)){
              $sql = 'UPDATE `retegas_amici` SET `status` = \'0\' WHERE id_referente='._USER_ID.';';
              $res = $db->sql_query($sql);
              unset($sql);     
          foreach($box_amici_on as $id_amico){
              //echo "id amico : $id_amico<br>";
              $sql = 'UPDATE `retegas_amici` SET `status` = \'1\' WHERE `retegas_amici`.`id_amici` = '.(int)$id_amico.' LIMIT 1;';
              $res = $db->sql_query($sql);
              unset($sql);
          }
       }
       $msg="Modifiche salvate";
       $id_ordine = CAST_TO_INT($id_ordine);
       
       //go_back arriva dalla pagina di partecipazione massiva
       if($go_back=="to_mass"){
           go("ordine_partecipa_massivo",_USER_ID,null,"?id_ordine=$id_ordine");
       }
                 
    }
   
    // ISTANZIO un nuovo oggetto "retegas"

    $retegas = new sito; 
    $ref_table ="output";

      
      // SE E' LA VISUALIZZAZIONE NORMALE;
      
      // assegno la posizione che sar? indicata nella barra info 
    $retegas->posizione = "Miei amici";
      
          // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard
    
    $retegas->sezioni = $retegas->html_standard;
      
    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito[] = menu_nuovo_amico($id_user);
    $retegas->menu_sito[] = menu_visualizza_amici($id_user);
    
 
    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;
     
      
    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");  // editor di testo
    
    $retegas->has_bookmark="SI"; 
         
      // creo  gli scripts per la gestione dei menu
      
      $retegas->java_scripts_header[] = java_accordion(null, menu_lat::user); // laterale    
      $retegas->java_scripts_header[] = java_superfish();
      $retegas->java_scripts_bottom_body[] = java_tablesorter("output");       
      
      
              $retegas->java_scripts_bottom_body[] = "<script src=\"js/protoclass.js\"></script>
                                                 <script src=\"js/box2d.js\"></script>
                                                 
                                                 <script>
                                                 function reset() {

            var i;

            if ( bodies ) {

                for ( i = 0; i < bodies.length; i++ ) {

                    var body = bodies[ i ]
                    canvas.removeChild( body.GetUserData().element );
                    world.DestroyBody( body );
                    body = null;
                }
            }

            // color theme
            theme = themes[ Math.random() * themes.length >> 0 ];
            //document.body.style[ 'backgroundColor' ] = theme[ 0 ];

            bodies = [];
            elements = [];

            
            for( i = 0; i < 100; i++ ) {
                createBall();
            }
            

        }
                                                 </script>
                                                 
        <script src=\"js/Main.js\"></script>";
      
      // assegno l'eventuale messaggio da proporre
      if(isset($msg)){ 
        $retegas->messaggio=$msg;
      }
      

      
      $retegas->content  = '
      <style>
            body {
                overflow: hidden;
                background-color: #ffffff;
            }
      </style>
      <div id="canvas"></div>'.tutti_gli_amici($id_user, $ref_table,$id_ordine,$go_back);
      
        
      $html = $retegas->sito_render();
      echo $html;
      exit;

      unset($retegas); 