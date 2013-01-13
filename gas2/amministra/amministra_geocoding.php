<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if (!(_USER_PERMISSIONS & perm::puo_gestire_retegas)){
     pussa_via();
}

if($do=="do_geo"){
    $id_tabella = CAST_TO_INT($id_tabella,0);
    if($id_tabella>0){
        if($tabella_geo =="Utenti"){
            $res_geocode = geocode_users_table("SELECT * FROM maaking_users WHERE userid='$id_tabella';");
        }
        if($tabella_geo =="Ditte"){
            $res_geocode = geocode_ditte_table("SELECT * FROM retegas_ditte WHERE id_ditte='$id_tabella';");
        }
        if($tabella_geo =="Gas"){
            $res_geocode = geocode_gas_table("SELECT * FROM retegas_gas WHERE id_gas='$id_tabella';");
        }
    
    }
    $result = "<div class=\"ui-state-highlight ui-corner-all padding_6px\">
                $res_geocode
               </div>";
    
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Geocoding";


//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = amministra_menu_completo();



$r->messaggio = $msg;
//Creo la pagina dell'aggiunta

//Creo la pagina dell'aggiunta

$f = new rg_form();

$f->form_name="geo";


$t = new rg_form_select();
$t->number=1;
$t->name= "tabella_geo";
$t->value = $tabella_geo;
$t->label="Tabella da sistemare";
$t->help="";

//$a =  new rg_form_option_item("Standard",null,1,$tipo_listino);

$t->options[]=$t->create_option_item("Utenti","Utenti");
$t->options[]=$t->create_option_item("Ditte","Ditte");
$t->options[]=$t->create_option_item("Gas","Gas");

$f->item[] = $t->create_form_select_item();
unset($t);

$t = new rg_form_text();
$t->number=2;
$t->name= "id_tabella";
$t->label="ID  da aggiornare";
$t->help="";
$t->size=10;
$t->id="id_tabella";
$t->value= $id_tabella;

$f->item[] = $t->create_form_text_item();
unset($t);


$h = new rg_form_hidden();
$h->value="do_geo";
$h->name="do";
$f->item[]=$h->create_form_hidden_item();

$t = new rg_form_submit();
$t->number=3;
$t->name= "submit_form";
$t->label="...e infine";
$t->value= "Aggiorna le coordinate";

$f->item[] = $t->create_form_submit_item();
unset($t);



$form = $f->create_form();


//Questo ?? il contenuto della pagina
$r->contenuto = "<div class=\"rg_widget rg_widget_helper\">
                 <h3>Aggiorna Geocoding</h3>
                 $result
                 ".$form."</div>";

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>