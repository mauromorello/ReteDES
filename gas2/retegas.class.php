<?php
class sito {
private $sezioni_sito = array("html_header" =>      "",
							  "pdf_header" =>       "",
							  "excel_header" =>     "",
							  "csv_header" =>       "",
							  "barra_info" =>       "",
							  "testata" =>          "",
							  "menu" =>             "",
							  "contenuti" =>        "",
							  "laterale" =>         "",
                              "footer" =>         "");		
public $html_standard =  array("html_header",         // Header per HTML, in caso di PDF o altro può non essere presente
								  "testata",           // IL logo e l'intestazione
								  "laterale",          // dentro a "laterale" c'è in automatico il menù con tutte le voci. NON CONFIGURABILE
								  "menu",        // Chi sei e dove sei
								  "contenuti",
								  "barra_info",
                                  //"footer"
                                  );                              
public $css_standard = array("principale",     // layout del sito
							"jquery_ui",      // effetti tipo tendine e riquadri
							"awesome",        // pulsanti dei menu
							"superfish",      // menu orizzontale
							"tabelle");        // tabelle
                            
public $css_standard_mini = array("rg");                            
                            
public $css = array();
public $css_header = array();
public $css_body = array();
public $sezioni =array();
public $java_headers = array();
public $java_scripts_header = array();
public $java_scripts_top_body = array();
public $java_scripts_bottom_body = array();
public $header_sito;
public $menu_sito =array();
public $messaggio ="";
public $user ="";
public $has_bookmark=false;

//IDs
public $id_ordine=null;


public $posizione="";
public $help_page="";
public $v_menu_position =0;
public $converti_accenti = "";
public $body_tags ="";
public $content ="Nessun contenuto da visualizzare";
private $html_header;
private $rg_id_user;
private $rg_fullname_user;
private $rg_gas;
private $rg_gas_name;
private function draw_html_header(){
   Global $RG_addr,$site_path; 
    
    //<!DOCTYPE HTML>
    //<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">
  // $h="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">   
if (_SITE_USE_CACHE_MANIFEST){
    $site_cache_manifest = "manifest=\""._SITE_CACHE_MANIFEST."\"";
}

// <meta http-equiv=\"Expires\" content=\"Fri, Jan 01 1900 00:00:00 GMT\">
//<meta http-equiv=\"Pragma\" content=\"no-cache\">
//<meta http-equiv=\"Cache-Control\" content=\"no-cache\">
//


//<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"> 
  
$h="
<!DOCTYPE html>  
<html $site_cache_manifest>
<head>
<title>ReteDes.it (Rete dei Distretti di economia Solidale)</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
<meta http-equiv=\"Expires\" content=\"Fri, Jan 01 1900 00:00:00 GMT\">
<meta http-equiv=\"Pragma\" content=\"no-cache\">
<meta http-equiv=\"Cache-Control\" content=\"no-cache\">
<meta http-equiv=\"Lang\" content=\"it\">
<meta name=\"author\" content=\"Mimmoz01\">
<meta http-equiv=\"Reply-to\" content=\""._SITE_MAIL_LOG."\">
<meta name=\"generator\" content=\"Notepad\">
<meta name=\"description\" content=\"ReteDES.it, rete di gruppi di acquisto e distretti di economia solidale per gestire ordini e collaborazioni.\">
<meta name=\"keywords\" content=\"Gruppi acquisto solidale, Distretti economia solidale, Reti economia solidale, retegas, retedes, rete gas, rete des, solidal\">
<meta name=\"creation-date\" content=\"".date("m/d/y")."\">
<meta name=\"revisit-after\" content=\"15 days\">
<meta name=\"title\" content=\"ReteDes.it (Rete dei distretti di economia solidale)\">
<meta http-equiv=\"X-UA-Compatible\" content=\"chrome=1\">
";    
//<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
//   "http://www.w3.org/TR/html4/loose.dtd">    
return $h;    
}
private function draw_html_testata(){
	global $db;
	global $RG_addr;
    //global $user_options;
	$res = $db->sql_query("SELECT * FROM maaking_users WHERE isactive=1;");
	$quanti = $db->sql_numrows($res);
	$online = crea_numero_user_attivi_totali(2);
    $user_options = null;
    
    if(_USER_LOGGED_IN){
        $trgn = totale_retegas_netto();
        if($trgn>499999){
            $trgn="<span style=\"font-size:2em;color:#FF0000\">$trgn</span>";
        }
        
        $totale_retegas = '<br><span class="small_link">'._HE_E_SINORA.' </span>'.$trgn.'<span class="small_link"> Euro circa</span>';
       
    }
    
if(__BIG_ALERT<>"OFF"){
    $big_alert .= __BIG_ALERT;
}

if(_GAS_SITE_LOGO<>""){
    $gas_site_logo = '<img align="left" src="'._GAS_SITE_LOGO.'" border="0" width="100" height="75" alt="Logo GAS">';
}


$des_site_logo = '<img align="left" src="'._DES_SITE_LOGO.'" border="0" width="75" height="75" alt="Logo DES">';


//CANCELLARE
$logo_gas =' <div style=" margin:0;padding:0;float:left;" id="logo_gas">
                <a href="'.$RG_addr["sommario"].'">
                    <img align="left" src="'.$RG_addr["img_piemonte_cuore_s"].'" border="0" width="75" height="75" alt="ReteDes.it">
                </a>
             </div>';
//-----------------------

             
$logo_des =' <div style=" margin:0;padding:0;float:left;" id="logo_des">
                <a href="'.$RG_addr["sommario"].'">
                    '.$des_site_logo.'
                </a>
             </div>';              
 

$scritta .= '   <div style="display:block;float:left;margin-top:10px">
                    <div style="margin:0;padding:0;float:left;vertical-align:bottom;font-size:1.2em;font-family:helvetica">'._USER_DES_NAME.'</div><br>  
                    <div style="margin:0;padding:0;float:left;vertical-align:bottom;font-size:1em;font-family:helvetica">'.gas_nome(_USER_ID_GAS).'</div>
                </div>';
                
$logo_dx_retedes .= '<img '.rg_tooltip("Questa è una bozza di un possibile logo per ReteDes.it. Rappresenta un omino (quello giallo sotto in basso) che porta della merce (il cerchio in mezzo) ad un gruppo di altri omini (quelli sopra colorati), che la ricevono.
                                         Se ti viene un'idea migliore (e gratuita) sei caldamente invitato a proporla.").' align="left" src="'.$RG_addr["img_logo_retedes"].'" border="0" width="20" height="20" alt="Logo DES">';                
             
//<div id="logo_retedes" style="display:block;float:right">'.$logo_dx_retedes.'</div>
$boxino_destro .= '	<div id="boxino">
                      
			          <div style="display:block;float:left;">
                      <span style="font-size:1.2em; font-family:helvetica; line-height:30px;bottom: 0;">'._SITE_NAME.'  </span> 
			          <div id="logo_retedes" style="display:block;float:right">'.$logo_dx_retedes.'</div><br>
                      <span class="small_link">'._HE_AD_OGGI_SIAMO.' </span>'.$quanti.'<span class="small_link"> '._HE_UTENTI_DI_CUI.' </span> '.$online.'<span class="small_link"> Online</span>
		              '.$totale_retegas.'
                      </div>
                      </div>
                    ';

         
            
            
    if(_USER_OPT_NO_SITE_HEADER =="SI"){
        return "";
    }else{
        return  $big_alert. 
                $logo_des.
               // $logo_gas.
                $scritta.
                $boxino_destro.
                '<div style="clear:both;"></div>';     
    }
}
private function draw_html_barra_info(){
global $RG_addr;

//$user_options = leggi_opzioni_sito_utente($this->rg_id_user);

if(_USER_USA_CASSA){
    $credito_effettivo = _nf(cassa_saldo_utente_totale(_USER_ID));
    $credito = "<span class=\"small_link \">Credito residuo: </span><div id=\"credito_info\" style=\"display:inline-block; font-weight:bold\">$credito_effettivo Eu.</div>";
}


if(_USER_OPT_NO_SITE_HEADER=="SI"){
    $toggle_header ="<span style=\"font-size:1.1em\"><strong>"._SITE_NAME."</strong> - </span>";
}else{
    $toggle_header ="<a class=\"small awesome silver destra\"  
                    ".rg_tooltip("Salimi")."
                    onclick=\"
                    $('#header').animate({'height': 'toggle'}, { duration: 1000 }); 
                    return false;
                    \"><span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span></a>";    
}


    
    $bookmark = "<a class=\"small awesome silver destra\"  
                    ".rg_tooltip("Bookmarkami")."
                    ><span class=\"ui-icon ui-star\"></span></a>";


$help_page = " help_page_holder ";

if(!empty($this->rg_id_user)){

    

$h .="<div id=\"small-panel\" style=\"clear:both\">";
$h.= "              
                    <a class=\"small awesome silver\"  ".rg_tooltip("Home")." href=\"".$RG_addr["sommario"]."\"><span class=\"ui-icon ui-icon-home\"></span></a>
                    <a class=\"small awesome silver destra\"  ".rg_tooltip("Escimi",true)." href=\"".$RG_addr["user_logout"]."\"><span class=\"ui-icon ui-icon-extlink\"></span></a>
                    
                    
                    ".$help_page."
                    ".$bookmark."
                    ".$toggle_header."
                    ".$chat_status."
                    
                    <span id=\"icon_rg\" class=\"ui-icon ui-icon-circle-check small awesome destra\" style=\"margin-top:2px;margin-bottom:-2px;display:none;\"></span>
                    "."
                    <div id=\"info_container\" style=\"display:inline-block;margin:0;padding:0;overflow:hidden\"> 
                    <span class=\"small_link \">"._BI_UTENTE.": </span>".$this->rg_fullname_user.", ".$this->rg_gas_name.";  
                    <span class=\"small_link \">"._BI_POSIZIONE.": </span>Pagina Senza Titolo;  
                    ".$credito."
                    </div>
                    <div style=\"clear:both\"> </div>
                    ";
$h.="</div>";
$h2.='<div class="ui-state-error ui-corner-all padding_6px" style="margin-top:10px"><b>ATTENZIONE :</b> In data 16/12/2010 è stata redatta una nuova versione del dlsclaimer di questo sito. Siete tutti invitati a leggerla attentamente, e 
a mandarmi una segnalazione nel caso non siate disposti ad accettarla, in modo da poter rimuovere il vostro account.<br>
La potete trovare su <a href="http://disclaimer.retegas.info" >http://disclaimer.retegas.info</a>.</div>';
}else{
$h .='<!--[if IE]>
<br>
<![endif]-->';
$h .="<div id=\"small-panel\" class=\"ui-widget-content  ui-corner-all\" style=\"clear:both\">
	 ";
$h.= "<div style=\"font-size:1.3em; text-align:left;\"><a class=\"small awesome silver\" href=\"".$RG_addr["sommario"]."\" style=\"margin-right:1em;\"><span class=\"ui-icon ui-icon-home\"></span></a>Benvenuti nel sito dei GAS dell' Alto Piemonte.</div>
					<div style=\"clear:both\"> </div>
";
$h.="</div>";	
}
return $h;    
}
private function draw_html_barra_info_v2($params=null){
global $RG_addr;

//$user_options = leggi_opzioni_sito_utente($this->rg_id_user);

if(_USER_USA_CASSA){
    $credito_effettivo = _nf(cassa_saldo_utente_totale(_USER_ID));
    $credito = "<span class=\"small_link \">Credito residuo: </span><div id=\"credito_info\" style=\"display:inline-block; font-weight:bold\">$credito_effettivo Eu.</div>";
}


if(_USER_OPT_NO_SITE_HEADER=="SI"){
    $toggle_header ="<span style=\"font-size:1.1em\"><strong>"._SITE_NAME."</strong> - </span>";
}else{
    $toggle_header ="<a class=\"small awesome silver destra\"  
                    ".rg_tooltip("Salimi")."
                    onclick=\"
                    $('#header').animate({'height': 'toggle'}, { duration: 1000 }); 
                    return false;
                    \"><span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span></a>";    
}

if($this->has_bookmark){
    
    if(check_option_exist(_USER_ID,"BOOK_".$params["page_title"])){
     $color = "red";
     $cosa_fare = "togliere questo";   
    }else{
     $color = "silver";
     $cosa_fare = "Aggiungere un";      
    }
    $bookmark = "<a class=\"small awesome $color destra\"  
                    ".rg_tooltip("Clicca per $cosa_fare collegamento rapido nella Home page")."
                    href=\"".$RG_addr["sommario"]."?page_title=".urlencode($params["page_title"])."&page_url=".urlencode(curPageURL())."\"
                    ><span class=\"ui-icon ui-icon-heart\"></span></a>";
}


if(_USER_LOGGED_IN){

    
    //SE l'opzione è settata
    if(_SITE_SHOW_USERID){
        $show_id="<b>ID: "._USER_ID."</b>";
    }else{
        $show_id="";
    }
    

$h .="<div id=\"small-panel\" style=\"clear:both\">";
$h.= "              
                    <a class=\"small awesome silver\"  ".rg_tooltip("Home")." href=\"".$RG_addr["sommario"]."\"><span class=\"ui-icon ui-icon-home\"></span></a>
                    <a class=\"small awesome silver destra\"  ".rg_tooltip("Escimi",true)." href=\"".$RG_addr["user_logout"]."\"><span class=\"ui-icon ui-icon-extlink\"></span></a>
                    ".$bookmark."
                    ".$toggle_header."
                    <span id=\"icon_rg\" class=\"ui-icon ui-icon-circle-check small awesome destra\" style=\"margin-top:-3px;margin-bottom:0;display:none;\"></span>
                    "."
                    <div id=\"info_container\" style=\"display:inline-block;margin:0;padding:0;overflow:hidden\"> 
                    <span class=\"small_link \">Utente: </span> ".$show_id." "._USER_FULLNAME." 
                    <span class=\"small_link \">Posizione: </span>".$params["page_title"].";  
                    ".$credito."
                    </div>
                    <div style=\"clear:both\"> </div>
                    ";
$h.="</div>";
$h2.='<div class="ui-state-error ui-corner-all padding_6px" style="margin-top:10px"><b>ATTENZIONE :</b> In data 16/12/2010 è stata redatta una nuova versione del dlsclaimer di questo sito. Siete tutti invitati a leggerla attentamente, e 
a mandarmi una segnalazione nel caso non siate disposti ad accettarla, in modo da poter rimuovere il vostro account.<br>
La potete trovare su <a href="http://disclaimer.retegas.info" >http://disclaimer.retegas.info</a>.</div>';

}else{
$h .='<!--[if IE]>
<br>
<![endif]-->';
$h .="<div id=\"small-panel\" class=\"ui-widget-content  ui-corner-all\" style=\"clear:both\">
     ";
$h.= "<div style=\"font-size:1.3em; text-align:left;\"><a class=\"small awesome silver\" href=\"".$RG_addr["sommario"]."\" style=\"margin-right:1em;\"><span class=\"ui-icon ui-icon-home\"></span></a>Benvenuti nella rete dei DES.</div>
                    <div style=\"clear:both\"> </div>
";
$h.="</div>";    
}
return $h;    
}

private function draw_html_first_menu(){
global $id_user;
	$h_menu .='<li><a class="medium silver awesome"><b>Sito</b></a>';     // 1
		$h_menu .='<ul>';                                                     //2
			$h_menu .='<li><a class="medium silver awesome">Utente</a>';
				$h_menu .='<ul>';
					$h_menu .='<li><a class="medium silver awesome">Miei Dati</a></li>';
					$h_menu .='<li><a class="medium silver awesome">Miei Amici</a></li>';
					$h_menu .='<li><a class="medium silver awesome">Cambia Password</a></li>';
					$h_menu .='<li><a class="medium silver awesome">Log Out</a></li>';
				$h_menu .='</ul>';
			$h_menu .='</li>';
		//$h_menu .='</ul>'; 	
	//$h_menu .='</li>';         
		$h_menu .='<li><a class="medium silver awesome">Anagrafiche</a>';
			$h_menu .='<ul>';
				$h_menu .='<li><a class="medium silver awesome">Tipologie</a></li>';
				$h_menu .='<li><a class="medium silver awesome">Ditte</a></li>';
				$h_menu .='<li><a class="medium silver awesome">Miei listini</a></li>';
			$h_menu .='</ul>';
		$h_menu .='</li>';    
		$h_menu .='<li><a class="medium silver awesome">ReteGas.AP</a>'; //3
			$h_menu .='<ul>';  //4
				$h_menu .='<li><a class="medium silver awesome">Bacheca</a></li>';
				$h_menu .='<li><a class="medium silver awesome">Associati</a></li>';
				$h_menu .='<li><a class="medium silver awesome">Mio Gas</a></li>';
			$h_menu .='</ul>';  //4
		$h_menu .='</li>';    //3
		$h_menu .='<li><a class="medium silver awesome">Ordini</a>';    //3
			$h_menu .='<ul>';    //4
				$h_menu .='<li><a class="medium silver awesome">Panoramica</a></li>';
				$h_menu .='<li><a class="medium silver awesome">Aperti</a></li>';
				$h_menu .='<li><a class="medium silver awesome">Chiusi</a></li>';
			$h_menu .='</ul>';   //4
		$h_menu .='</li>';      //3
		$h_menu .='<li><a class="medium silver awesome"><b>Real time:</b><br>A,b,c,d</a></li>';
	$h_menu .='</ul>';          //2
$h_menu .='</li>';             //1
return $a;    
}
private function draw_html_laterale(){
//echo "Root_dir :".$ROOT_DIR."<br>";
//echo "Rooot :"._ROOOT_."<br>";

//<a href=\"".$RG_addr["pag_users_form_password"]."\">Cambia password</a><br>   

global $RG_addr,$db;

    if(!is_empty($this->rg_id_user)){
        
		    if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
		        $amministra =" <div class=\"menu_lat_divider ui-corner-top\">AMMINISTRA</div>
                                <div class=\"menu_lat_container ui-corner-bottom\">
                                    <a href=\"".$RG_addr["amministra"]."\">Amministra</a>
                                </div>";    
		    }
            if(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini){
                $extra ="<div class=\"menu_lat_divider ui-corner-top\">AMMINISTRA</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                    <a href=\"".$RG_addr["extra_dashboard"]."\">Dashboard</a><br>
                                    </div>";    
            }
            
            if(_GAS_USA_CASSA){
                $cassa ="<div class=\"menu_lat_divider ui-corner-top\">CASSA</div>
                            <div class=\"menu_lat_container ui-corner-bottom\">
                            <a href=\"".$RG_addr["cassa_gas_panel"]."\">Cassa</a>
                         </div>";    
                $cassa_personale = "<div class=\"menu_lat_divider ui-corner-top\">CASSA</div>
                                        <div class=\"menu_lat_container ui-corner-bottom\">
                                            <a href=\"".$RG_addr["movimenti_cassa_users"]."\">Mia Cassa</a>
                                        </div>";
            }
            
            if(_USER_PERMISSIONS & perm::puo_vedere_retegas){
                $amministra_des ="<div class=\"menu_lat_divider ui-corner-top\">AMMINISTRA</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                    <a href=\"".$RG_addr["des_option_sito"]."\">Amministra DES</a><br>
                                    </div>";    
            }
            
            //-----------------------------------BIRRA
            //Anche se è presente a 0 l'utente è risparmiato
            
            if(_USER_DONATION>0){
               $birra = ""; 
            }else{
               $birra = rg_birra(_USER_ID); 
            }
            //----------------------------------------
            
            
            $menu_lat_user ="<h3><a href=\"#\" class=\"medium nav awesome\" style=\"margin:6px\">"._USER_FULLNAME."</a></h3>
                            <div>
                             $amministra                             
                             $cassa_personale
                             <div class=\"menu_lat_divider ui-corner-top\">GESTIONE</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                    <a href=\"".$RG_addr["user_option_sito"]."\">Opzioni sito</a><br>
                                    <a href=\"".$RG_addr["pag_users_theme_select"]."\">Temi</a><br>
                                    <a href=\"".$RG_addr["pag_users_form_mia"]."\">I miei dati</a><br>
                             </div>
                             <div class=\"menu_lat_divider ui-corner-top\">AMICI</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                        <a href=\"".$RG_addr["pag_amici_table"]."\">I miei amici</a><br>
                                    </div>
                            <div class=\"menu_lat_divider ui-corner-top\">ALTRO</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                        <a href=\"".$RG_addr["user_logout"]."\">Log OUT</a>
                                    </div>                      
                            
                            </div>";
            
            $menu_lat_gas ="<h3><a href=\"#\" class=\"medium nav awesome\" style=\"margin:6px\">".gas_nome(_USER_ID_GAS)."</a></h3>
                            <div>
                                    $cassa
                                    <div class=\"menu_lat_divider ui-corner-top\">GAS</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                        <a href=\"".$RG_addr["gas_form"]."\">Il mio GAS</a><br>
                                        <a href=\"".$RG_addr["gas_users"]."\">Utenti</a><br>
                                    </div>    
                            </div>";
            $menu_lat_bacheca ="<h3><a href=\"#\" class=\"medium nav awesome\" style=\"margin:6px\">Bacheca</a></h3>
                            <div>
                                    <div class=\"menu_lat_divider ui-corner-top\">MIEI MESSAGGI</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                        <a href=\"".$RG_addr["bacheca_user_table"]."\">Commenti fornitori</a>
                                    </div>    
                            </div>";
            
            $menu_lat_des ="<h3><a href=\"#\" class=\"medium nav awesome\" style=\"margin:6px\">"._USER_DES_NAME."</a></h3>
                            <div>
                                    $amministra_des
                                    
                                    <div class=\"menu_lat_divider ui-corner-top\">MAPPE</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                        <a href=\"".$RG_addr["des_geo_dimensioni_gas"]."\">Dimensioni GAS</a><br>
                                        <a href=\"".$RG_addr["des_geo_ultimi_ordini"]."\" >Ultimi ordini</a><br>
                                        <a href=\"".$RG_addr["des_geo_valori_ditte"]."\" >Soldi a Ditte</a><br>
                                    </div>
                                    <div class=\"menu_lat_divider ui-corner-top\">GRAFICI</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                        <a href=\"".$RG_addr["grafico_global_mon_all"]."\">Spesa attuale</a><br>
                                        <a href=\"".$RG_addr["des_dimensione_gas"]."\">Struttura GAS</a><br>
                                        <a href=\"".$RG_addr["des_quanti_ordini_gas"]."\">Ordini gestiti</a><br>
                                        <a href=\"".$RG_addr["des_quanti_gestiscono"]."\">Utenti/Gestori</a><br>
                                        <a href=\"".$RG_addr["retegas_perc_ut"]."\">Utilizzo sito</a><br>
                                    </div> 
                                    <div class=\"menu_lat_divider ui-corner-top\">TABULATI</div>                                    
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                        <a href=\"".$RG_addr["gas_table"]."\">Gas di questo DES</a><br>
                                    </div>    
                            </div>";
                            
            $menu_lat_anag ="<h3><a href=\"#\" class=\"medium nav awesome\" style=\"margin:6px\">"._ML_ANAGRAFICHE."</a></h3>
                            <div>
                                    <div class=\"menu_lat_divider ui-corner-top\">MAPPE</div>
                                        <div class=\"menu_lat_container ui-corner-bottom\">
                                            <a href=\"".$RG_addr["ditte_geo"]."\">Ditte (Dett)</a><br>
                                            <a href=\"".$RG_addr["ditte_geo_cluster"]."\">Ditte (Raggr.)</a><br>
                                        </div>
                                    
                                    <div class=\"menu_lat_divider ui-corner-top\">TABELLE</div>
                                        <div class=\"menu_lat_container ui-corner-bottom\">
                                            <a href=\"".$RG_addr["ditte_table_3"]."\">Ditte</a><br>
                                            <form action=\"".$RG_addr["ditte_table_3"]."\" method=\"POST\">
                                            <input type=\"text\" name=\"search\" size=\"5\">
                                                <input type=\"submit\" value=\"GO!\">
                                            </form>
                                        </div>
                                    <div class=\"menu_lat_divider ui-corner-top\">LISTINI</div>
                                        <div class=\"menu_lat_container ui-corner-bottom\">
                                        <a href=\"".$RG_addr["miei_listini"]."\">"._ML_MIEI_LISTINI."</a><br>    
                                    </div>
                                    <div class=\"menu_lat_divider ui-corner-top\">TIPOLOGIE</div>
                                        <div class=\"menu_lat_container ui-corner-bottom\">        
                                            <a href=\"".$RG_addr["tipologie"]."\">"._ML_TIPOLOGIE."</a><br> 
                                    </div>        
                                            
                            </div>";
                            
            $menu_lat_ordini ="<h3><a href=\"#\" class=\"medium nav awesome\" style=\"margin:6px\">"._ML_ORDINI."</a></h3>
                            <div>
                                    <div class=\"menu_lat_divider ui-corner-top\"></div>
                                        <div class=\"menu_lat_container ui-corner-bottom\">
                                            $extra
                                                
                                            <div class=\"menu_lat_divider ui-corner-top\">OPERA:</div>
                                            <div class=\"menu_lat_container ui-corner-bottom\">
                                                <a href=\"".$RG_addr["nuovo_ordine_simple"]."\">Nuovo ordine</a><br>
                                            </div>
                                            
                                            <div class=\"menu_lat_divider ui-corner-top\">VEDI:</div>
                                            <div class=\"menu_lat_container ui-corner-bottom\">
                                                <a href=\"".$RG_addr["panoramica"]."\">"._ML_PANORAMICA."</a><br>
                                                <a href=\"".$RG_addr["ordini_aperti"]."\">"._ML_APERTI."</a><br>
                                                <a href=\"".$RG_addr["ordini_chiusi"]."\">"._ML_CHIUSI."</a><br>
                                                <a href=\"".$RG_addr["storici_miei_ordini"]."\">"._ML_STORICI."</a><br>                                            
                                            </div>
                                        </div>
                                    <br>     
                                    <div class=\"menu_lat_divider ui-corner-top\" ".rg_tooltip("Inserire ID ordine per aprire direttamente la sua scheda").">Ricerca veloce</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                        <form action=\"".$RG_addr["ordini_form_new"]."\" method=\"POST\">
                                            <input type=\"text\" name=\"id_ordine\" size=\"3\">
                                                <input type=\"submit\" value=\"GO!\">
                                        </form>
                                    </div>            
                            </div>";                
            
            $menu_lat_help = "<h3><a href=\"#\" class=\"medium nav awesome\" style=\"margin:6px\">"._ML_AIUTO."</a></h3>
                            <div>
                                    <a href=\"".$RG_addr["wiki"]."\">"._ML_ISTRUZIONI."</a><br>
                                    <a href=\"".$RG_addr["disclaimer"]."\">"._ML_DISCLAIMER."</a><br>
                            </div>";
            
            
            /// IL MENU PARTE DA QUA
            
            $h .= $birra;
		    $h .= "<div id=\"accordion\">";
			
            $ml = read_option_text(_USER_ID,"MNL");
            $MENU_LAT = unserialize(base64_decode($ml));
            $total_mnl = count($MENU_LAT);
            (int)$has_menu_lat = strlen($ml);
            //print_r($MENU_LAT);
            
            if($has_menu_lat==0){
                   $h .= "<div id=\"section_1\">".$menu_lat_des."</div>";
                   $h .= "<div id=\"section_2\">".$menu_lat_gas."</div>";
                   $h .= "<div id=\"section_3\">".$menu_lat_user."</div>";
                   $h .= "<div id=\"section_4\">".$menu_lat_ordini."</div>";
                   $h .= "<div id=\"section_5\">".$menu_lat_anag."</div>";
                   $h .= "<div id=\"section_6\">".$menu_lat_help."</div>";
                   $h .= "<div id=\"section_7\">".$menu_lat_bacheca."</div>";   
            }else{
          
                for($i=0;$i<7;$i++){
                    switch ($MENU_LAT[$i]){
                        case 1:
                             $h .= "<div id=\"section_1\">".$menu_lat_des."</div>";
                        break;
                        
                        case 2:
                             $h .= "<div id=\"section_2\">".$menu_lat_gas."</div>";
                        break;
                        
                        case 3:
                             $h .= "<div id=\"section_3\">".$menu_lat_user."</div>";
                        break;
                        
                        case 4:
                             $h .= "<div id=\"section_4\">".$menu_lat_ordini."</div>"; 
                        break;
                        
                        case 5:
                             $h .= "<div id=\"section_5\">".$menu_lat_anag."</div>";
                        break;
                        
                        case 6:
                             $h .= "<div id=\"section_6\">".$menu_lat_help."</div>";
                        break;
                        
                        case 7:
                             $h .= "<div id=\"section_7\">".$menu_lat_bacheca."</div>";
                        break;
                        
                    }
                }
            } // SE USER HA L'ORDINAMENTO LATERALE 
            
            
            
            		    
					    
                 
              
			$h .= "</div>";
            
            //AGGIUNTA SPONSOR COSEINUTILI
            $h .= "<div id=\"coseinutili\" style=\"margin-top:1em;margin-left:9px\">";
            $h .= "<a href=\"".$RG_addr["coseinutili"]."\">";
            $h .= "<img SRC=\"".$RG_addr["logo_coseinutili"]."\" ".rg_tooltip("Cose(in)utili, un sito che permette di gestire lo scambio, il baratto e la banca del tempo. Clicca per avere maggiori informazioni.")." width=112 height=29 >";
            $h .= "</a>";
            $h .= "</div>";
            
            

		    if(!is_chrome()){
		     $h .="       
				    <div class=\"ui-state-error \" style=\"margin:.5em; padding:10px; font-size: 76%;\">
				    <a href=\"http://www.google.com/chrome/index.html?hl=it\" target=\"_blank\">
				    <img src=\"".$RG_addr["img_chrome_logo"]."\" BORDER=\"0\" height=\"30\" width=\"30\" align=\"right\" alt=\"Chrome_Logo\" >
				    Sito ottimizzato per Google Chrome.
				    </a>
				    </div> ";
				    }
            
            
            }else{
                //SE UTENTE NON E' LOGGATO
                $h.="USER NON LOGGATO"; 
            }               
            
return $h;             
}

private function draw_html_messaggio($msg){
$h    .=   "<div id=\"dialog-message\" title=\""._SITE_NAME."\">
		    <p>$msg</p>
            </div>";			
return $h;               
}

private function draw_html_footer($extra_msg = null){

$h  = "<div id=\"pageFooterOuter\">
       prova di footer  
       </div>";
       
return $h;
        
}
public function initialize_user($user){
	  $this->user = $user;
	  $cookie_read = explode("|", base64_decode($this->user));
	  $this->rg_id_user = $cookie_read[0];
	  $this->rg_gas = id_gas_user($this->rg_id_user);
	  $this->rg_gas_name = gas_nome($this->rg_gas);
	  $this->rg_fullname_user = fullname_from_id($this->rg_id_user);
	  $this->rg_user_level = user_level($this->rg_id_user);    
}
public function __construct() {
	  global $user;
	  global $RG_addr, $class_debug;
      
      $class_debug->debug_msg[]= "----- START RETEGAS CLASS CONSTRUCT ------->";
      
      
	  if(isset($user)){
		  $this->initialize_user($user);    
	  }
      //SELEZIONE LINGUAGGIO
      //select_language($this->rg_id_user);
      
      
	  $this->stili_sito = array(
                           "rg" =>     "<link type=\"text/css\" href=\"".$RG_addr["css_rg"]."\" rel=\"Stylesheet\">\n
                                        <link type=\"text/css\" href=\"".$RG_addr["css_qtip"]."\" rel=\"Stylesheet\">\n",
                           "principale" =>     "<link type=\"text/css\" href=\"".$RG_addr["css_layer_sito"]."\" rel=\"Stylesheet\">\n",
						   "jquery_ui" =>           "<link type=\"text/css\" href=\"".$RG_addr["css_jquery_ui"]."\" rel=\"Stylesheet\">\n",
						   "widgets_ui" =>          "<link type=\"text/css\" href=\"".$RG_addr["css_widgets_ui"]."\" rel=\"Stylesheet\">\n",
                           "tabelle" =>             "<link type=\"text/css\" href=\"".$RG_addr["css_tabelle"]."\" rel=\"Stylesheet\">\n",
						   //"tabelle_ie" =>          "<!--[if IE]><link rel=\"stylesheet\" href=\"".$RG_addr["css_tabelle_ie"]."\" type=\"text/css\"><![endif]-->\n",
						   "awesome" =>             "<link type=\"text/css\" href=\"".$RG_addr["css_awesome"]."\" rel=\"Stylesheet\">\n",
						   "superfish" =>           "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$RG_addr["css_superfish"]."\"  media=\"screen\">\n",
						   "datetimepicker" =>  "<style type=\"text/css\">\n
												 /* css for timepicker */\n
												.ui-timepicker-div .ui-widget-header{ margin-bottom: 8px; }\n
												.ui-timepicker-div dl{ text-align: left; }\n
												.ui-timepicker-div dl dt{ height: 25px; }\n
												.ui-timepicker-div dl dd{ margin: -25px 0 10px 65px; }\n
												.ui-timepicker-div td { font-size: 90%; }\n
												</style>\n");
	  $this->java_head_sito = array("jquery" =>         "<script type=\"text/javascript\" src=\"".$RG_addr["js_jquery"]."\"></script>\n",
							   "jquery_ui" =>       "<script type=\"text/javascript\" src=\"".$RG_addr["js_jquery_ui"]."\"></script>\n",
                               "sortable" =>        "<script type=\"text/javascript\" src=\"".$RG_addr["js_jquery_sortable"]."\"></script>\n",
							   "accordion" =>       "<script type=\"text/javascript\" src=\"".$RG_addr["js_jquery_accordion"]."\"></script>\n",
							   "superfish" =>       "<script type=\"text/javascript\" src=\"".$RG_addr["js_superfish_hoverintent"]."\"></script>
													 <script type=\"text/javascript\" src=\"".$RG_addr["js_superfish_bigframe"]."\"></script>
													 <script type=\"text/javascript\" src=\"".$RG_addr["js_superfish"]."\"></script>\n",
							   //pacchetto cumulativo : Jquery , UI , Superfish, Qtip e tablesorter
							   "rg"        =>       "<script type=\"text/javascript\" src=\"".$RG_addr["js_rg"]."\"></script>\n",
                               "metadata"        => "<script type=\"text/javascript\" src=\"".$RG_addr["js_metadata"]."\"></script>\n",                           
							   "datepicker_loc" =>  "<script type=\"text/javascript\" src=\"".$RG_addr["js_datepicker_loc"]."\"></script>\n",
							   "sparkline" =>       "<script type=\"text/javascript\" src=\"".$RG_addr["js_sparkline"]."\"></script>\n",
							   "qtip" =>            "<script type=\"text/javascript\" src=\"".$RG_addr["js_qtip"]."\"></script>\n
                                                     <link type=\"text/css\" href=\"".$RG_addr["css_qtip"]."\" rel=\"Stylesheet\">\n",
							   "highcharts" =>      "<script type=\"text/javascript\" src=\"".$RG_addr["js_highcharts"]."\"></script>\n
                                                     <script type=\"text/javascript\" src=\"".$RG_addr["js_highcharts_more"]."\"></script>\n
                                                     <script type=\"text/javascript\" src=\"".$RG_addr["js_highcharts_export"]."\"></script>\n",
                               "highstocks" =>      "<script type=\"text/javascript\" src=\"".$RG_addr["js_highstocks"]."\"></script>\n
                                                     <script type=\"text/javascript\" src=\"".$RG_addr["js_highstocks_export"]."\"></script>\n",
                               "selectmenu" =>      "<script type=\"text/javascript\" src=\"".$RG_addr["js_selectmenu"]."\"></script>\n",
							   "ckeditor" =>        "<script type=\"text/javascript\" src=\"".$RG_addr["js_ckeditor"]."\"></script>\n",
							   "progression" =>     "<script type=\"text/javascript\" src=\"".$RG_addr["js_progression"]."\"></script>\n",
                               "jcalc" =>           "<script type=\"text/javascript\" src=\"".$RG_addr["js_jcalc"]."\"></script>\n",
							   "datetimepicker" =>  "<script type=\"text/javascript\" src=\"".$RG_addr["js_datetimepicker"]."\"></script>\n",
							   "table_sorter" =>    "<script type=\"text/javascript\" src=\"".$RG_addr["js_tablesorter"]."\"></script>");    
	  $this->converti_accenti="SI";
	  $this->menu_sito[]="";// $this->draw_html_first_menu();
	  $this->css[]="";
      $this->css_header[]="";
	  $this->css_body[]="";
      $this->java_scripts_bottom_body[] = java_qtip();
	  $this->sezioni_sito["html_header"] = $this->draw_html_header();
	  $this->sezioni_sito["testata"] = $this->draw_html_testata();
	  $this->sezioni_sito["barra_info"] = $this->draw_html_barra_info();
	  $this->sezioni_sito["laterale"] = $this->draw_html_laterale();
      $this->sezioni_sito["footer"] = $this->draw_html_footer();
   }
public function sito_render(){
global $class_debug,$RG_addr;


$class_debug->debug_msg[]= "----- START RETEGAS CLASS RENDER FULL ------->";


switch(_USER_OPT_THEME){
case "WINTER":
    $this->css_body[]=theme_winter_css();
    $this->java_scripts_top_body[] = theme_winter_javascript();
    break;
case "SPRING":
    $this->css_body[]=theme_spring_css();
    $this->java_scripts_top_body[] = theme_spring_javascript();
    break;
case "NIGHT":
    $this->css_body[]=theme_night_css();
    break;    
case "PAPER_1":
    $this->css_body[]=theme_paper_1_css();
    break;
case "RAIN":
    $this->css_body[]=theme_rain_css();    
    break;
case "SEPIA":
    $this->css_body[]=theme_sepia_css();
    break;
case "DRUNKEN":
    $this->css_body[]=theme_drunken_css(rand(1,2));
    break;
case "GONG":
    $this->java_scripts_bottom_body[] = theme_gong_javascript();
    break;              
}



//SISTEMO IL MESSAGGIO

if (!empty($this->messaggio)){
	$this->java_scripts_bottom_body[] = java_dialog("",$this->messaggio);
}



//SE IN SEZIONI E' PRESENTE HEADER ALLORA LO DISEGNO
if (in_array("html_header",$this->sezioni)){	
	$h = $this->sezioni_sito["html_header"];
}
//pulisce l'array da possibili doppioni CSS
$this->css = array_unique($this->css);
foreach ($this->css as $v) {
	$h .= $this->stili_sito[$v];
}
unset($v);
foreach ($this->css_header as $v) {
    $h .= $v;
}
unset($v);
foreach ($this->java_headers as $v) {
	$h .= $this->java_head_sito[$v];
}
unset ($v);
foreach ($this->java_scripts_header as $v) {
	$h .= $v;
}




$h .= '</head>';
$h .= '<body '.$this->body_tags.'>';


unset ($v);
foreach ($this->css_body as $v) {
		$h .= $v;
	}

if(!empty($this->messaggio)){
	$h .= $this->draw_html_messaggio($this->messaggio);
}
foreach ($this->java_scripts_top_body as $v) {
	$h .= $v;
}
unset ($v);
$h .='<div id="container">';
	if (in_array("testata",$this->sezioni)){
		$h .='<div id="header">';
			$h .= $this->sezioni_sito["testata"];
		$h .='</div>';
	}
	
    
    if (in_array("barra_info",$this->sezioni)){
		if(!empty($this->posizione)){
			$this->sezioni_sito["barra_info"] = str_replace("Pagina Senza Titolo",$this->posizione,$this->sezioni_sito["barra_info"]);
		}
        if(($this->help_page)==""){
            $help_page = "";    
        }else{
            $help_page = "<a class=\"small awesome yellow destra\"  title=\"Help relativo a questa pagina\"  target=\"blank\" href=\"".$this->help_page."\"><span class=\"ui-icon ui-icon-help\"></span></a>"; 
        }
        $this->sezioni_sito["barra_info"] = str_replace("help_page_holder",$help_page,$this->sezioni_sito["barra_info"]);
        
        
        
		$h .='<div id="barra_info" class="rg_widget" style="margin-bottom:0.25em">';
			//$h .= $this->sezioni_sito["barra_info"];
            
            $params = array("page_title"=>$this->posizione,
                            "id_ordine"=>$this->id_ordine);
            
            $h .= $this->draw_html_barra_info_v2($params);
		$h .='</div>';
	}
    
    
    
	if (in_array("laterale",$this->sezioni)){
		$h .= c1_open_div("navigation","class=\"rg_widget\"");
		$h .= $this->sezioni_sito["laterale"];
		$h .='</div>';
	}
    
    
    
    
        
	if (in_array("contenuti",$this->sezioni)){      
		$h .='<div id="content">';
		if (in_array("menu",$this->sezioni)){
			$h .='<div id="menu_hor" style="padding-bottom:2em;">
					<ul class="sf-menu">';
						if(is_array($this->menu_sito)){
                            foreach ($this->menu_sito as $v) {
							    $h .= $v;
                            }
                        }
			$h .='  </ul>
				  </div><br><hr id="linea_sotto_menu">';
		}
		$h .= $this->content; 
		$h .='</div>';
	}
    if (in_array("footer",$this->sezioni)){
        $h .= $this->sezioni_sito["footer"];
    }
    
        
$h .='</div>';
foreach ($this->java_scripts_bottom_body as $v) {
	$h .= $v;
}
unset ($v);
       
  if(_USER_PERMISSIONS & perm::puo_gestire_retegas){
        //SE E' ATTIVA L'OPZTION DEBUG ALLORA LO FACCIO VEDERE
		if(read_option_text(0,"DEBUG")=="ON"){		
                $tot_time = (array_sum(explode(' ', microtime())) - $class_debug->debug_start);    
				$class_debug->debug_msg[]='Tempo creazione pagina = '.$tot_time;
				$class_debug->debug_msg[]='Permessi utente : ';
                //$class_debug->debug_msg[]= var_dump($retegas);
				$debug_html .='
                      <div style="clear:both"></div>
                      <div class="debug ui-corner-all">
                      <h3>DIVERTITI !</h3>
					  '.$class_debug->render_debug().'
					  </div>';
        }
  }
// AGGIUNGO IL DEBUG
$h .= $debug_html;
$h .= '</body>';
$h .= '</html>';

//$h = utf8_encode($h);
    
$conversion_chars = array (    "à" => "&agrave;", 
							   "è" => "&egrave;", 
							   "é" => "&egrave;", 
							   "ì" => "&igrave;", 
							   "ò" => "&ograve;", 
							   "ù" => "&ugrave;"); 
$h = str_replace (array_keys ($conversion_chars), array_values($conversion_chars), $h);
return $h;
}
public function sito_render_basic_html(){
global $class_debug;
$h .= '<html>';
$h .= '<head>';
	unset ($v);
	foreach ($this->css_body as $v) {
		$h .= $v;
	}
$h .= '</head>';
$h .= '<body>';
	if (in_array("contenuti",$this->sezioni)){      
		$h .='<div id="content">';
		$h .= $this->content; 
		$h .='</div>';
	}    
$h .= '</body>';
$h .= '</html>';    

if($this->converti_accenti=="SI"){
    $h = sistema_accenti($h);
}




return $h;
  


}


  
public function sito_render_excel(){
global $class_debug;
$h .= '<html>';
$h .= '<body>';
	if (in_array("contenuti",$this->sezioni)){      
		$h .='<div id="content">';
		$h .= $this->content; 
		$h .='</div>';
	}    
$h .= '</body>';
$h .= '</html>';    
$h = sistema_accenti($h);
return $h;
} 
public function sito_render_word(){
global $class_debug;
$h .= '<html>';
$h .= '<head>';
$h .= '<style>';
$h .= include("word.css");
$h .= '</style>';    
$h .= '</head>';
$h .= '<body>';
	if (in_array("contenuti",$this->sezioni)){      
		$h .='<div id="content">';
		$h .= $this->content; 
		$h .='</div>';
	}    
$h .= '</body>';
$h .= '</html>';    
$h = sistema_accenti($h);
return $h;
}
public function sito_render_content(){
global $class_debug;

        $h .='<div style="padding-bottom:2em;">
                    <ul class="sf-menu">';
                        foreach ($this->menu_sito as $v) {
                            $h .= $v;
                        }
        $h .='  </ul>
                  </div><br><hr>';
        
        $h .= $this->content; 

    
        $h = sistema_accenti($h);

        return $h;
}
 
}
class rg_widget{

public $name = "";
public $title = "";
public $content = "";
public $footer = "";
public $can_toggle ="";
public $toggle_state ="";
public $use_handler="";
public $body_style="";
public $body_class="";
public $footer_class="";
public $header_class="";
public $rgw_class="";
public $params;
public $settings;
public $has_settings;
public $has_search;
public $search;
public $search_class;
public $settings_class;

public function __construct(){

$this->name = "widget_".random_string(5);
$this->title = "Retedes Widget";
$this->content = "Nessun contenuto da mostrare";
$this->footer = _SITE_NAME." - "._SITE_MAIL_LOG;
$this->can_toggle =true;
$this->toggle_state ="hide";
$this->use_handler = false;
$this->body_style="";
$this->rgw_class="rg_widget";
$this->header_class ="rg_w_header";
$this->body_class="rg_w_body";
$this->footer_class="rg_w_footer";
$this->settings_class="rg_w_settings";
$this->search="rg_w_search";
$this->params=array();
$this->settings="";
$this->has_settings=false;

$this->has_search=false;


    
}

public function rgw_render(){
global $RG_addr;



if(!$this->use_handler){$this->header_class = $this->header_class." widget_handler";}

//INTESTAZIONE    
$h = '
 <div class="'.$this->rgw_class.'" id="'.$this->name.'">
    <div class="'.$this->header_class.'">
    ';
//SE CI SONO BOTTONI 


//BOTTONE HANDLE
if($this->use_handler){    
$h.='<a href="#" class="awesome smallest silver destra widget_handler">
    <span class="ui-icon ui-icon-arrow-4-diag">    
    </span>
    </a>';

}



// BOTTONE TOGGLE   
if($this->can_toggle){    
$h.='<a href="#" class="awesome smallest silver destra"  
    onClick="$(\'#'.$this->name.'_body\').animate({\'height\': \'toggle\'}, { duration: 100 });return false;">
    <span class="ui-icon ui-icon-arrowthickstop-1-s">    
    </span>
    </a>';
    if($this->toggle_state=="hide"){
        $style_toggle = 'display:none;';
    }else{
        $style_toggle = 'display:block;'; 
    }
}

//BOTTONE SETTINGS
if($this->has_settings){   
$h.='<a href="#" class="awesome smallest silver destra"  
    onClick="$(\'#'.$this->name.'_settings\').animate({\'height\': \'toggle\'}, { duration: 100 });return false;">
    <span class="ui-icon ui-icon-wrench">    
    </span>
    </a>';
}

//BOTTONE SEARCH
if($this->has_search){   
$h.='<a href="" class="awesome smallest silver destra"  
    onClick="$(\'#'.$this->name.'_search\').animate({\'height\': \'toggle\'}, { duration: 100 });return false;">
    <span class="ui-icon ui-icon-search">    
    </span>
    </a>';
}



//Chiusura Header
$h.= $this->title.'         
     </div>';
     
//RICERCA
if($this->has_search){
$h.='<div class="'.$this->body_class.'" id="'.$this->name.'_search" style="display:none">
        <div class="'.$this->search_class.'">
            '.$this->search.'
        </div>
    </div>
    ';
}     

//IMPOSTAZIONI UTENTE
if($this->has_settings){
$h.='<div class="'.$this->body_class.'" id="'.$this->name.'_settings" style="display:none">
        <div class="'.$this->settings_class.'">
            '.$this->settings.'
        </div>
    </div>
    ';
}



//RESTO DEL WIDGET

$h.='<div class="'.$this->body_class.'" id="'.$this->name.'_body" style="'.$this->body_style.' '.$style_toggle.'">
    '.$this->content.'
    </div>
    <div class="'.$this->footer_class.'" id="'.$this->name.'_footer">
    '.$this->footer.'
    </div>
    
</div>
';    

return $h;    
}
   
}

class rg_simplest{

public $title = null;
public $tabella_da_ordinare = null;
public $voce_mv_attiva = null;
public $has_bookmark = false;
public $menu_orizzontale = array();
public $messaggio = null;
public $contenuto = null;
public $body_tags = null;
public $javascripts = array();
public $javascripts_header = array();

public function create_retegas(){
    
$rg = new sito;
$rg->posizione = $this->title;
$rg->sezioni = $rg->html_standard;
$rg->menu_sito = $this->menu_orizzontale;
$rg->css = $rg->css_standard;
$rg->has_bookmark = $this->has_bookmark;
$rg->java_headers = array("rg");
$rg->body_tags = $this->body_tags;


//HEADER  
$rg->java_scripts_header[] = java_accordion(null,$this->voce_mv_attiva); // laterale    
$rg->java_scripts_header[] = java_superfish();
$rg->java_scripts_header = array_merge($rg->java_scripts_header,$this->javascripts_header);

//BODY       
if(!is_empty($this->tabella_da_ordinare)){
 $rg->java_scripts_bottom_body[] = java_tablesorter($this->tabella_da_ordinare);   
}
$rg->java_scripts_bottom_body = array_merge($rg->java_scripts_bottom_body,$this->javascripts);
 
      // assegno l'eventuale messaggio da proporre
 if(isset($this->messaggio)){

    $rg->messaggio=$this->messaggio;
 }

 $rg->content  =  $this->contenuto;
 $html = $rg->sito_render();
 unset($retegas);    
 return $html;   
}

    
}

class rg_form{

 public $form_method = "POST";
 public $form_action = "";
 public $form_name = "retegas_form";
 public $form_class = "retegas_form ui-corner-all";
 public $form_style = "";
 public $item = array();
 public $form = array();
 
 public function create_form(){
     
     if(!empty($this->form_class)){
         $this->form_class = " CLASS=\"".$this->form_class."\""; 
     }
     
     if(!empty($this->form_method)){
         $this->form_method = " METHOD=\"".$this->form_method."\""; 
     }
     
     if(!empty($this->form_style)){
         $this->form_style = " STYLE=\"".$this->form_style."\""; 
     }
     
     if(!empty($this->form_action)){
         $this->form_action = " ACTION=\"".$this->form_action."\""; 
     }
     
     if(!empty($this->form_name)){
         $this->form_name = " NAME=\"".$this->form_name."\""; 
     }    
     
     $h = "<FORM ".$this->form_name.$this->form_method.$this->form_action.$this->form_class.$this->form_style.">";
     
     foreach ($this->item as $i) {
        $h .= $i;
     }
     
     
     
     $h .="</FORM>"; 
     
     
     
     return $h;
 }
    
}
class rg_form_text{

public $value="";
public $size ="";
public $name="";
public $name_for_label="";
public $help="";
public $label="";
public $number="";
public $class="";
public $style="";
public $id="";
public $autocomplete="";

public function create_form_text_item(){

if(empty($this->label)){
         $this->label = $this->name;
}    
    
if(!is_empty($this->value)){
         $this->value = " VALUE=\"".$this->value."\""; 
}
if(!empty($this->name)){
         $this->name_for_label = $this->name;
         $this->name = " NAME=\"".$this->name."\""; 
}
if(!empty($this->size)){
         $this->size = " SIZE=\"".$this->size."\""; 
}
if(!empty($this->class)){
         $this->class = " CLASS=\"".$this->class."\""; 
}
if(!empty($this->style)){
         $this->style = " STYLE=\"".$this->style."\""; 
}
if(!empty($this->id)){
         $this->id = " ID=\"".$this->id."\""; 
}    
if(!empty($this->autocomplete)){
         $this->autocomplete = " autocomplete=\"".$this->autocomplete."\""; 
}    
    
$h  = "<DIV>";
$h .= "<h4>".$this->number."</h4>";
$h .= "<LABEL FOR=\"".$this->name_for_label."\">".$this->label."</LABEL>";
$h .= "<INPUT TYPE=\"TEXT\" ".$this->id.$this->name.$this->value.$this->size.$this->class.$this->style.$this->autocomplete."></INPUT>";
$h .= "<H5 TITLE=\"".$this->help."\">Inf.</H5>"; 
$h .= "</DIV>"; 

return $h;    
}    
    
}
class rg_form_text_number{

public $value="";
public $size ="";
public $name="";
public $name_for_label="";
public $help="";
public $label="";
public $number="";
public $class="";
public $style="";
public $id="";
public $min="";
public $step="";

public function create_form_text_item(){

if(empty($this->label)){
         $this->label = $this->name;
}    
    
if(!is_empty($this->value)){
         $this->value = " VALUE=\"".$this->value."\""; 
}
if(!empty($this->name)){
         $this->name_for_label = $this->name;
         $this->name = " NAME=\"".$this->name."\""; 
}
if(!empty($this->size)){
         $this->size = " SIZE=\"".$this->size."\""; 
}
if(!is_empty($this->min)){
         $this->min = " MIN=\"".$this->min."\""; 
}
if(!is_empty($this->step)){
         $this->step = " STEP=\"".$this->step."\""; 
}
if(!empty($this->class)){
         $this->class = " CLASS=\"".$this->class."\""; 
}
if(!empty($this->style)){
         $this->style = " STYLE=\"".$this->style."\""; 
}
if(!empty($this->id)){
         $this->id = " ID=\"".$this->id."\""; 
}    
    
    
$h  = "<DIV>";
$h .= "<h4>".$this->number."</h4>";
$h .= "<LABEL FOR=\"".$this->name_for_label."\">".$this->label."</LABEL>";
$h .= "<INPUT TYPE=\"NUMBER\" ".$this->id.$this->name.$this->value.$this->size.$this->class.$this->style.$this->min.$this->step."></INPUT>";
$h .= "<H5 TITLE=\"".$this->help."\">Inf.</H5>"; 
$h .= "</DIV>"; 

return $h;    
}    
    
}
class rg_form_textarea{

public $value="";
public $rows ="";
public $cols ="";
public $name="";
public $name_for_label="";
public $help="";
public $label="";
public $number="";
public $class="";
public $style="";
public $id="";

public function create_form_textarea_item(){

if(empty($this->label)){
         $this->label = $this->name;
}    
    
if(!empty($this->name)){
         $this->name_for_label = $this->name;
         $this->name = " NAME=\"".$this->name."\""; 
}
if(!empty($this->rows)){
         $this->size = " ROWS=\"".$this->rows."\""; 
}
if(!empty($this->cols)){
         $this->cols = " COLS=\"".$this->cols."\""; 
}
if(!empty($this->class)){
         $this->class = " CLASS=\"".$this->class."\""; 
}
if(!empty($this->style)){
         $this->style = " STYLE=\"".$this->style."\""; 
}
if(!empty($this->id)){
         $this->id = " ID=\"".$this->id."\""; 
}    
    
    
$h  = "<DIV>";
$h .= "<h4>".$this->number."</h4>";
$h .= "<TEXTAREA ".$this->id.$this->name.$this->rows.$this->cols.$this->class.$this->style.">".$this->value."</TEXTAREA>";
$h .= "<LABEL FOR=\"".$this->name_for_label."\">".$this->label."</LABEL>";
$h .= "<H5 TITLE=\"".$this->help."\">Inf.</H5>"; 
$h .= "</DIV>"; 

return $h;    
}    
    
}
class rg_form_checkbox{

public $value="";
public $size ="";
public $name="";
public $name_for_label="";
public $help="";
public $label="";
public $number="";
public $state="";
public $class="";
public $style="";
public $id="";

public function create_form_checkbox_item(){

if(empty($this->label)){
         $this->label = $this->name;
}    
    
if(!is_empty($this->value)){
         $this->value = " VALUE=\"".$this->value."\""; 
}
if(!empty($this->name)){
         $this->name_for_label = $this->name;
         $this->name = " NAME=\"".$this->name."\""; 
}
if(!is_empty($this->state)){
    $this->state = " CHECKED ";
}else{
    $this->state = "";
}

if(!empty($this->size)){
         $this->size = " SIZE=\"".$this->size."\""; 
}
if(!empty($this->class)){
         $this->class = " CLASS=\"".$this->class."\""; 
}
if(!empty($this->style)){
         $this->style = " STYLE=\"".$this->style."\""; 
}
if(!empty($this->id)){
         $this->id = " ID=\"".$this->id."\""; 
}    
    
    
$h  = "<DIV>";
$h .= "<h4>".$this->number."</h4>";
$h .= "<LABEL FOR=\"".$this->name_for_label."\">".$this->label."</LABEL>";
$h .= "<INPUT TYPE=\"CHECKBOX\" ".$this->state.$this->id.$this->name.$this->value.$this->size.$this->class.$this->style."></INPUT>";
$h .= "<H5 TITLE=\"".$this->help."\">Inf.</H5>"; 
$h .= "</DIV>"; 

return $h;    
}    
    
}


class rg_form_select{

public $value="";
public $size ="";
public $name="";
public $name_for_label="";
public $help="";
public $label="";
public $number="";
public $class="";
public $style="";
public $id="";
public $options = array();

public function create_form_select_item(){

if(empty($this->label)){
         $this->label = $this->name;
}    
    
if(!empty($this->value)){
         $this->value = " VALUE=\"".$this->value."\""; 
}
if(!empty($this->name)){
         $this->name_for_label = $this->name;
         $this->name = " NAME=\"".$this->name."\""; 
}
if(!empty($this->size)){
         $this->size = " SIZE=\"".$this->size."\""; 
}
if(!empty($this->class)){
         $this->class = " CLASS=\"".$this->class."\""; 
}
if(!empty($this->style)){
         $this->style = " STYLE=\"".$this->style."\""; 
}
if(!empty($this->id)){
         $this->id = " ID=\"".$this->id."\""; 
}    
    
    
$h  = "<DIV>";
$h .= "<h4>".$this->number."</h4>";
$h .= "<LABEL FOR=\"".$this->name_for_label."\">".$this->label."</LABEL>";
$h .= "<SELECT  ".$this->id.$this->name.$this->size.$this->class.$this->style.">";
foreach ($this->options as $i) {
        $h .= $i;
}
$h .= "</SELECT>";
$h .= "<H5 TITLE=\"".$this->help."\">Inf.</H5>"; 
$h .= "</DIV>"; 

return $h;    
}    
public function create_option_item($label,$value){
    
        if($this->value==$value){
            $sel = "SELECTED";
        }
    return "<option value=\"$value\" $sel >$label</option>";    

}    
}
class rg_form_submit{

public $value="";
public $size ="";
public $name="";
public $name_for_label="";
public $help="";
public $label="";
public $number="";
public $class="";
public $style="";
public $id="";

public function create_form_submit_item(){

if(empty($this->label)){
         $this->label = $this->name;
}    
    
if(!empty($this->value)){
         $this->value = " VALUE=\"".$this->value."\""; 
}
if(!empty($this->name)){
         $this->name_for_label = $this->name;
         $this->name = " NAME=\"".$this->name."\""; 
}
if(!empty($this->size)){
         $this->size = " SIZE=\"".$this->size."\""; 
}
if(!empty($this->class)){
         $this->class = " CLASS=\"".$this->class."\""; 
}
if(!empty($this->style)){
         $this->style = " STYLE=\"".$this->style."\""; 
}
if(!empty($this->id)){
         $this->id = " ID=\"".$this->id."\""; 
}    
    
    
$h  = "<DIV>";
$h .= "<h4>".$this->number."</h4>";
$h .= "<LABEL FOR=\"".$this->name_for_label."\">".$this->label."</LABEL>";
$h .= "<INPUT TYPE=\"SUBMIT\" ".$this->id.$this->name.$this->value.$this->size.$this->class.$this->style."></INPUT>";
$h .= "<H5 TITLE=\"".$this->help."\">Inf.</H5>"; 
$h .= "</DIV>"; 

return $h;    
}    
    
}
class rg_form_hidden{

public $value="";
public $name="";
public $id="";

public function create_form_hidden_item(){

  
    
if(!is_empty($this->value)){
         $this->value = " VALUE=\"".$this->value."\""; 
}
if(!empty($this->name)){
         $this->name_for_label = $this->name;
         $this->name = " NAME=\"".$this->name."\""; 
}

if(!empty($this->id)){
         $this->id = " ID=\"".$this->id."\""; 
}    

$h .= "<INPUT TYPE=\"HIDDEN\" ".$this->id.$this->name.$this->value."></INPUT>";


return $h;    
}    
    
}

class ExportXLS {

    private $filename;    //Filename which the excel file will be returned as
    private $headerArray;    // Array which contains header information
    private $bodyArray;    // Array with the spreadsheet body
    private $rowNo = 0;    // Keep track of the row numbers


    #Class constructor
    function ExportXLS($filename) { 
        $this->filename = $filename;
    }


    /*
    -------------------------
    START OF PUBLIC FUNCTIONS
    -------------------------
    */

    public function addHeader($header) {
    #Accepts an array or var which gets added to the top of the spreadsheet as a header.

        if(is_array($header)) {
            $this->headerArray[] = $header;
        }
        else
        {
            $this->headerArray[][0] = $header;
        }
    }

    public function addRow($row) {
    #Accepts an array or var which gets added to the spreadsheet body

        if(is_array($row)) {
            #check for multi dim array
            if(is_array($row[0])) {
                foreach($row as $key=>$array) {
                    $this->bodyArray[] = $array;
                }
            }
            else
            {
                $this->bodyArray[] = $row;
            }            
        }
        else
        {
            $this->bodyArray[][0] = $row;
        }
    
    }
    
    public function returnSheet() {
    # returns the spreadsheet as a variable

        #build the xls
        return $this->buildXLS();
    }

    public function sendFile() {

        #build the xls
        $xls = $this->buildXLS();

        #send headers
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=".$this->filename);
        header("Content-Transfer-Encoding: binary ");

        echo $xls;

        exit;
    }


    /*
    --------------------------
    START OF PRIVATE FUNCTIONS
    --------------------------
    */

    private function buildXLS() {
    # build and return the xls 
    
        #Excel BOF
        $xls = pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);

        #build headers
        if(is_array($this->headerArray)) {
            $xls .= $this->build($this->headerArray);
        }

        #build body
        if(is_array($this->bodyArray)) {
            $xls .= $this->build($this->bodyArray);
        }

        $xls .= pack("ss", 0x0A, 0x00);

        return $xls;
    }

    private function build($array) {
    #build and return the headers 

        foreach($array as $key=>$row) {
            $colNo = 0;
            foreach($row as $key2=>$field) {
                if(is_numeric($field)) {
                    $build .= $this->numFormat($this->rowNo, $colNo, $field);
                }
                else
                {
                    $build .= $this->textFormat($this->rowNo, $colNo, $field);
                }

                $colNo++;
            }
            $this->rowNo++;
        }

        return $build;
    }

    private function textFormat($row, $col, $data) {
    # format and return the field as a header
        $data = utf8_decode($data);
        $length = strlen($data);
        $field = pack("ssssss", 0x204, 8 + $length, $row, $col, 0x0, $length);
        $field .= $data;

        return $field; 
    }
        

    private function numFormat($row, $col, $data) {
    # format and return the field as a header
            $field = pack("sssss", 0x203, 14, $row, $col, 0x0);
            $field .= pack("d", $data); 
        
        return $field; 
    }
}

class Date_Time_Converter{
    
    
    /*        PUBLIC VARIABLES        */
    
    
    public $date_time_stamp;        //the date to be calculated in timestamp format
    public $date_time;                //the date to be calculated. ex: 12/30/2008 17:40:00
    public $date_time_mask;            //the php date() style format that $date_time is in.  ex: m/d/Y H:i:s
    
    public $seconds;
    public $minutes;
    public $hours;    
    public $days;
    public $months;
    public $years;
    public $ampm;
    
    
    
    
    
    
    /*        CONSTRUCTOR and DESTRUCTOR */
    
    /** Constructor.  This is where you supply the date.  Accepts almost any format of
     *   date as long as you supply the correct mask.  DOES accept dates
      *     without leading zeros (n,j,g,G) as long as they aren't bunched together.  
    *   ie: ("1152008", "njY") wont work;   ("1/15/2008", "n/j/2008") will work. 
     *   Example: $obj = new Date_Time_Calc('12/30/2008 17:40:00', 'm/d/Y H:i:s');     */
    public function __construct($start_date_time, $mask) {
        $this->_default_date_time_units();                //set date&time units to default values
        $this->date_time = $start_date_time;
        $this->date_time_mask = $mask;
        
        //convert date to timestamp
        $this->date_time_stamp = $this->_date_to_timestamp($start_date_time, $mask);
    }
    
    
    public function __destruct() {
        unset($this->date_time_stamp);
        unset($this->date_time);
        unset($this->date_time_mask);
        unset($this->seconds);
        unset($this->minutes);
        unset($this->hours);
        unset($this->days);
        unset($this->months);
        unset($this->years);
        unset($this->ampm);    
    }
    
    
    
    
    
    
    
    /*        PRIVATE FUNCTIONS        */
    
    /** Private Function. Resets date and time unit variables to default
     */
    private function _default_date_time_units() {
        $this->seconds         = '00';
        $this->minutes        = '00';
        $this->hours        = '12';    
        $this->days            = '01';
        $this->months        = '01';
        $this->years        = date("Y");
        $this->ampm            = 'am';
    }
    
    
    /** Private Function.  Converts a textual month into a digit.  Accepts almost any
     *     textual format of a month including abbreviations. 
    *   Example: _month_num("jan"); //returns '1'   Example2: _month_num("january", true);  //returns '01'     
    */
    private function _month_num($themonth, $return_two_digit=false) {
    
        switch (strtolower($themonth)) {
            case 'jan':
            case 'jan.';
            case 'january':
                return ($return_two_digit == true ? '01': '1');
                break;
            case 'feb':
            case 'feb.':
            case 'february':
            case 'febuary':
                return ($return_two_digit == true ? '02': '2');
                break;
            case 'mar':
            case 'mar.':
            case 'march':
                return ($return_two_digit == true ? '03': '3');
                break;
            case 'apr':
            case 'apr.':
            case 'april':
                return ($return_two_digit == true ? '04': '4');
                break;
            case 'may':
            case 'may.':
                return ($return_two_digit == true ? '05': '5');
                break;
            case 'jun':
            case 'jun.':
            case 'june':
                return ($return_two_digit == true ? '06': '6');
                break;
            case 'jul':
            case 'jul.':
            case 'july':
                return ($return_two_digit == true ? '07': '7');
                break;
            case 'aug':
            case 'aug.':
            case 'august':
                return ($return_two_digit == true ? '08': '8');
                break;
            case 'sep':
            case 'sep.':
            case 'sept':
            case 'sept.':
            case 'september':
                return ($return_two_digit == true ? '09': '9');
                break;
            case 'oct':
            case 'oct.':
            case 'october':
                return '10';
                break;
            case 'nov':
            case 'nov.':
            case 'november':
                return '11';
                break;
            case 'dec':
            case 'dec.':
            case 'december':
                return '12';
                break;
            default:
                return false;
                break;
        }
    }
    
    
    
    
    
    
    
    
    
    
    /** Private Function. Converts a date into a timestamp.  Accepts almost any
     *     format of date as long as you supply the correct mask.  DOES accept dates
      *     without leading zeros (n,j,g,G) as long as they aren't bunched together.  
    *   ie: ("1152008", "njY") wont work;   ("1/15/2008", "n/j/2008") will work 
    */
    private function _date_to_timestamp($thedate, $mask) {
        
        $mask_orig = $mask;
        // define the valid values that we will use to check
        // value => length
        $all = array(
            
            //time
            's' => 'ss',        // Seconds, with leading zeros
            'i' => 'ii',        // Minutes with leading zeros
            'H' => 'HH',        // 24-hour format of an hour with leading zeros
            'h' => 'hh',        // 12-hour format of an hour with leading zeros
            'G' => 'GG',          // 24-hour format of an hour without leading zeros
            'g' => 'gg',          // 12-hour format of an hour without leading zeros
            'A' => 'AA',        // Uppercase Ante meridiem and Post meridiem
            'a' => 'aa',        // Lowercase Ante meridiem and Post meridiem
            
            //year
            'y' => 'yy',        // A full numeric representation of a year, 4 digits
            'Y' => 'YYYY',         // A two digit representation of a year
            
            //month
            'm' => 'mm',         // A numeric representation of a month with leading zeros.
            'M' => 'MMM',        // A textual representation of a month.  3 letters.  ex: Jan, Feb, Mar, Apr...
            'n' => 'nn',        // Numeric representation of a month, without leading zeros
            
            //days
            'd' => 'dd',        // Day of the month, 2 digits with leading zeros
            'j' => 'jj',        // Day of the month without leading zeros
            'S' => 'SS',        // English ordinal suffix for the day of the month, 2 characters (st, nd, rd, or th. works well with j)
            'D' => 'DDD'        // Textual representation of day of the week (Sun, Mon, Tue, Wed)
            
        );

        // this will give us a mask with full length fields
        $mask = str_replace(array_keys($all), $all, $mask);
        
        $vals = array();
        
        //loop through each character of $mask starting at the beginning
        for ($i=0; $i<strlen($mask_orig); $i++) {
            //get the current character
            $thischar = substr($mask_orig, $i, 1);
            
            //if the character is not in the $all array, skip it
            if (array_key_exists($thischar, $all)) {
                $type = $thischar;
                $chars = $all[$type];
                
                // get position of the current char
                if(($pos = strpos($mask, $chars)) === false)
                    continue;
    
                // find the value from $thedate
                $val = substr($thedate, $pos, strlen($chars));
                
                /*        START FIX FOR UNITS WITHOUT LEADING ZEROS        */
                if ($type == "n" || $type == "j" || $type == "g" || $type == "G") {
                    //if its not numeric, try a shorter digit
                    if (!is_numeric($val)) {
                        $val = substr($thedate, $pos, strlen($chars)-1);
                        $mask = str_replace($chars, $type, $mask);
                    } else {
                        //try numeric value checking
                        switch ($type) {
                            case "n":
                                if ($val > 12 || $val < 1) {  //month must be between 1-12
                                    $val = substr($thedate, $pos, strlen($chars)-1);
                                    $mask = str_replace($chars, $type, $mask);
                                }
                                break;
                            case "j":
                                if ($val > 31 || $val < 1) {  //day must be between 1-31
                                    $val = substr($thedate, $pos, strlen($chars)-1);
                                    $mask = str_replace($chars, $type, $mask);
                                }
                                break;
                            case "g":
                                if ($val > 12 || $val < 1) {  //day must be between 1-12
                                    $val = substr($thedate, $pos, strlen($chars)-1);
                                    $mask = str_replace($chars, $type, $mask);
                                }
                                break;
                            case "G":
                                if ($val > 24 || $val < 1) {  //day must be between 1-24
                                    $val = substr($thedate, $pos, strlen($chars)-1);
                                    $mask = str_replace($chars, $type, $mask);
                                }
                                break;                        
                        }
                    }
                }
                
                /*        END FIX FOR UNITS WITHOUT LEADING ZEROS        */
                
                //save this value
                $vals[$type] = $val;
            }
        }

        foreach($vals as $type => $val) {
            
            switch($type) {
                case 's' :
                    $this->seconds = $val;
                break;
                case 'i' :
                    $this->minutes = $val;
                break;
                case 'H':
                case 'h':
                    $this->hours = $val;
                break;
                case 'A':
                case 'a':
                    $this->ampm = $val;
                break;
                case 'y':
                    $this->years = '20'.$val;
                break;
                case 'Y':
                    $this->years = $val;
                break;
                case 'm':
                    $this->months = $val;
                break;
                case 'M':
                    $this->months = $this->_month_num($val, true);
                break;
                case 'd':
                    $this->days = $val;
                break;
                //ones without leading zeros:
                case 'n':
                    $this->months = $val;
                break;
                case 'j':
                    $this->days = $val;
                break;
                case 'g':
                    $this->hours = $val;
                break;
                case 'G':
                    $this->hours = $val;
                break;
            }
        }
        
        if (strtolower($this->ampm) == "pm") {$this->hours = $this->hours + 12;}            //if its pm, add 12 hours
        
        $make_stamp = mktime( (int)ltrim($this->hours, "0"), (int)ltrim($this->minutes, "0"), 
                              (int)ltrim($this->seconds, "0"), (int)ltrim($this->months, "0"), 
                              (int)ltrim($this->days, "0"), (int)ltrim($this->years, "0"));
        
        return $make_stamp;

}






    
    
    
    
    /**        PUBLIC FUNCTIONS            */
    
    
    
    
    
    /** Sets a new format/mask for the date using the php date() style formatting
     *     Example: $obj->convert("M j Y H:i:s A");
     */    
    public function convert($new_mask, $save=true) {
        $newdate = date($new_mask, $this->date_time_stamp);
        //if they want to save and apply this new mask to $this->date_time, save it
        if ($save == true) {
            $this->date_time_mask = $new_mask;
            $this->date_time = $newdate;
        }
        return $newdate;
    }
    
    
    
    
    
    
    /** Changes the date to a new one.
    *   Example: $obj->set_date_time('11/20/2005 07:40:00 AM', 'm/d/Y H:i:s A');
    */
    public function set_date_time($start_date_time, $mask) {
        $this->__construct($start_date_time, $mask);
    }
    
    
    
    

    
    
}