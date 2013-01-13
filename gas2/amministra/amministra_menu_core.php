<?php
  if (eregi("amministra_menu_core.php", $_SERVER['SCRIPT_NAME'])) {
	Header("Location: ../index.php"); die();
}

// $h_table = Content
// $id_user = utente
// $id = ordine

$_parenDir_path = join(array_slice(split( "/" ,dirname($_SERVER['PHP_SELF'])),0,-1),"/").'/'; // returns the full path to the parent dir
$_parenDir =  basename ($_parenDir_path,"/"); // returns only the name of the pare

if($_parenDir=="amministra"){
	$pa = "../";
}else{
	$pa="";
}

$h_menu ='
<div style="padding-bottom:2em;">
<ul class="sf-menu">';  // INIZIO



// ------------------------------------------------MIO GAS
if ($my_user_level>0){
$h_menu .='<li><a class="medium green awesome"><b>LOG</b></a>'; 
$h_menu .='<ul>';
	 
	 $h_menu .='<li><a class="medium green awesome" href="'.$pa.'amministra_messaggi.php?do=all" target="_self">Tutti</a></li>';
	 $h_menu .='<li><a class="medium green awesome" href="'.$pa.'amministra_messaggi.php?do=lis" target="_self">Listini</a></li>';
	 $h_menu .='<li><a class="medium green awesome" href="'.$pa.'amministra_messaggi.php?do=cro" target="_self">CronJob</a></li>';
	 $h_menu .='<li><a class="medium green awesome" href="'.$pa.'amministra_messaggi.php?do=art" target="_self">Articoli</a></li>';
	 $h_menu .='<li><a class="medium green awesome" href="'.$pa.'amministra_messaggi.php?do=ema" target="_self">Posta Auto</a></li>';
	 $h_menu .='<li><a class="medium green awesome" href="'.$pa.'amministra_messaggi.php?do=emm" target="_self">Posta Manu</a></li>';
	 
$h_menu .='</ul>';
$h_menu .='</li>'; 
}
// ------------------------------------------------FINE MIO GAS
// ------------------------------------------------USERS
if ($my_user_level>0){
$h_menu .='<li><a class="medium red awesome"><b>USERS</b></a>'; 
$h_menu .='<ul>';
	 
	 $h_menu .='<li><a class="medium red awesome" href="'.$pa.'amministra_users.php?do=not_act" target="_self">Attesa Attivazione</a></li>';
	 $h_menu .='<li><a class="medium red awesome" href="'.$pa.'amministra_users.php?do=all" target="_self">Tutti</a></li>';
	 $h_menu .='<li><a class="medium red awesome" href="'.$pa.'amministra_users.php?do=last_act" target="_self">Attivi di recente</a></li>';
	 //$h_menu .='<li><a class="medium green awesome" href="'.$pa.'amministra_messaggi.php?do=cro" target="_self">CronJob</a></li>';
	 //$h_menu .='<li><a class="medium green awesome" href="'.$pa.'amministra_messaggi.php?do=art" target="_self">Articoli</a></li>';
	 //$h_menu .='<li><a class="medium green awesome" href="'.$pa.'amministra_messaggi.php?do=ema" target="_self">Posta Auto</a></li>';
	 //$h_menu .='<li><a class="medium green awesome" href="'.$pa.'amministra_messaggi.php?do=emm" target="_self">Posta Manu</a></li>';
	 
$h_menu .='</ul>';
$h_menu .='</li>'; 
}
// ------------------------------------------------FINE MIO GAS

// ------------------------------------------------DB
if ($my_user_level>0){
$h_menu .='<li><a class="medium yellow awesome"><b>DB</b></a>'; 
$h_menu .='<ul>';
	 
	 $h_menu .='<li><a class="medium yellow awesome" href="'.$pa.'amministra_relazioni_db.php?do=lis" target="_self">Listini Orfani</a></li>';
	 $h_menu .='<li><a class="medium yellow awesome" href="'.$pa.'amministra_relazioni_db.php?do=art" target="_self">Articoli Orfani</a></li>';
	 $h_menu .='<li><a class="medium yellow awesome" href="'.$pa.'amministra_relazioni_db.php?do=ami" target="_self">Amici Orfani</a></li>';
	 $h_menu .='<li><a class="medium yellow awesome" href="'.$pa.'amministra_relazioni_db.php?do=dis" target="_self">Distribuzione Orfani</a></li>';
	 $h_menu .='<li><a class="medium yellow awesome" href="'.$pa.'amministra_relazioni_db.php?do=det" target="_self">Dettagli Orfani</a></li>';
	 $h_menu .='<li><a class="medium yellow awesome" href="'.$pa.'amministra_relazioni_db.php?do=ref" target="_self">Referenze orfane</a></li>'; 
	 
$h_menu .='</ul>';
$h_menu .='</li>'; 
}
// ------------------------------------------------FINE DB

// ------------------------------------------------DB
if ($my_user_level==5){
$h_menu .='<li><a class="medium blue awesome"><b>Options</b></a>'; 
$h_menu .='<ul>';
	 
	 $h_menu .='<li><a class="medium blue awesome" href="'.$pa.'amministra_statistiche.php?do=m_ON" target="_self">Mailer ON</a></li>';
	 $h_menu .='<li><a class="medium blue awesome" href="'.$pa.'amministra_statistiche.php?do=m_OFF" target="_self">Mailer OFF</a></li>';
	 $h_menu .='<li><a class="medium blue awesome" href="'.$pa.'amministra_statistiche.php?do=geocode" target="_self">Geocode</a></li>';
	 $h_menu .='<li><a class="medium blue awesome" href="'.$pa.'amministra_statistiche.php?do=d_ON" target="_self">Debug ON</a></li>';
	 $h_menu .='<li><a class="medium blue awesome" href="'.$pa.'amministra_statistiche.php?do=d_OFF" target="_self">Debug OFF</a></li>';
     $h_menu .='<li><a class="medium blue awesome" href="'.$pa.'amministra_options_table.php" target="_self">OPZIONI</a></li>';
	
$h_menu .='</ul>';
$h_menu .='</li>'; 
}
// ------------------------------------------------FINE DB


//---------------------------------------ORDINI
if ($my_user_level==5){
$h_menu .='<li><a class="medium beige awesome"><b>Ordini</b></a>'; 
$h_menu .='<ul>';
	 
	 $h_menu .='<li><a class="medium beige awesome" href="'.$pa.'amministra_ordini.php" target="_self">Tutti</a></li>';
     $h_menu .='<li><a class="medium beige awesome" href="'.$pa.'../ordini/storici/storici_ordini_miei.php" target="_self">Storici miei ordini</a></li>';
 
$h_menu .='</ul>';
$h_menu .='</li>'; 
}
// ------------------------------------------------FINE DB


$h_menu .=''; 
$h_menu .=''; 
$h_menu .='</ul>
		   </div>
		   <br />  
			';                // FINE





$h_table=$h_menu;


?> 