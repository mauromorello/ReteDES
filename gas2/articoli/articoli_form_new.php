<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("articoli_renderer.php");
include_once ("../retegas.class.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

(int)$id_articolo;

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = 1;
//Assegno il titolo che compare nella barra delle info
$r->title = "Scheda articolo";

$r->menu_orizzontale = articoli_menu_completo($id_articolo);

$r->messaggio = $msg;
//Creo la pagina dell'aggiunta

$r->contenuto =  schedina_articolo($id_articolo,true);

echo $r->create_retegas();

//Distruggo l'oggetto r    
unset($r)   
?>