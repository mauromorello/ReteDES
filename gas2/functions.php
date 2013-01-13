<?php


if (preg_match('/functions.php/i',$_SERVER['SCRIPT_NAME'])){ 
	Header("Location: index.php"); die();
}


function _clean($str){ 
return is_array($str) ? array_map('_clean', $str) : str_replace("\\", "\\\\", htmlspecialchars((get_magic_quotes_gpc() ? stripslashes($str) : $str), ENT_QUOTES)); 
}


// Disable magic_quotes_runtime
if(get_magic_quotes_runtime())
{
	// Deactivate
	set_magic_quotes_runtime(false);
}



if (!ini_get("register_globals")) {
	import_request_variables('GPC');
}

$phpver = phpversion();
if ($phpver < '4.1.0') {
	$_GET = $HTTP_GET_VARS;
	$_POST = $HTTP_POST_VARS;
	$_SERVER = $HTTP_SERVER_VARS;
}
$phpver = explode(".", $phpver);
$phpver = "$phpver[0]$phpver[1]";
if ($phpver >= 41) {
	$PHP_SELF = $_SERVER['PHP_SELF'];
}


if(isset($user)){
$user = base64_decode($user);
$user = addslashes($user);
$user = base64_encode($user);
}


_clean($_POST);
_clean($_GET);
//_clean($_REQUEST);// and so on..

foreach ($_GET as $sec_key => $secvalue) {
    
    if(is_array($secvalue)){
         foreach ($secvalue as $thirdvalue) {
         //echo "GET: Third:".$thirdvalue."<br>";
         if ((eregi("<[^>]*script*\"?[^>]*>", $thirdvalue)) ||
                (eregi("<[^>]*object*\"?[^>]*>", $thirdvalue)) ||
                (eregi("<[^>]*iframe*\"?[^>]*>", $thirdvalue)) ||
                (eregi("<[^>]*applet*\"?[^>]*>", $thirdvalue)) ||
                (eregi("<[^>]*meta*\"?[^>]*>", $thirdvalue)) ||
                (eregi("<[^>]*style*\"?[^>]*>", $thirdvalue)) ||
                (eregi("<[^>]*form*\"?[^>]*>", $thirdvalue)) ||
                (eregi("<[^>]*img*\"?[^>]*>", $thirdvalue)) ||
                (eregi("<[^>]*onmouseover*\"?[^>]*>", $thirdvalue)) ||
                (eregi("\([^>]*\"?[^)]*\)", $thirdvalue)) ||
                (eregi("\"", $thirdvalue))) {
                    die ("not allowed");
                }
         }
    }else{
    
	if ((eregi("<[^>]*script*\"?[^>]*>", $secvalue)) ||
	(eregi("<[^>]*object*\"?[^>]*>", $secvalue)) ||
	(eregi("<[^>]*iframe*\"?[^>]*>", $secvalue)) ||
	(eregi("<[^>]*applet*\"?[^>]*>", $secvalue)) ||
	(eregi("<[^>]*meta*\"?[^>]*>", $secvalue)) ||
	(eregi("<[^>]*style*\"?[^>]*>", $secvalue)) ||
	(eregi("<[^>]*form*\"?[^>]*>", $secvalue)) ||
	(eregi("<[^>]*img*\"?[^>]*>", $secvalue)) ||
	(eregi("<[^>]*onmouseover*\"?[^>]*>", $secvalue)) ||
	(eregi("\([^>]*\"?[^)]*\)", $secvalue)) ||
	(eregi("\"", $secvalue))) {
		die ("not allowed");
	}
    }
}
foreach ($_POST as $secvalue) {
	//echo "POST: Sec: ".$secvalue."<br>";
	if(is_array($secvalue)){
		 foreach ($secvalue as $thirdvalue) {
			 //echo "POST: Third:".$thirdvalue."<br>";
		 if ((eregi("<[^>]*onmouseover*\"?[^>]*>", $thirdvalue)) ||
				(eregi("<[^>]script*\"?[^>]*>", $thirdvalue)) ||
				(eregi("<[^>]meta*\"?[^>]*>", $thirdvalue)) ||
				(eregi("<[^>]style*\"?[^>]*>", $thirdvalue))) {
				die ("not allowed");
			}
		 }
	}else{
		if ((eregi("<[^>]*onmouseover*\"?[^>]*>", $secvalue)) ||
			(eregi("<[^>]script*\"?[^>]*>", $secvalue)) ||
			(eregi("<[^>]meta*\"?[^>]*>", $secvalue)) ||
			(eregi("<[^>]style*\"?[^>]*>", $secvalue))) {
			die ("not allowed");
		}
	}
}

//set root path
$ROOT_DIR = realpath(dirname(__FILE__));
$ROOT_DIR = str_replace('\\', '/', $ROOT_DIR);

define("_ROOOT_",$ROOT_DIR);





include ("$ROOT_DIR/config.php");
include ("$ROOT_DIR/mysql.class.php");
//include ("$ROOT_DIR/lang/italian.php");
include ("$ROOT_DIR/function_engine/structures.class.php"); 


if ($php_debug=="ON"){    
    error_reporting(E_ERROR | E_WARNING | E_PARSE);       
}else{
	//echo "OFF";   
	error_reporting(0);
}


$db = new sql_db($db_host, $db_username, $db_password, $databse_name, false);
if(!$db->db_connect_id) {
	  

	  echo "<br><font color=\"red\"><h5><br><center>Error:</b><br><hr><br>
			<b>Il database è morto<br>
			chiama l'assistenza</center><hr>";
			echo mysql_error();

	  die();
}



$site_name= "ReteDES.it";              // stripslashes($options['site_name']);
$site_email= _SITE_MAIL_LOG;    //stripslashes($options['site_email']);

//-----------------------------------------------MENU'
if(in_array("menu",$_FUNCTION_LOADER)){
    include ("function_engine/fun_menu.php");
}
//----------------------------------------------- OPZIONI
if(in_array("options",$_FUNCTION_LOADER)){
    include ("function_engine/fun_options.php");
}
//----------------------------------------------- WIDGETS
if(in_array("widgets",$_FUNCTION_LOADER)){
    include ("function_engine/fun_widgets.php");
}
//----------------------------------------------- DES
if(in_array("des",$_FUNCTION_LOADER)){
    include ("function_engine/fun_des.php");
}
// ----------------------------------------------- GRAFICI
if(in_array("gphpchart",$_FUNCTION_LOADER)){
    include_once("lib/graph/GphpChart.class.php");
}
// ----------------------------------------------- POSTA
if(in_array("swift",$_FUNCTION_LOADER)){
    require_once "lib/Swift-4.1.6/lib/swift_required.php";
}    
if(in_array("posta",$_FUNCTION_LOADER)){
    include ("function_engine/fun_posta.php");
}
//----------------------------------------------AMICI
if(in_array("amici",$_FUNCTION_LOADER)){
    include ("function_engine/fun_amici.php");
}
//----------------------------------------------AIUTANTI
if(in_array("aiutanti",$_FUNCTION_LOADER)){
  //  include ("function_engine/fun_aiutanti.php");
}
//----------------------------------------------USERS
if(in_array("users",$_FUNCTION_LOADER)){
    include ("function_engine/fun_users.php");
}
//-----------------------------------------------GAS
if(in_array("gas",$_FUNCTION_LOADER)){
    include ("function_engine/fun_gas.php");
}
//-----------------------------------------------LISTINI
if(in_array("listini",$_FUNCTION_LOADER)){
    include ("function_engine/fun_listini.php"); 
}
//-----------------------------------------------DITTE
if(in_array("ditte",$_FUNCTION_LOADER)){
    include ("function_engine/fun_ditte.php");
} 
//----------------------------------------------TIPOLOGIE
if(in_array("tipologie",$_FUNCTION_LOADER)){
    include ("function_engine/fun_tipologie.php");
}
//----------------------------------------------ARTICOLI
if(in_array("articoli",$_FUNCTION_LOADER)){
    include ("function_engine/fun_articoli.php");
}
//----------------------------------------------GRAFICI
if(in_array("graphics",$_FUNCTION_LOADER)){
    include ("function_engine/fun_graphics.php");
}
//---------------------------------------------ORDINI
if(in_array("ordini",$_FUNCTION_LOADER)){
    include ("function_engine/fun_ordini.php");
}
//---------------------------------------------ORDINI CALCOLI
if(in_array("ordini_valori",$_FUNCTION_LOADER)){
    include ("function_engine/fun_ordini_valori.php");
}
//----------------------------------------------BACHECA
if(in_array("bacheca",$_FUNCTION_LOADER)){
    include ("function_engine/fun_bacheca.php");
}
//-------------------------------------------DATA CHECK
if(in_array("data_check",$_FUNCTION_LOADER)){
    include ("function_engine/fun_data_check.php");
}
//--------------------------------------------RENDERING
if(in_array("rendering",$_FUNCTION_LOADER)){
    include ("function_engine/fun_rendering.php");
}
//--------------------------------------------Geocoding
if(in_array("geocoding",$_FUNCTION_LOADER)){
    include ("function_engine/fun_geocoding.php");
}
//------------------------------------------ADMINISTRATION
if(in_array("admin",$_FUNCTION_LOADER)){
    include ("function_engine/fun_admin.php");
}
//------------------------------------------DAREAVERE
if(in_array("dareavere",$_FUNCTION_LOADER)){
    include ("function_engine/fun_dareavere.php");
}
//------------------------------------------CASSA
if(in_array("cassa",$_FUNCTION_LOADER)){
    include ("function_engine/fun_cassa.php");
}
//------------------------------------------VARIE
if(in_array("varie",$_FUNCTION_LOADER)){
    include ("function_engine/fun_varie.php");
}
//------------------------------------------OPINIONI
if(in_array("opinioni",$_FUNCTION_LOADER)){
    include ("function_engine/fun_opinioni.php");
}

//------------------------------------------THEMING
if(in_array("theming",$_FUNCTION_LOADER)){
    include ("function_engine/fun_theming.php");
}

//------------------------------------------MOBILE
if(in_array("mobile",$_FUNCTION_LOADER)){
    include ("function_engine/fun_mobile.php");
}

//------------------------------------------TWITTER
if(in_array("twitter",$_FUNCTION_LOADER)){
    include ("function_engine/fun_twitter.php");
    include ("lib/o_auth/tmhOAuth.php");
}

//DEBUG
class debugs{

public $debug_state;
public $debug_msg;
public $debug_start;

public function __construct() {

	  $this->debug_state = read_option_text(0,"DEBUG");
	  $this->debug_start = array_sum(explode(' ', microtime()));;
	   

   }
	
public function render_debug(){

	unset($h_d);
	
foreach ($this->debug_msg as $v) {
	$h_d .='<div class="sub_debug">'.$v.'</div>';
}

return $h_d;
}
	
}

