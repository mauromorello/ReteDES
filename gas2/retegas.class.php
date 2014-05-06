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
public $disqus_id = "";
public $disqus_title = "";
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
<meta charset='utf-8'>
<meta http-equiv=\"Content-Type\" content=\"text/html\">
<meta http-equiv=\"Expires\" content=\"Fri, Jan 01 1900 00:00:00 GMT\">
<meta http-equiv=\"Pragma\" content=\"no-cache\">
<meta http-equiv=\"Cache-Control\" content=\"no-cache\">
<meta http-equiv=\"Lang\" content=\"it\">
<meta name=\"author\" content=\"Mimmoz01\">
<meta http-equiv=\"Reply-to\" content=\""._SITE_MAIL_LOG."\">
<meta name=\"generator\" content=\"Notepad\">
<meta name=\"description\" content=\""._SITE_NAME.", rete di gruppi di acquisto e distretti di economia solidale per gestire ordini e collaborazioni.\">
<meta name=\"keywords\" content=\"Gruppi acquisto solidale, Distretti economia solidale, Reti economia solidale, retegas, retedes, rete gas, rete des, solidal\">
<meta name=\"creation-date\" content=\"".date("m/d/y")."\">
<meta name=\"revisit-after\" content=\"15 days\">
<meta name=\"title\" content=\"ReteDes.it (Rete dei distretti di economia solidale)\">
<meta http-equiv=\"X-UA-Compatible\" content=\"chrome=1\">
<link href='http://fonts.googleapis.com/css?family=Anaheim' rel='stylesheet' type='text/css'>
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

    $resg = $db->sql_query("SELECT * FROM retegas_gas;");
    $quanti_gas = $db->sql_numrows($resg);

    $resg = $db->sql_query("SELECT * FROM retegas_ordini;");
    $quanti_ordini = $db->sql_numrows($resg);

    if(_USER_LOGGED_IN){
        $trgn = totale_retegas_netto();
        if($trgn>999999){
            $trgn="<span style=\"font-size:2em;color:#FF0000\">$trgn</span>";
        }


        $totale_retegas = '<br><span class="small_link">abbiamo gestito </span>'.$quanti_ordini.'<span class="small_link"> ordini, togliendo alla grande distribuzione </span>'.$trgn.'<span class="small_link"> Euro circa</span>';

    }

if(__BIG_ALERT<>"OFF"){
    $big_alert .= __BIG_ALERT;
}

if(_GAS_SITE_LOGO<>""){
    $gas_site_logo = '<img align="left" src="'._GAS_SITE_LOGO.'" border="0" width="100" height="75" alt="Logo GAS">';
}


$des_site_logo = '<img align="left" src="'._DES_SITE_LOGO.'" style="margin:6px;" border="0" width="75" height="75" alt="Logo DES">';



$logo_des =' <div style=" margin:0;padding:0;float:left;" id="logo_des">
                <a href="'.$RG_addr["sommario"].'">
                    '.$des_site_logo.'
                </a>
             </div>';


$scritta .= '   <div style="display:block;float:left;margin-top:10px;font-family:\'Anaheim\', sans-serif;">
                    <div style="margin:0;padding:0;float:left;vertical-align:bottom;font-size:2em;">'._USER_DES_NAME.'</div><br>
                    <div style="margin:0;padding:0;float:left;vertical-align:bottom;font-size:1.5em;">'.gas_nome(_USER_ID_GAS).'</div><br>

                </div>';

$logo_dx_retedes .= '<img align="left" src="'.$RG_addr["img_logo_retedes"].'" border="0" width="20" height="20" alt="Logo DES">';

//<div id="logo_retedes" style="display:block;float:right">'.$logo_dx_retedes.'</div>
$boxino_destro .= '	<div id="boxino">

			          <div style="display:block;float:left;">
                      <span style="font-size:1.2em; font-family:helvetica; line-height:30px;bottom: 0;">'._SITE_NAME.'  </span>
			          <div id="logo_retedes" style="display:block;float:right">'.$logo_dx_retedes.'</div><br>
                      <span class="small_link">...ad oggi siamo </span>'.$quanti_gas.'<span class="small_link"> GAS, con </span>'.$quanti.'<span class="small_link"> famiglie, di cui </span> '.$online.'<span class="small_link"> <a title="'.crea_lista_gas_attivi(2).'">Online</a></span>
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
    if(read_option_gas_text_new(_USER_ID_GAS,"_GAS_CASSA_VISUALIZZAZIONE_SALDO")=="1"){

        $credito_effettivo = _nf(cassa_saldo_utente_totale(_USER_ID));
        $credito = "<span class=\"small_link \">Credito residuo: </span><div id=\"credito_info\" style=\"display:inline-block; font-weight:bold\">$credito_effettivo Eu.</div>";
    }else{
        $credito_effettivo = _nf(cassa_saldo_utente_totale(_USER_ID)+ abs( cassa_saldo_utente_non_confermato(_USER_ID)));
        $credito = "<span class=\"small_link \">Saldo effettivo: </span><div id=\"credito_info\" style=\"display:inline-block; font-weight:bold\">$credito_effettivo Eu.</div>";

    }
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
                    <a class=\"small awesome yellow destra\"  ".rg_tooltip("Prova la nuova versione di Retedes !!",true)." href=\"http://retegas.altervista.org/gas3/\"><span class=\"ui-icon ui-icon-extlink\" style=\"display:inline-block\"></span><span style=\"margin-top:-5px;\">V3 !</span></a>
                    ".$bookmark."
                    ".$toggle_header."
                    <span id=\"icon_rg\" class=\"ui-icon ui-icon-circle-check small awesome destra\" style=\"margin-top:-3px;margin-bottom:0;display:none;\"></span>
                    "."
                    <div id=\"info_container\" style=\"display:inline-block;margin:0;padding:0;overflow:hidden\">
                    <span class=\"small_link \">User: </span> ".$show_id." "._USER_FULLNAME."
                    <span class=\"small_link \">Posizione: </span>".$params["page_title"].";
                    ".$credito."
                    </div>
                    <div style=\"clear:both\"></div>
                    ";
$h.="</div>";
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
                                            <a href=\"".$RG_addr["cassa_suggerisci_movimento"]."\">Suggerisci Carico</a>
                                        </div>";
            }

            //questa pagina la può vedere solo il responsabile DES e io

            if((_USER_ID == db_val_q("id_des",_USER_ID_DES,"id_referente","retegas_des"))
                OR
                (_USER_PERMISSIONS & perm::puo_gestire_retegas)){

                $amministra_des ="<div class=\"menu_lat_divider ui-corner-top\">AMMINISTRA</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                    <a href=\"".$RG_addr["des_option_sito"]."\">Amministra DES</a><br>
                                    </div>";
            }



            //-----------------------------------BIRRINO

            //if(_USER_DONATION>0){
            //   $birra = "";
            //}else{
               $birra = rg_birra(_USER_ID);
            //}
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
                                    <div class=\"menu_lat_divider ui-corner-top\">IL MIO GAS</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                        <a href=\"".$RG_addr["gas_form"]."\">Scheda</a><br>
                                        <a href=\"".$RG_addr["gas_users"]."\">Utenti</a><br>
                                    </div>
                                    <div class=\"menu_lat_divider ui-corner-top\">GAS VICINI</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                        <a href=\"".$RG_addr["gas_form_geogas"]."\">di retedes</a><br>
                                        <a href=\"".$RG_addr["gas_non_siamo_soli"]."\">NON retedes</a><br>
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

            $menu_lat_coseinutili ="<h3><a href=\"#\" class=\"medium nav awesome yellow\" style=\"margin:6px\"><img SRC=\"".$RG_addr["logo_coseinutili"]."\" style=\"height:29px;width:112px;\" ".rg_tooltip("Cose(in)utili, un sito che permette di gestire lo scambio, il baratto e la banca del tempo. Clicca su 'cos'è ?' per avere maggiori informazioni.")."></a></h3>
                            <div>
                                    <div class=\"menu_lat_divider ui-corner-top\">INFO</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                        <a href=\"".$RG_addr["coseinutili"]."\">Cos'è ?</a>
                                    </div>

                                    <div class=\"menu_lat_divider ui-corner-top\">MAPPE</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                        <a href=\"".$RG_addr["ci_geo_oggetti"]."\">Oggetti vicini</a>
                                    </div>
                                    <div class=\"menu_lat_divider ui-corner-top\">GAS</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                        <a href=\"".$RG_addr["ci_gas_oggetti"]."\">Nel mio GAS</a>
                                    </div>

                                    <div class=\"menu_lat_divider ui-corner-top\" ".rg_tooltip("Cerca un oggetto tra quelli barattabili con cose(in)utili").">Cerca Oggetti</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                        <form action=\"".$RG_addr["ci_search_oggetti"]."\" method=\"POST\">
                                            <input type=\"text\" name=\"oggetto\" size=\"10\">
                                                <input type=\"submit\" value=\"GO!\">
                                        </form>
                                    </div>

                                    <div class=\"menu_lat_divider ui-corner-top\">BANCA DEL TEMPO</div>
                                    <div class=\"menu_lat_container ui-corner-bottom\">
                                        <a href=\"".$RG_addr["ci_table_bdt"]."\">Lista annunci</a>
                                    </div>

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
                   $h .= "<div id=\"section_8\">".$menu_lat_coseinutili."</div>";
            }else{

                for($i=0;$i<8;$i++){
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

                        case 8:
                             $h .= "<div id=\"section_8\">".$menu_lat_coseinutili."</div>";
                        break;
                    }
                }
            } // SE USER HA L'ORDINAMENTO LATERALE







			$h .= "</div>";

            //AGGIUNTA SPONSOR COSEINUTILI
            $h2 .= "<div id=\"coseinutili\" style=\"margin-top:1em;margin-left:9px\">";
            $h2 .= "<a href=\"".$RG_addr["coseinutili"]."\">";
            $h2 .= "<img SRC=\"".$RG_addr["logo_coseinutili"]."\" ".rg_tooltip("Cose(in)utili, un sito che permette di gestire lo scambio, il baratto e la banca del tempo. Clicca per avere maggiori informazioni.")." width=112 height=29 >";
            $h2 .= "</a>";
            $h2 .= "</div>";



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
						   //"widgets_ui" =>          "<link type=\"text/css\" href=\"".$RG_addr["css_widgets_ui"]."\" rel=\"Stylesheet\">\n",
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


    //FONT ZOOM
    $this->css_body[]="<style type=\"text/css\">
                            .rg_widget{font-size:".number_format(_USER_OPT_ZOOM_FONTS/100,2)."em;}
                            </style>";


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
				  </div>
                  <br>
                  <hr id="linea_sotto_menu">';

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
$h2 .= "<script>$('form#rg').submit(function(e){
$(this).children('input[type=submit]').attr('disabled', true);
});</script>";

//DISQUS
//if($this->disqus_id<>""){
if(false){

    $h .="<script type=\"text/javascript\">
            /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
            var disqus_shortname = 'reteDes'; // required: replace example with your forum shortname
            var disqus_identifier = '".$this->disqus_id."';
            var disqus_url = window.location.href+'/#".$this->disqus_id."';
            console.log(disqus_url);

            /* * * DON'T EDIT BELOW THIS LINE * * */
            (function() {
                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
            })();
            (function () {
                var s = document.createElement('script'); s.async = true;
                s.type = 'text/javascript';
                s.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
                (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
                }());
        </script>";
    }
$h .= "<script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-50606408-1', 'retedes.it');
          ga('send', 'pageview');

        </script>";
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
public $disqus_id = null;
public $disqus_title =null;
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
$rg->disqus_id = $this->disqus_id;


//HEADER
$rg->java_scripts_header[] = java_accordion(null,$this->voce_mv_attiva); // laterale
$rg->java_scripts_header[] = java_superfish();
$rg->java_scripts_header = array_merge($rg->java_scripts_header,$this->javascripts_header);

//BODY
if(!is_empty($this->tabella_da_ordinare)){
 $rg->java_scripts_bottom_body[] = java_tablesorter($this->tabella_da_ordinare);
}
//Auto disable double input submit

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
public $selected = "";
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

class MessageTemplateFile{
    /**
     * @var string
     */
    private $file;
    /**
     * @var string[] varname => string value
     */
    private $vars;

    public function __construct($file, array $vars = array())
    {
        $this->file = (string)$file;
        $this->setVars($vars);
    }

    public function setVars(array $vars)
    {
        $this->vars = $vars;
    }

    public function getTemplateText()
    {
        return file_get_contents($this->file);
    }

    public function __toString()
    {
        return strtr($this->getTemplateText(), $this->getReplacementPairs());
    }

    private function getReplacementPairs()
    {
        $pairs = array();
        foreach ($this->var as $name => $value)
        {
            $key = sprintf('[{%s}]', strtoupper($name));
            $pairs[$key] = (string)$value;
        }
        return $pairs;
    }
}

//GIUGNO 2013
class Mobile_Detect {

    protected $scriptVersion = '2.6.2';

    // External info.
    protected $userAgent = null;
    protected $httpHeaders;

    // Arrays holding all detection rules.
    protected $mobileDetectionRules = null;
    protected $mobileDetectionRulesExtended = null;
    // Type of detection to use.
    protected $detectionType = 'mobile'; // mobile, extended @todo: refactor this.

    // List of mobile devices (phones)
    protected $phoneDevices = array(
        'iPhone'        => '\biPhone.*Mobile|\biPod|\biTunes',
        'BlackBerry'    => 'BlackBerry|\bBB10\b|rim[0-9]+',
        'HTC'           => 'HTC|HTC.*(Sensation|Evo|Vision|Explorer|6800|8100|8900|A7272|S510e|C110e|Legend|Desire|T8282)|APX515CKT|Qtek9090|APA9292KT|HD_mini|Sensation.*Z710e|PG86100|Z715e|Desire.*(A8181|HD)|ADR6200|ADR6425|001HT|Inspire 4G|Android.*\bEVO\b',
        'Nexus'         => 'Nexus One|Nexus S|Galaxy.*Nexus|Android.*Nexus.*Mobile',
        // @todo: Is 'Dell Streak' a tablet or a phone? ;)
        'Dell'          => 'Dell.*Streak|Dell.*Aero|Dell.*Venue|DELL.*Venue Pro|Dell Flash|Dell Smoke|Dell Mini 3iX|XCD28|XCD35|\b001DL\b|\b101DL\b|\bGS01\b',
        'Motorola'      => 'Motorola|\bDroid\b.*Build|DROIDX|Android.*Xoom|HRI39|MOT-|A1260|A1680|A555|A853|A855|A953|A955|A956|Motorola.*ELECTRIFY|Motorola.*i1|i867|i940|MB200|MB300|MB501|MB502|MB508|MB511|MB520|MB525|MB526|MB611|MB612|MB632|MB810|MB855|MB860|MB861|MB865|MB870|ME501|ME502|ME511|ME525|ME600|ME632|ME722|ME811|ME860|ME863|ME865|MT620|MT710|MT716|MT720|MT810|MT870|MT917|Motorola.*TITANIUM|WX435|WX445|XT300|XT301|XT311|XT316|XT317|XT319|XT320|XT390|XT502|XT530|XT531|XT532|XT535|XT603|XT610|XT611|XT615|XT681|XT701|XT702|XT711|XT720|XT800|XT806|XT860|XT862|XT875|XT882|XT883|XT894|XT909|XT910|XT912|XT928',
        'Samsung'       => 'Samsung|SGH-I337|BGT-S5230|GT-B2100|GT-B2700|GT-B2710|GT-B3210|GT-B3310|GT-B3410|GT-B3730|GT-B3740|GT-B5510|GT-B5512|GT-B5722|GT-B6520|GT-B7300|GT-B7320|GT-B7330|GT-B7350|GT-B7510|GT-B7722|GT-B7800|GT-C3010|GT-C3011|GT-C3060|GT-C3200|GT-C3212|GT-C3212I|GT-C3262|GT-C3222|GT-C3300|GT-C3300K|GT-C3303|GT-C3303K|GT-C3310|GT-C3322|GT-C3330|GT-C3350|GT-C3500|GT-C3510|GT-C3530|GT-C3630|GT-C3780|GT-C5010|GT-C5212|GT-C6620|GT-C6625|GT-C6712|GT-E1050|GT-E1070|GT-E1075|GT-E1080|GT-E1081|GT-E1085|GT-E1087|GT-E1100|GT-E1107|GT-E1110|GT-E1120|GT-E1125|GT-E1130|GT-E1160|GT-E1170|GT-E1175|GT-E1180|GT-E1182|GT-E1200|GT-E1210|GT-E1225|GT-E1230|GT-E1390|GT-E2100|GT-E2120|GT-E2121|GT-E2152|GT-E2220|GT-E2222|GT-E2230|GT-E2232|GT-E2250|GT-E2370|GT-E2550|GT-E2652|GT-E3210|GT-E3213|GT-I5500|GT-I5503|GT-I5700|GT-I5800|GT-I5801|GT-I6410|GT-I6420|GT-I7110|GT-I7410|GT-I7500|GT-I8000|GT-I8150|GT-I8160|GT-I8320|GT-I8330|GT-I8350|GT-I8530|GT-I8700|GT-I8703|GT-I8910|GT-I9000|GT-I9001|GT-I9003|GT-I9010|GT-I9020|GT-I9023|GT-I9070|GT-I9100|GT-I9103|GT-I9220|GT-I9250|GT-I9300|GT-I9505|GT-M3510|GT-M5650|GT-M7500|GT-M7600|GT-M7603|GT-M8800|GT-M8910|GT-N7000|GT-S3110|GT-S3310|GT-S3350|GT-S3353|GT-S3370|GT-S3650|GT-S3653|GT-S3770|GT-S3850|GT-S5210|GT-S5220|GT-S5229|GT-S5230|GT-S5233|GT-S5250|GT-S5253|GT-S5260|GT-S5263|GT-S5270|GT-S5300|GT-S5330|GT-S5350|GT-S5360|GT-S5363|GT-S5369|GT-S5380|GT-S5380D|GT-S5560|GT-S5570|GT-S5600|GT-S5603|GT-S5610|GT-S5620|GT-S5660|GT-S5670|GT-S5690|GT-S5750|GT-S5780|GT-S5830|GT-S5839|GT-S6102|GT-S6500|GT-S7070|GT-S7200|GT-S7220|GT-S7230|GT-S7233|GT-S7250|GT-S7500|GT-S7530|GT-S7550|GT-S7562|GT-S8000|GT-S8003|GT-S8500|GT-S8530|GT-S8600|SCH-A310|SCH-A530|SCH-A570|SCH-A610|SCH-A630|SCH-A650|SCH-A790|SCH-A795|SCH-A850|SCH-A870|SCH-A890|SCH-A930|SCH-A950|SCH-A970|SCH-A990|SCH-I100|SCH-I110|SCH-I400|SCH-I405|SCH-I500|SCH-I510|SCH-I515|SCH-I600|SCH-I730|SCH-I760|SCH-I770|SCH-I830|SCH-I910|SCH-I920|SCH-LC11|SCH-N150|SCH-N300|SCH-R100|SCH-R300|SCH-R351|SCH-R400|SCH-R410|SCH-T300|SCH-U310|SCH-U320|SCH-U350|SCH-U360|SCH-U365|SCH-U370|SCH-U380|SCH-U410|SCH-U430|SCH-U450|SCH-U460|SCH-U470|SCH-U490|SCH-U540|SCH-U550|SCH-U620|SCH-U640|SCH-U650|SCH-U660|SCH-U700|SCH-U740|SCH-U750|SCH-U810|SCH-U820|SCH-U900|SCH-U940|SCH-U960|SCS-26UC|SGH-A107|SGH-A117|SGH-A127|SGH-A137|SGH-A157|SGH-A167|SGH-A177|SGH-A187|SGH-A197|SGH-A227|SGH-A237|SGH-A257|SGH-A437|SGH-A517|SGH-A597|SGH-A637|SGH-A657|SGH-A667|SGH-A687|SGH-A697|SGH-A707|SGH-A717|SGH-A727|SGH-A737|SGH-A747|SGH-A767|SGH-A777|SGH-A797|SGH-A817|SGH-A827|SGH-A837|SGH-A847|SGH-A867|SGH-A877|SGH-A887|SGH-A897|SGH-A927|SGH-B100|SGH-B130|SGH-B200|SGH-B220|SGH-C100|SGH-C110|SGH-C120|SGH-C130|SGH-C140|SGH-C160|SGH-C170|SGH-C180|SGH-C200|SGH-C207|SGH-C210|SGH-C225|SGH-C230|SGH-C417|SGH-C450|SGH-D307|SGH-D347|SGH-D357|SGH-D407|SGH-D415|SGH-D780|SGH-D807|SGH-D980|SGH-E105|SGH-E200|SGH-E315|SGH-E316|SGH-E317|SGH-E335|SGH-E590|SGH-E635|SGH-E715|SGH-E890|SGH-F300|SGH-F480|SGH-I200|SGH-I300|SGH-I320|SGH-I550|SGH-I577|SGH-I600|SGH-I607|SGH-I617|SGH-I627|SGH-I637|SGH-I677|SGH-I700|SGH-I717|SGH-I727|SGH-i747M|SGH-I777|SGH-I780|SGH-I827|SGH-I847|SGH-I857|SGH-I896|SGH-I897|SGH-I900|SGH-I907|SGH-I917|SGH-I927|SGH-I937|SGH-I997|SGH-J150|SGH-J200|SGH-L170|SGH-L700|SGH-M110|SGH-M150|SGH-M200|SGH-N105|SGH-N500|SGH-N600|SGH-N620|SGH-N625|SGH-N700|SGH-N710|SGH-P107|SGH-P207|SGH-P300|SGH-P310|SGH-P520|SGH-P735|SGH-P777|SGH-Q105|SGH-R210|SGH-R220|SGH-R225|SGH-S105|SGH-S307|SGH-T109|SGH-T119|SGH-T139|SGH-T209|SGH-T219|SGH-T229|SGH-T239|SGH-T249|SGH-T259|SGH-T309|SGH-T319|SGH-T329|SGH-T339|SGH-T349|SGH-T359|SGH-T369|SGH-T379|SGH-T409|SGH-T429|SGH-T439|SGH-T459|SGH-T469|SGH-T479|SGH-T499|SGH-T509|SGH-T519|SGH-T539|SGH-T559|SGH-T589|SGH-T609|SGH-T619|SGH-T629|SGH-T639|SGH-T659|SGH-T669|SGH-T679|SGH-T709|SGH-T719|SGH-T729|SGH-T739|SGH-T746|SGH-T749|SGH-T759|SGH-T769|SGH-T809|SGH-T819|SGH-T839|SGH-T919|SGH-T929|SGH-T939|SGH-T959|SGH-T989|SGH-U100|SGH-U200|SGH-U800|SGH-V205|SGH-V206|SGH-X100|SGH-X105|SGH-X120|SGH-X140|SGH-X426|SGH-X427|SGH-X475|SGH-X495|SGH-X497|SGH-X507|SGH-X600|SGH-X610|SGH-X620|SGH-X630|SGH-X700|SGH-X820|SGH-X890|SGH-Z130|SGH-Z150|SGH-Z170|SGH-ZX10|SGH-ZX20|SHW-M110|SPH-A120|SPH-A400|SPH-A420|SPH-A460|SPH-A500|SPH-A560|SPH-A600|SPH-A620|SPH-A660|SPH-A700|SPH-A740|SPH-A760|SPH-A790|SPH-A800|SPH-A820|SPH-A840|SPH-A880|SPH-A900|SPH-A940|SPH-A960|SPH-D600|SPH-D700|SPH-D710|SPH-D720|SPH-I300|SPH-I325|SPH-I330|SPH-I350|SPH-I500|SPH-I600|SPH-I700|SPH-L700|SPH-M100|SPH-M220|SPH-M240|SPH-M300|SPH-M305|SPH-M320|SPH-M330|SPH-M350|SPH-M360|SPH-M370|SPH-M380|SPH-M510|SPH-M540|SPH-M550|SPH-M560|SPH-M570|SPH-M580|SPH-M610|SPH-M620|SPH-M630|SPH-M800|SPH-M810|SPH-M850|SPH-M900|SPH-M910|SPH-M920|SPH-M930|SPH-N100|SPH-N200|SPH-N240|SPH-N300|SPH-N400|SPH-Z400|SWC-E100|SCH-i909|GT-N7100|SCH-I535',
        'LG'            => '\bLG\b;|(LG|LG-)?(C800|C900|E400|E610|E900|E-900|F160|F180K|F180L|F180S|730|855|L160|LS840|LS970|LU6200|MS690|MS695|MS770|MS840|MS870|MS910|P500|P700|P705|VM696|AS680|AS695|AX840|C729|E970|GS505|272|C395|E739BK|E960|L55C|L75C|LS696|LS860|P769BK|P350|P870|UN272|US730|VS840|VS950|LN272|LN510|LS670|LS855|LW690|MN270|MN510|P509|P769|P930|UN200|UN270|UN510|UN610|US670|US740|US760|UX265|UX840|VN271|VN530|VS660|VS700|VS740|VS750|VS910|VS920|VS930|VX9200|VX11000|AX840A|LW770|P506|P925|P999)',
        'Sony'          => 'sony|SonyEricsson|SonyEricssonLT15iv|LT18i|E10i',
        'Asus'          => 'Asus.*Galaxy',
        'Palm'          => 'PalmSource|Palm', // avantgo|blazer|elaine|hiptop|plucker|xiino ; @todo - complete the regex.
        'Vertu'         => 'Vertu|Vertu.*Ltd|Vertu.*Ascent|Vertu.*Ayxta|Vertu.*Constellation(F|Quest)?|Vertu.*Monika|Vertu.*Signature', // Just for fun ;)
        // @ref: http://www.pantech.co.kr/en/prod/prodList.do?gbrand=VEGA (PANTECH)
        // Most of the VEGA devices are legacy. PANTECH seem to be newer devices based on Android.
        'Pantech'       => 'PANTECH|IM-A850S|IM-A840S|IM-A830L|IM-A830K|IM-A830S|IM-A820L|IM-A810K|IM-A810S|IM-A800S|IM-T100K|IM-A725L|IM-A780L|IM-A775C|IM-A770K|IM-A760S|IM-A750K|IM-A740S|IM-A730S|IM-A720L|IM-A710K|IM-A690L|IM-A690S|IM-A650S|IM-A630K|IM-A600S|VEGA PTL21|PT003|P8010|ADR910L|P6030|P6020|P9070|P4100|P9060|P5000|CDM8992|TXT8045|ADR8995|IS11PT|P2030|P6010|P8000|PT002|IS06|CDM8999|P9050|PT001|TXT8040|P2020|P9020|P2000|P7040|P7000|C790',
        // @ref: http://www.fly-phone.com/devices/smartphones/ ; Included only smartphones.
        'Fly'           => 'IQ230|IQ444|IQ450|IQ440|IQ442|IQ441|IQ245|IQ256|IQ236|IQ255|IQ235|IQ245|IQ275|IQ240|IQ285|IQ280|IQ270|IQ260|IQ250',
        // Added simvalley mobile just for fun. They have some interesting devices.
        // @ref: http://www.simvalley.fr/telephonie---gps-_22_telephonie-mobile_telephones_.html
        'SimValley'     => '\b(SP-80|XT-930|SX-340|XT-930|SX-310|SP-360|SP60|SPT-800|SP-120|SPT-800|SP-140|SPX-5|SPX-8|SP-100|SPX-8|SPX-12)\b',
        // @Tapatalk is a mobile app; @ref: http://support.tapatalk.com/threads/smf-2-0-2-os-and-browser-detection-plugin-and-tapatalk.15565/#post-79039
        'GenericPhone'  => 'Tapatalk|PDA;|PPC;|SAGEM|mmp|pocket|psp|symbian|Smartphone|smartfon|treo|up.browser|up.link|vodafone|wap|nokia|Series40|Series60|S60|SonyEricsson|N900|MAUI.*WAP.*Browser|LG-P500'
    );
    // List of tablet devices.
    protected $tabletDevices = array(
        'iPad'              => 'iPad|iPad.*Mobile', // @todo: check for mobile friendly emails topic.
        'NexusTablet'       => '^.*Android.*Nexus(((?:(?!Mobile))|(?:(\s(7|10).+))).)*$',
        'SamsungTablet'     => 'SAMSUNG.*Tablet|Galaxy.*Tab|SC-01C|GT-P1000|GT-P1003|GT-P1010|GT-P3105|GT-P6210|GT-P6800|GT-P6810|GT-P7100|GT-P7300|GT-P7310|GT-P7500|GT-P7510|SCH-I800|SCH-I815|SCH-I905|SGH-I957|SGH-I987|SGH-T849|SGH-T859|SGH-T869|SPH-P100|GT-P3100|GT-P3108|GT-P3110|GT-P5100|GT-P5110|GT-P6200|GT-P7320|GT-P7511|GT-N8000|GT-P8510|SGH-I497|SPH-P500|SGH-T779|SCH-I705|SCH-I915|GT-N8013|GT-P3113|GT-P5113|GT-P8110|GT-N8010|GT-N8005|GT-N8020|GT-P1013|GT-P6201|GT-P7501|GT-N5100|GT-N5110|SHV-E140K|SHV-E140L|SHV-E140S|SHV-E150S|SHV-E230K|SHV-E230L|SHV-E230S|SHW-M180K|SHW-M180L|SHW-M180S|SHW-M180W|SHW-M300W|SHW-M305W|SHW-M380K|SHW-M380S|SHW-M380W|SHW-M430W|SHW-M480K|SHW-M480S|SHW-M480W|SHW-M485W|SHW-M486W|SHW-M500W|GT-I9228|SCH-P739|SCH-I925',
        // @reference: http://www.labnol.org/software/kindle-user-agent-string/20378/
        'Kindle'            => 'Kindle|Silk.*Accelerated',
        // Only the Surface tablets with Windows RT are considered mobile.
        // @ref: http://msdn.microsoft.com/en-us/library/ie/hh920767(v=vs.85).aspx
        'SurfaceTablet'     => 'Windows NT [0-9.]+; ARM;',
        'AsusTablet'        => 'Transformer|TF101',
        'BlackBerryTablet'  => 'PlayBook|RIM Tablet',
        'HTCtablet'         => 'HTC Flyer|HTC Jetstream|HTC-P715a|HTC EVO View 4G|PG41200',
        'MotorolaTablet'    => 'xoom|sholest|MZ615|MZ605|MZ505|MZ601|MZ602|MZ603|MZ604|MZ606|MZ607|MZ608|MZ609|MZ615|MZ616|MZ617',
        'NookTablet'        => 'Android.*Nook|NookColor|nook browser|BNRV200|BNRV200A|BNTV250|BNTV250A|LogicPD Zoom2',
        // @ref: http://www.acer.ro/ac/ro/RO/content/drivers
        // @ref: http://www.packardbell.co.uk/pb/en/GB/content/download (Packard Bell is part of Acer)
        'AcerTablet'        => 'Android.*\b(A100|A101|A110|A200|A210|A211|A500|A501|A510|A511|A700|A701|W500|W500P|W501|W501P|W510|W511|W700|G100|G100W|B1-A71)\b',
        // @ref: http://eu.computers.toshiba-europe.com/innovation/family/Tablets/1098744/banner_id/tablet_footerlink/
        // @ref: http://us.toshiba.com/tablets/tablet-finder
        // @ref: http://www.toshiba.co.jp/regza/tablet/
        'ToshibaTablet'     => 'Android.*(AT100|AT105|AT200|AT205|AT270|AT275|AT300|AT305|AT1S5|AT500|AT570|AT700|AT830)|TOSHIBA.*FOLIO',
        // @ref: http://www.nttdocomo.co.jp/english/service/developer/smart_phone/technical_info/spec/index.html
        'LGTablet'          => '\bL-06C|LG-V900|LG-V909\b',
        // Prestigio Tablets http://www.prestigio.com/support
        'PrestigioTablet'   => 'PMP3170B|PMP3270B|PMP3470B|PMP7170B|PMP3370B|PMP3570C|PMP5870C|PMP3670B|PMP5570C|PMP5770D|PMP3970B|PMP3870C|PMP5580C|PMP5880D|PMP5780D|PMP5588C|PMP7280C|PMP7280|PMP7880D|PMP5597D|PMP5597|PMP7100D|PER3464|PER3274|PER3574|PER3884|PER5274|PER5474',
        'YarvikTablet'      => 'Android.*(TAB210|TAB211|TAB224|TAB250|TAB260|TAB264|TAB310|TAB360|TAB364|TAB410|TAB411|TAB420|TAB424|TAB450|TAB460|TAB461|TAB464|TAB465|TAB467|TAB468)',
        'MedionTablet'      => 'Android.*\bOYO\b|LIFE.*(P9212|P9514|P9516|S9512)|LIFETAB',
        'ArnovaTablet'      => 'AN10G2|AN7bG3|AN7fG3|AN8G3|AN8cG3|AN7G3|AN9G3|AN7dG3|AN7dG3ST|AN7dG3ChildPad|AN10bG3|AN10bG3DT',
        // @reference: http://wiki.archosfans.com/index.php?title=Main_Page
        'ArchosTablet'      => 'Android.*ARCHOS|\b101G9\b|\b80G9\b',
        // @reference: http://en.wikipedia.org/wiki/NOVO7
        'AinolTablet'       => 'NOVO7|Novo7Aurora|Novo7Basic|NOVO7PALADIN',
        // @todo: inspect http://esupport.sony.com/US/p/select-system.pl?DIRECTOR=DRIVER
        // @ref: Readers http://www.atsuhiro-me.net/ebook/sony-reader/sony-reader-web-browser
        // @ref: http://www.sony.jp/support/tablet/
        'SonyTablet'        => 'Sony.*Tablet|Xperia Tablet|Sony Tablet S|SO-03E|SGPT12|SGPT121|SGPT122|SGPT123|SGPT111|SGPT112|SGPT113|SGPT211|SGPT213|SGP311|SGP312|SGP321|EBRD1101|EBRD1102|EBRD1201',
        // @ref: db + http://www.cube-tablet.com/buy-products.html
        'CubeTablet'        => 'Android.*(K8GT|U9GT|U10GT|U16GT|U17GT|U18GT|U19GT|U20GT|U23GT|U30GT)|CUBE U8GT',
        // @ref: http://www.cobyusa.com/?p=pcat&pcat_id=3001
        'CobyTablet'        => 'MID1042|MID1045|MID1125|MID1126|MID7012|MID7014|MID7034|MID7035|MID7036|MID7042|MID7048|MID7127|MID8042|MID8048|MID8127|MID9042|MID9740|MID9742|MID7022|MID7010',
        // @ref: http://pdadb.net/index.php?m=pdalist&list=SMiT (NoName Chinese Tablets)
        // @ref: http://www.imp3.net/14/show.php?itemid=20454
        'SMiTTablet'        => 'Android.*(\bMID\b|MID-560|MTV-T1200|MTV-PND531|MTV-P1101|MTV-PND530)',
        // @ref: http://www.rock-chips.com/index.php?do=prod&pid=2
        'RockChipTablet'    => 'Android.*(RK2818|RK2808A|RK2918|RK3066)|RK2738|RK2808A',
        // @ref: http://www.telstra.com.au/home-phone/thub-2/
        'TelstraTablet'     => 'T-Hub2',
        // @ref: http://www.fly-phone.com/devices/tablets/ ; http://www.fly-phone.com/service/
        'FlyTablet'         => 'IQ310|Fly Vision',
        // @ref: http://www.bqreaders.com/gb/tablets-prices-sale.html
        'bqTablet'          => 'bq.*(Elcano|Curie|Edison|Maxwell|Kepler|Pascal|Tesla|Hypatia|Platon|Newton|Livingstone|Cervantes|Avant)',
        // @ref: http://www.huaweidevice.com/worldwide/productFamily.do?method=index&directoryId=5011&treeId=3290
        // @ref: http://www.huaweidevice.com/worldwide/downloadCenter.do?method=index&directoryId=3372&treeId=0&tb=1&type=software (including legacy tablets)
        'HuaweiTablet'      => 'MediaPad|IDEOS S7|S7-201c|S7-202u|S7-101|S7-103|S7-104|S7-105|S7-106|S7-201|S7-Slim',
        // Nec or Medias Tab
        'NecTablet'         => '\bN-06D|\bN-08D',
        // Pantech Tablets: http://www.pantechusa.com/phones/
        'PantechTablet'     => 'Pantech.*P4100',
        // Broncho Tablets: http://www.broncho.cn/ (hard to find)
        'BronchoTablet'     => 'Broncho.*(N701|N708|N802|a710)',
        // @ref: http://versusuk.com/support.html
        'VersusTablet'      => 'TOUCHPAD.*[78910]',
        // @ref: http://www.zync.in/index.php/our-products/tablet-phablets
        'ZyncTablet'        => 'z1000|Z99 2G|z99|z930|z999|z990|z909|Z919|z900',
        // @ref: http://www.positivoinformatica.com.br/www/pessoal/tablet-ypy/
        'PositivoTablet'    => 'TB07STA|TB10STA|TB07FTA|TB10FTA',
        // @ref: https://www.nabitablet.com/
        'NabiTablet'        => 'Android.*\bNabi',
        'KoboTablet'        => 'Kobo Touch|\bK080\b|\bVox\b Build|\bArc\b Build',
        // French Danew Tablets http://www.danew.com/produits-tablette.php
        'DanewTablet'       => 'DSlide.*\b(700|701R|702|703R|704|802|970|971|972|973|974|1010|1012)\b',
        // Texet Tablets and Readers http://www.texet.ru/tablet/
        'TexetTablet'       => 'NaviPad|TB-772A|TM-7045|TM-7055|TM-9750|TM-7016|TM-7024|TM-7026|TM-7041|TM-7043|TM-7047|TM-8041|TM-9741|TM-9747|TM-9748|TM-9751|TM-7022|TM-7021|TM-7020|TM-7011|TM-7010|TM-7023|TM-7025|TM-7037W|TM-7038W|TM-7027W|TM-9720|TM-9725|TM-9737W|TM-1020|TM-9738W|TM-9740|TM-9743W|TB-807A|TB-771A|TB-727A|TB-725A|TB-719A|TB-823A|TB-805A|TB-723A|TB-715A|TB-707A|TB-705A|TB-709A|TB-711A|TB-890HD|TB-880HD|TB-790HD|TB-780HD|TB-770HD|TB-721HD|TB-710HD|TB-434HD|TB-860HD|TB-840HD|TB-760HD|TB-750HD|TB-740HD|TB-730HD|TB-722HD|TB-720HD|TB-700HD|TB-500HD|TB-470HD|TB-431HD|TB-430HD|TB-506|TB-504|TB-446|TB-436|TB-416|TB-146SE|TB-126SE',
        // @note: Avoid detecting 'PLAYSTATION 3' as mobile.
        'PlaystationTablet' => 'Playstation.*(Portable|Vita)',
        // @ref: http://www.galapad.net/product.html
        'GalapadTablet'     => 'Android.*\bG1\b',
        'GenericTablet'     => 'Android.*\b97D\b|Tablet(?!.*PC)|ViewPad7|MID7015|BNTV250A|LogicPD Zoom2|\bA7EB\b|CatNova8|A1_07|CT704|CT1002|\bM721\b|hp-tablet|rk30sdk',
    );
    // List of mobile Operating Systems.
    protected $operatingSystems = array(
        'AndroidOS'         => 'Android',
        'BlackBerryOS'      => 'blackberry|\bBB10\b|rim tablet os',
        'PalmOS'            => 'PalmOS|avantgo|blazer|elaine|hiptop|palm|plucker|xiino',
        'SymbianOS'         => 'Symbian|SymbOS|Series60|Series40|SYB-[0-9]+|\bS60\b',
        // @reference: http://en.wikipedia.org/wiki/Windows_Mobile
        'WindowsMobileOS'   => 'Windows CE.*(PPC|Smartphone|Mobile|[0-9]{3}x[0-9]{3})|Window Mobile|Windows Phone [0-9.]+|WCE;',
        // @reference: http://en.wikipedia.org/wiki/Windows_Phone
        // http://wifeng.cn/?r=blog&a=view&id=106
        // http://nicksnettravels.builttoroam.com/post/2011/01/10/Bogus-Windows-Phone-7-User-Agent-String.aspx
        'WindowsPhoneOS'   => 'Windows Phone OS|XBLWP7|ZuneWP7',
        'iOS'               => '\biPhone.*Mobile|\biPod|\biPad',
        // http://en.wikipedia.org/wiki/MeeGo
        // @todo: research MeeGo in UAs
        'MeeGoOS'           => 'MeeGo',
        // http://en.wikipedia.org/wiki/Maemo
        // @todo: research Maemo in UAs
        'MaemoOS'           => 'Maemo',
        'JavaOS'            => 'J2ME/|Java/|\bMIDP\b|\bCLDC\b',
        'webOS'             => 'webOS|hpwOS',
        'badaOS'            => '\bBada\b',
        'BREWOS'            => 'BREW',
    );
    // List of mobile User Agents.
    protected $userAgents = array(
        // @reference: https://developers.google.com/chrome/mobile/docs/user-agent
        'Chrome'          => '\bCrMo\b|CriOS|Android.*Chrome/[.0-9]* (Mobile)?',
        'Dolfin'          => '\bDolfin\b',
        'Opera'           => 'Opera.*Mini|Opera.*Mobi|Android.*Opera|Mobile.*OPR/[0-9.]+',
        'Skyfire'         => 'Skyfire',
        'IE'              => 'IEMobile|MSIEMobile',
        'Firefox'         => 'fennec|firefox.*maemo|(Mobile|Tablet).*Firefox|Firefox.*Mobile',
        'Bolt'            => 'bolt',
        'TeaShark'        => 'teashark',
        'Blazer'          => 'Blazer',
        // @reference: http://developer.apple.com/library/safari/#documentation/AppleApplications/Reference/SafariWebContent/OptimizingforSafarioniPhone/OptimizingforSafarioniPhone.html#//apple_ref/doc/uid/TP40006517-SW3
        'Safari'          => 'Version.*Mobile.*Safari|Safari.*Mobile',
        // @ref: http://en.wikipedia.org/wiki/Midori_(web_browser)
        //'Midori'          => 'midori',
        'Tizen'           => 'Tizen',
        'UCBrowser'       => 'UC.*Browser|UCWEB',
        // @ref: https://github.com/serbanghita/Mobile-Detect/issues/7
        'DiigoBrowser'    => 'DiigoBrowser',
        // http://www.puffinbrowser.com/index.php
        'Puffin'            => 'Puffin',
        // @ref: http://mercury-browser.com/index.html
        'Mercury'          => '\bMercury\b',
        // @reference: http://en.wikipedia.org/wiki/Minimo
        // http://en.wikipedia.org/wiki/Vision_Mobile_Browser
        'GenericBrowser'  => 'NokiaBrowser|OviBrowser|OneBrowser|TwonkyBeamBrowser|SEMC.*Browser|FlyFlow|Minimo|NetFront|Novarra-Vision'
    );
    // Utilities.
    protected $utilities = array(
        // Experimental. When a mobile device wants to switch to 'Desktop Mode'.
        // @ref: http://scottcate.com/technology/windows-phone-8-ie10-desktop-or-mobile/
        // @ref: https://github.com/serbanghita/Mobile-Detect/issues/57#issuecomment-15024011
        'DesktopMode'   => 'WPDesktop',
        'TV'            => 'SonyDTV115', // experimental
        'WebKit'        => '(webkit)[ /]([\w.]+)',
        'Bot'           => 'Googlebot|DoCoMo|YandexBot|bingbot|ia_archiver|AhrefsBot|Ezooms|GSLFbot|WBSearchBot|Twitterbot|TweetmemeBot|Twikle|PaperLiBot|Wotbox|UnwindFetchor|facebookexternalhit',
        'MobileBot'     => 'Googlebot-Mobile|DoCoMo|YahooSeeker/M1A1-R2D2',
    );
    // Properties list.
    // @reference: http://user-agent-string.info/list-of-ua#Mobile Browser
    const VER = '([\w._\+]+)';
    protected $properties = array(

        // Build
        'Mobile'        => 'Mobile/[VER]',
        'Build'         => 'Build/[VER]',
        'Version'       => 'Version/[VER]',
        'VendorID'      => 'VendorID/[VER]',

        // Devices
        'iPad'          => 'iPad.*CPU[a-z ]+[VER]',
        'iPhone'        => 'iPhone.*CPU[a-z ]+[VER]',
        'iPod'          => 'iPod.*CPU[a-z ]+[VER]',
        //'BlackBerry'    => array('BlackBerry[VER]', 'BlackBerry [VER];'),
        'Kindle'        => 'Kindle/[VER]',

        // Browser
        'Chrome'        => array('Chrome/[VER]', 'CriOS/[VER]', 'CrMo/[VER]'),
        'Dolfin'        => 'Dolfin/[VER]',
        // @reference: https://developer.mozilla.org/en-US/docs/User_Agent_Strings_Reference
        'Firefox'       => 'Firefox/[VER]',
        'Fennec'        => 'Fennec/[VER]',
        // @reference: http://msdn.microsoft.com/en-us/library/ms537503(v=vs.85).aspx
        'IE'      => array('IEMobile/[VER];', 'IEMobile [VER]', 'MSIE [VER];'),
        // http://en.wikipedia.org/wiki/NetFront
        'NetFront'      => 'NetFront/[VER]',
        'NokiaBrowser'  => 'NokiaBrowser/[VER]',
        'Opera'         => array( ' OPR/[VER]', 'Opera Mini/[VER]', 'Version/[VER]' ),
        'UC Browser'    => 'UC Browser[VER]',
        // @note: Safari 7534.48.3 is actually Version 5.1.
        // @note: On BlackBerry the Version is overwriten by the OS.
        'Safari'        => array( 'Version/[VER]', 'Safari/[VER]' ),
        'Skyfire'       => 'Skyfire/[VER]',
        'Tizen'         => 'Tizen/[VER]',
        'Webkit'        => 'webkit[ /][VER]',

        // Engine
        'Gecko'         => 'Gecko/[VER]',
        'Trident'       => 'Trident/[VER]',
        'Presto'        => 'Presto/[VER]',

        // OS
        'iOS'              => ' \bOS\b [VER] ',
        'Android'          => 'Android [VER]',
        'BlackBerry'       => array('BlackBerry[\w]+/[VER]', 'BlackBerry.*Version/[VER]', 'Version/[VER]'),
        'BREW'             => 'BREW [VER]',
        'Java'             => 'Java/[VER]',
        // @reference: http://windowsteamblog.com/windows_phone/b/wpdev/archive/2011/08/29/introducing-the-ie9-on-windows-phone-mango-user-agent-string.aspx
        // @reference: http://en.wikipedia.org/wiki/Windows_NT#Releases
        'Windows Phone OS' => array( 'Windows Phone OS [VER]', 'Windows Phone [VER]'),
        'Windows Phone'    => 'Windows Phone [VER]',
        'Windows CE'       => 'Windows CE/[VER]',
        // http://social.msdn.microsoft.com/Forums/en-US/windowsdeveloperpreviewgeneral/thread/6be392da-4d2f-41b4-8354-8dcee20c85cd
        'Windows NT'       => 'Windows NT [VER]',
        'Symbian'          => array('SymbianOS/[VER]', 'Symbian/[VER]'),
        'webOS'            => array('webOS/[VER]', 'hpwOS/[VER];'),


    );

    function __construct(){

        $this->setHttpHeaders();
        $this->setUserAgent();

        $this->setMobileDetectionRules();
        $this->setMobileDetectionRulesExtended();

    }


    /**
    * Get the current script version.
    * This is useful for the demo.php file,
    * so people can check on what version they are testing
    * for mobile devices.
    */
    public function getScriptVersion(){

        return $this->scriptVersion;

    }

    public function setHttpHeaders($httpHeaders = null){

        if(!empty($httpHeaders)){
            $this->httpHeaders = $httpHeaders;
        } else {
            foreach($_SERVER as $key => $value){
                if(substr($key,0,5)=='HTTP_'){
                    $this->httpHeaders[$key] = $value;
                }
            }
        }

    }

    public function getHttpHeaders(){

        return $this->httpHeaders;

    }

    public function setUserAgent($userAgent = null){

        if(!empty($userAgent)){
            $this->userAgent = $userAgent;
        } else {
            $this->userAgent    = isset($this->httpHeaders['HTTP_USER_AGENT']) ? $this->httpHeaders['HTTP_USER_AGENT'] : null;

            if(empty($this->userAgent)){
                $this->userAgent = isset($this->httpHeaders['HTTP_X_DEVICE_USER_AGENT']) ? $this->httpHeaders['HTTP_X_DEVICE_USER_AGENT'] : null;
            }
            // Header can occur on devices using Opera Mini (can expose the real device type). Let's concatenate it (we need this extra info in the regexes).
            if(!empty($this->httpHeaders['HTTP_X_OPERAMINI_PHONE_UA'])){
                $this->userAgent .= ' '.$this->httpHeaders['HTTP_X_OPERAMINI_PHONE_UA'];
            }
        }

    }

    public function getUserAgent(){

        return $this->userAgent;

    }

    function setDetectionType($type = null){

        $this->detectionType = (!empty($type) ? $type : 'mobile');

    }

    public function getPhoneDevices(){

        return $this->phoneDevices;

    }

    public function getTabletDevices(){

        return $this->tabletDevices;

    }

    /**
     * Method sets the mobile detection rules.
     *
     * This method is used for the magic methods $detect->is*()
     */
    public function setMobileDetectionRules(){
        // Merge all rules together.
        $this->mobileDetectionRules = array_merge(
            $this->phoneDevices,
            $this->tabletDevices,
            $this->operatingSystems,
            $this->userAgents
        );

    }

    /**
     * Method sets the mobile detection rules + utilities.
     * The reason this is separate is because utilities rules
     * don't necessary imply mobile.
     *
     * This method is used inside the new $detect->is('stuff') method.
     *
     * @return bool
     */
    public function setMobileDetectionRulesExtended(){

        // Merge all rules together.
        $this->mobileDetectionRulesExtended = array_merge(
            $this->phoneDevices,
            $this->tabletDevices,
            $this->operatingSystems,
            $this->userAgents,
            $this->utilities
        );

    }

    /**
     * @return array
     */
    public function getRules()
    {

        if($this->detectionType=='extended'){
            return $this->mobileDetectionRulesExtended;
        } else {
            return $this->mobileDetectionRules;
        }

    }

/**
* Check the HTTP headers for signs of mobile.
* This is the fastest mobile check possible; it's used
* inside isMobile() method.
* @return boolean
*/
    public function checkHttpHeadersForMobile(){

        if(
            isset($this->httpHeaders['HTTP_ACCEPT']) &&
                (strpos($this->httpHeaders['HTTP_ACCEPT'], 'application/x-obml2d') !== false || // Opera Mini; @reference: http://dev.opera.com/articles/view/opera-binary-markup-language/
                 strpos($this->httpHeaders['HTTP_ACCEPT'], 'application/vnd.rim.html') !== false || // BlackBerry devices.
                 strpos($this->httpHeaders['HTTP_ACCEPT'], 'text/vnd.wap.wml') !== false ||
                 strpos($this->httpHeaders['HTTP_ACCEPT'], 'application/vnd.wap.xhtml+xml') !== false) ||
            isset($this->httpHeaders['HTTP_X_WAP_PROFILE'])             || // @todo: validate
            isset($this->httpHeaders['HTTP_X_WAP_CLIENTID'])            ||
            isset($this->httpHeaders['HTTP_WAP_CONNECTION'])            ||
            isset($this->httpHeaders['HTTP_PROFILE'])                   ||
            isset($this->httpHeaders['HTTP_X_OPERAMINI_PHONE_UA'])      || // Reported by Nokia devices (eg. C3)
            isset($this->httpHeaders['HTTP_X_NOKIA_IPADDRESS'])         ||
            isset($this->httpHeaders['HTTP_X_NOKIA_GATEWAY_ID'])        ||
            isset($this->httpHeaders['HTTP_X_ORANGE_ID'])               ||
            isset($this->httpHeaders['HTTP_X_VODAFONE_3GPDPCONTEXT'])   ||
            isset($this->httpHeaders['HTTP_X_HUAWEI_USERID'])           ||
            isset($this->httpHeaders['HTTP_UA_OS'])                     || // Reported by Windows Smartphones.
            isset($this->httpHeaders['HTTP_X_MOBILE_GATEWAY'])          || // Reported by Verizon, Vodafone proxy system.
            isset($this->httpHeaders['HTTP_X_ATT_DEVICEID'])            || // Seend this on HTC Sensation. @ref: SensationXE_Beats_Z715e
            //HTTP_X_NETWORK_TYPE = WIFI
            ( isset($this->httpHeaders['HTTP_UA_CPU']) &&
                    $this->httpHeaders['HTTP_UA_CPU'] == 'ARM'          // Seen this on a HTC.
            )
        ){

            return true;

        }

        return false;

    }

    /**
     * Magic overloading method.
     *
     * @method boolean is[...]()
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {

        $this->setDetectionType('mobile');

        $key = substr($name, 2);
        return $this->matchUAAgainstKey($key);

    }

    /**
    * Find a detection rule that matches the current User-agent.
    *
    * @param null $userAgent deprecated
    * @return boolean
    */
    private function matchDetectionRulesAgainstUA($userAgent = null){

        // Begin general search.
        foreach($this->getRules() as $_regex){
            if(empty($_regex)){ continue; }
            if( $this->match($_regex, $userAgent) ){
                //var_dump( $_regex );
                return true;
            }
        }

        return false;

    }

    /**
    * Search for a certain key in the rules array.
    * If the key is found the try to match the corresponding
    * regex agains the User-agent.
    *
    * @param string $key
    * @param null $userAgent deprecated
    * @return mixed
    */
    private function matchUAAgainstKey($key, $userAgent = null){

        // Make the keys lowercase so we can match: isIphone(), isiPhone(), isiphone(), etc.
        $key = strtolower($key);
        $_rules = array_change_key_case($this->getRules());

        if(array_key_exists($key, $_rules)){
            if(empty($_rules[$key])){ return null; }
            return $this->match($_rules[$key], $userAgent);
        }

        return false;

    }

    /**
    * Check if the device is mobile.
    * Returns true if any type of mobile device detected, including special ones
    * @param null $userAgent deprecated
    * @param null $httpHeaders deprecated
    * @return bool
    */
    public function isMobile($userAgent = null, $httpHeaders = null) {

        if($httpHeaders){ $this->setHttpHeaders($httpHeaders); }
        if($userAgent){ $this->setUserAgent($userAgent); }

        $this->setDetectionType('mobile');

        if ($this->checkHttpHeadersForMobile()) {
            return true;
        } else {
            return $this->matchDetectionRulesAgainstUA();
        }

    }

    /**
    * Check if the device is a tablet.
    * Return true if any type of tablet device is detected.
     *
     * @param null $userAgent deprecated
     * @param null $httpHeaders deprecated
     * @return bool
    */
    public function isTablet($userAgent = null, $httpHeaders = null) {

        $this->setDetectionType('mobile');

        foreach($this->tabletDevices as $_regex){
            if($this->match($_regex, $userAgent)){
                return true;
            }
        }

        return false;

    }

    /**
     * This method checks for a certain property in the
     * userAgent.
     * @todo: The httpHeaders part is not yet used.
     *
     * @param $key
     * @param string $userAgent deprecated
     * @param string $httpHeaders deprecated
     * @return bool|int|null
     */
    public function is($key, $userAgent = null, $httpHeaders = null){


        // Set the UA and HTTP headers only if needed (eg. batch mode).
        if($httpHeaders) $this->setHttpHeaders($httpHeaders);
        if($userAgent) $this->setUserAgent($userAgent);

        $this->setDetectionType('extended');

        return $this->matchUAAgainstKey($key);

    }

    public function getOperatingSystems(){

        return $this->operatingSystems;

    }

    /**
     * Some detection rules are relative (not standard),
     * because of the diversity of devices, vendors and
     * their conventions in representing the User-Agent or
     * the HTTP headers.
     *
     * This method will be used to check custom regexes against
     * the User-Agent string.
     *
     * @param $regex
     * @param string $userAgent
     * @return bool
     *
     * @todo: search in the HTTP headers too.
     */
    function match($regex, $userAgent=null){

        // Escape the special character which is the delimiter.
        $regex = str_replace('/', '\/', $regex);

        return (bool)preg_match('/'.$regex.'/is', (!empty($userAgent) ? $userAgent : $this->userAgent));

    }

    /**
     * Get the properties array.
     * @return array
     */
    function getProperties(){

        return $this->properties;

    }

    /**
     * Prepare the version number.
     *
     * @param $ver
     * @return int
     */
    function prepareVersionNo($ver){

        $ver = str_replace(array('_', ' ', '/'), array('.', '.', '.'), $ver);
        $arrVer = explode('.', $ver, 2);
        if(isset($arrVer[1])){
            $arrVer[1] = @str_replace('.', '', $arrVer[1]); // @todo: treat strings versions.
        }
        $ver = (float)implode('.', $arrVer);

        return $ver;

    }

    /**
     * Check the version of the given property in the User-Agent.
     * Will return a float number. (eg. 2_0 will return 2.0, 4.3.1 will return 4.31)
     *
     * @param string $propertyName
     * @return mixed $version
     */
    function version($propertyName, $type = 'text'){

        if(empty($propertyName)){ return false; }
        if( !in_array($type, array('text', 'float')) ){ $type = 'text'; }

        $properties = $this->getProperties();

        // Check if the property exists in the properties array.
        if( array_key_exists($propertyName, $properties) ){

            // Prepare the pattern to be matched.
            // Make sure we always deal with an array (string is converted).
            $properties[$propertyName] = (array)$properties[$propertyName];

            foreach($properties[$propertyName] as $propertyMatchString){

                $propertyPattern = str_replace('[VER]', self::VER, $propertyMatchString);

                // Escape the special character which is the delimiter.
                $propertyPattern = str_replace('/', '\/', $propertyPattern);

                // Identify and extract the version.
                preg_match('/'.$propertyPattern.'/is', $this->userAgent, $match);

                if(!empty($match[1])){
                    $version = ( $type == 'float' ? $this->prepareVersionNo($match[1]) : $match[1] );
                    return $version;
                }

            }

        }

        return false;

    }

    function mobileGrade(){

        $isMobile = $this->isMobile();

        if(
            // Apple iOS 3.2-5.1 - Tested on the original iPad (4.3 / 5.0), iPad 2 (4.3), iPad 3 (5.1), original iPhone (3.1), iPhone 3 (3.2), 3GS (4.3), 4 (4.3 / 5.0), and 4S (5.1)
            $this->version('iPad')>=4.3 ||
            $this->version('iPhone')>=3.1 ||
            $this->version('iPod')>=3.1 ||

            // Android 2.1-2.3 - Tested on the HTC Incredible (2.2), original Droid (2.2), HTC Aria (2.1), Google Nexus S (2.3). Functional on 1.5 & 1.6 but performance may be sluggish, tested on Google G1 (1.5)
            // Android 3.1 (Honeycomb)  - Tested on the Samsung Galaxy Tab 10.1 and Motorola XOOM
            // Android 4.0 (ICS)  - Tested on a Galaxy Nexus. Note: transition performance can be poor on upgraded devices
            // Android 4.1 (Jelly Bean)  - Tested on a Galaxy Nexus and Galaxy 7
            ( $this->version('Android')>2.1 && $this->is('Webkit') ) ||

            // Windows Phone 7-7.5 - Tested on the HTC Surround (7.0) HTC Trophy (7.5), LG-E900 (7.5), Nokia Lumia 800
            $this->version('Windows Phone OS')>=7.0 ||

            // Blackberry 7 - Tested on BlackBerry® Torch 9810
            // Blackberry 6.0 - Tested on the Torch 9800 and Style 9670
            $this->version('BlackBerry')>=6.0 ||
            // Blackberry Playbook (1.0-2.0) - Tested on PlayBook
            $this->match('Playbook.*Tablet') ||

            // Palm WebOS (1.4-2.0) - Tested on the Palm Pixi (1.4), Pre (1.4), Pre 2 (2.0)
            ( $this->version('webOS')>=1.4 && $this->match('Palm|Pre|Pixi') ) ||
            // Palm WebOS 3.0  - Tested on HP TouchPad
            $this->match('hp.*TouchPad') ||

            // Firefox Mobile (12 Beta) - Tested on Android 2.3 device
            ( $this->is('Firefox') && $this->version('Firefox')>=12 ) ||

            // Chrome for Android - Tested on Android 4.0, 4.1 device
            ( $this->is('Chrome') && $this->is('AndroidOS') && $this->version('Android')>=4.0 ) ||

            // Skyfire 4.1 - Tested on Android 2.3 device
            ( $this->is('Skyfire') && $this->version('Skyfire')>=4.1 && $this->is('AndroidOS') && $this->version('Android')>=2.3 ) ||

            // Opera Mobile 11.5-12: Tested on Android 2.3
            ( $this->is('Opera') && $this->version('Opera Mobi')>11 && $this->is('AndroidOS') ) ||

            // Meego 1.2 - Tested on Nokia 950 and N9
            $this->is('MeeGoOS') ||

            // Tizen (pre-release) - Tested on early hardware
            $this->is('Tizen') ||

            // Samsung Bada 2.0 - Tested on a Samsung Wave 3, Dolphin browser
            // @todo: more tests here!
            $this->is('Dolfin') && $this->version('Bada')>=2.0 ||

            // UC Browser - Tested on Android 2.3 device
            ( ($this->is('UC Browser') || $this->is('Dolfin')) && $this->version('Android')>=2.3 ) ||

            // Kindle 3 and Fire  - Tested on the built-in WebKit browser for each
            ( $this->match('Kindle Fire') ||
            $this->is('Kindle') && $this->version('Kindle')>=3.0 ) ||

            // Nook Color 1.4.1 - Tested on original Nook Color, not Nook Tablet
            $this->is('AndroidOS') && $this->is('NookTablet') ||

            // Chrome Desktop 11-21 - Tested on OS X 10.7 and Windows 7
            $this->version('Chrome')>=11 && !$isMobile ||

            // Safari Desktop 4-5 - Tested on OS X 10.7 and Windows 7
            $this->version('Safari')>=5.0 && !$isMobile ||

            // Firefox Desktop 4-13 - Tested on OS X 10.7 and Windows 7
            $this->version('Firefox')>=4.0 && !$isMobile ||

            // Internet Explorer 7-9 - Tested on Windows XP, Vista and 7
            $this->version('MSIE')>=7.0 && !$isMobile ||

            // Opera Desktop 10-12 - Tested on OS X 10.7 and Windows 7
            // @reference: http://my.opera.com/community/openweb/idopera/
            $this->version('Opera')>=10 && !$isMobile


        ){
            return 'A';
        }

        if(
            // Blackberry 5.0: Tested on the Storm 2 9550, Bold 9770
            $this->version('BlackBerry')>=5 && $this->version('BlackBerry')<6 ||

            //Opera Mini (5.0-6.5) - Tested on iOS 3.2/4.3 and Android 2.3
            ( $this->version('Opera Mini')>=5.0 && $this->version('Opera Mini')<=6.5 &&
            ($this->version('Android')>=2.3 || $this->is('iOS')) ) ||

            // Nokia Symbian^3 - Tested on Nokia N8 (Symbian^3), C7 (Symbian^3), also works on N97 (Symbian^1)
            $this->match('NokiaN8|NokiaC7|N97.*Series60|Symbian/3') ||

            // @todo: report this (tested on Nokia N71)
            $this->version('Opera Mobi')>=11 && $this->is('SymbianOS')

            ){
            return 'B';
        }

        if(
            // Blackberry 4.x - Tested on the Curve 8330
            $this->version('BlackBerry')<5.0 ||
            // Windows Mobile - Tested on the HTC Leo (WinMo 5.2)
            $this->match('MSIEMobile|Windows CE.*Mobile') || $this->version('Windows Mobile')<=5.2


        ){

            return 'C';

        }

        // All older smartphone platforms and featurephones - Any device that doesn't support media queries will receive the basic, C grade experience
        return 'C';


    }


}