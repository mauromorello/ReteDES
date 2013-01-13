<?php   

$_FUNCTION_LOADER=array("mobile",
                        "ordini",
                        "users",
                        "ordini_valori",
                        "gas",
                        "listini",
                        "ditte",
                        "tipologie",
                        "geocoding",
                        "posta",
                        "swift");

include_once ("../rend.php");
include_once ("../jqm.class.php");


//Controllo su login
if(!_USER_LOGGED_IN){   
    go("sommario_mobile");       
}

if(isset($id_utente)){
    (int)$id_utente = mimmo_decode($id_utente);
}else{
    go("sommario_mobile"); 
}

if($do == "send"){
          $da_chi = fullname_from_id(_USER_ID);
          $mail_da_chi = id_user_mail(_USER_ID);
        
          $verso_chi = fullname_from_id($id_utente); 
          $mail_verso_chi = id_user_mail($id_utente);
          $textarea=sanitize($textarea);
          
            
          $soggetto = "["._SITE_NAME."] - da $da_chi - Comunicazione";
          $result = manda_mail($da_chi,$mail_da_chi,$verso_chi,$mail_verso_chi,$soggetto,$textarea);
          
            
          $msg="<h3>Mail inviata a $verso_chi<h3>";
          
          unset($do); 
    
}

      
                
//Nuovo oggetto Jquery MObile
$j = new jqm(load_jqm_param());

//-------------------------------------------------------PAG 1                                
//Nuova pagina con relativi parametri
$p = new jqm_page(load_page_param("ReteGas","scheda_utente"));

//Negli attributi assegno un ID
$p->jqm_footer_hide= true;

//Assegno la navbar
//Con solo un pulsate per tornare indietro
$n = new jqm_navbar(load_scheda_utente_navbar(null,$id_utente));
$n->jqm_navbar_set_item_attrib(0,"class=\"ui-btn-active ui-state-persist\"");

//$n->jqm_navbar_set_item_attrib(0,"class=\"ui-btn-active ui-state-persist\"");
$p->jqm_header_navbar=$n->jqm_render_navbar();
//----------------

$fullname = fullname_from_id($id_utente);
$telefono = telefono_from_id($id_utente);
$mail     = email_from_id($id_utente);
$gas_app  = gas_nome(id_gas_user($id_utente));



     $h_table .=  "
     $msg
     <ul data-role=\"listview\" data-inset=\"true\">
         <li data-role=\"list-divider\">Dati Pubblici</li>
         <li>Nome: $fullname</li>
         <li><a href=\"tel:$telefono\">$telefono</a></li>
         <li>$mail</li>
         <li>Gas: $gas_app</li>
         <li data-role=\"list-divider\">Messaggialo</li>
         <li>
         <form action=\"".$RG_addr["m_user_scheda"]."\" method=\"POST\" data-ajax=\"false\">
         <div data-role=\"fieldcontain\">
            <label for=\"textarea\">Messaggio:</label>
            <textarea name=\"textarea\" id=\"textarea\"></textarea>
            
         </div>
         <div data-role=\"fieldcontain\" class=\"ui-hide-label\">
            
            <input type=\"hidden\" name=\"do\" id=\"do\" value=\"send\"/>
            <input type=\"hidden\" name=\"id_utente\" id=\"id_utente\" value=\"".mimmo_encode($id_utente)."\"/>
            <input type=\"submit\" name=\"submit\" id=\"submit\" value=\"Invia\"/>
        </div>
         </form>
         </li>
         

 </ul>";





//---------------Assegno i contenuti

$p->jqm_page_content = $h_table;
//Creo la pagina
$j->jqm_pages[]=$p->jqm_render_page();
unset($p);

//-------------------------------------------------------PAG 1



//La visualizzo
echo $j->jqm_render();
unset($j);  
?>