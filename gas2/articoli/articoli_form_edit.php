<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    


//Mi assicuro che sia un numero

(int)$id_articoli;

//MI assicuro che l'articolo sia in un suo listino
if (id_listino_user(articolo_id_listino($id_articoli))<>_USER_ID){
    go("sommario",_USER_ID,"Articolo di un listino non tuo");
}

if(articoli_in_ordine($id_articoli)<>0){
    go("sommario",_USER_ID,"Articolo già usato in un ordine");
}



//se il comando è quello di aggiungere la ditta
if($do=="mod"){
      
      //PULIZIA
      $codice               = sanitize($codice);
      $u_misura             = sanitize($u_misura);
      $misura               = CAST_TO_FLOAT($misura,0);
      $descrizione_articoli = sanitize($descrizione_articoli);
      $qta_scatola          = CAST_TO_FLOAT($qta_scatola,0);
      $prezzo               = CAST_TO_FLOAT($prezzo,0);
      $ingombro             = sanitize($ingombro);
      $qta_minima           = CAST_TO_FLOAT($qta_minima,0);
      $articoli_note        = sanitize($articoli_note);
      $articoli_unico       = CAST_TO_INT($articoli_unico,0,1);
      $articoli_opz_1       = sanitize($articoli_opz_1);
      $articoli_opz_2       = sanitize($articoli_opz_2);
      $articoli_opz_3       = sanitize($articoli_opz_3);
      
      // se è vuoto
      if (is_empty($codice)     OR
          is_empty($u_misura)   OR
          is_empty($misura)     OR
          is_empty($descrizione_articoli) OR
          is_empty($qta_scatola)OR
          is_empty($prezzo)     OR
          is_empty($qta_minima))
          {$msg.="Hai lasciato vuoto qualche campo di troppo;<br>";$e_empty++;};
      // se è a zero
      if (  $misura==0 OR
            $qta_scatola==0 OR
            $qta_minima==0 OR
            $prezzo ==0)
      {$msg.="Hai lasciato a zero qualche campo di troppo;<br>";$e_empty++;}
      // Altro
      if ( $qta_minima>$qta_scatola)
      {$msg.="La quantità di multiplo non può essere superiore alla scatola;<br>";$e_logical++;}
      if (!is_multiplo($qta_minima,$qta_scatola))
      {$msg.="Devi fare in modo che la quantità scatola sia divisibile per il multiplo minimo.<br>";$e_logical++;};
  
      
      
      $msg.="<br>Verifica i dati immessi e riprova";
      $e_total = $e_empty + $e_logical + $e_numerical;
      
      if($e_total==0){

        // QUERY EDIT
        $sql = "UPDATE retegas_articoli 
                SET 
                codice = '$codice',
                u_misura = '$u_misura',
                misura = '$misura',
                descrizione_articoli = '$descrizione_articoli',
                qta_scatola = '$qta_scatola ',
                prezzo = '$prezzo',
                ingombro = '$ingombro',
                qta_minima = '$qta_minima',
                articoli_note = '$articoli_note',
                articoli_unico = '$articoli_unico',
                articoli_opz_1 = '$articoli_opz_1',
                articoli_opz_2 = '$articoli_opz_2',
                articoli_opz_3 = '$articoli_opz_3' 
                WHERE 
                id_articoli = '$id_articoli' LIMIT 1;";

        //EDIT BEGIN ---------------------------------------------------------
        $result = $db->sql_query($sql); 
        if (is_null($result)){
            $msg = "Errore nella modifica dei dati";
        }else{
            $msg = "Dati modificati";
        };
        
        
        go("listini_scheda",_USER_ID,$msg,"?id_listino=$id_listini");
        
        //EDIT END --------------------------------------------------------- 
              
      } // se non ci sono errori
    
      //ci sono errori
      //Msg è già settato
  
    
}else{
//altrimenti riempio i campi
$sql = "SELECT * FROM retegas_articoli WHERE id_articoli='$id_articoli'"; 
 extract($db->sql_fetchrow($db->sql_query($sql)));    
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = menu_lat::anagrafiche;
//Assegno il titolo che compare nella barra delle info
$r->title = "Modifica anagrafica articolo";
//Carico il 
//$r->javascripts_header[]=java_head_datetimepicker();
$r->javascripts[] = java_qtip(".retegas_form h5[title]");

//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menÃ¹ orizzontale dovrÃ  essere associato alla pagina.
$r->menu_orizzontale = "";


$r->messaggio = $msg;
//Creo la pagina dell'aggiunta

$f = new rg_form();

$f->form_name="edit_articolo";

$t = new rg_form_text();
$t->number=1;
$t->name= "codice";
$t->label="Codice articolo del fornitore";
$t->help="Serve ad identificare l'articolo presso il fornitore";
$t->size=50;
$t->value= $codice;

$f->item[] = $t->create_form_text_item();
unset($t);

$t = new rg_form_text();
$t->number=2;
$t->name= "descrizione_articoli";
$t->label="La descrizione dell'articolo";
$t->help="Sarebbe opportuna una descrizione semplice ma esaustiva.";
$t->size=50;
$t->value= $descrizione_articoli;

$f->item[] = $t->create_form_text_item();
unset($t);

$t = new rg_form_text();
$t->number=3;
$t->name= "u_misura";
$t->label="Unità di misura.";
$t->help="(Kg, Gr. Mt, Km, N. Scatole, Barattoli, Sacchetti, Bidoni, ecc ecc, cioè tutto quello che serve ad identificare una misura.";
$t->size=10;
$t->value= $u_misura;

$f->item[] = $t->create_form_text_item();
unset($t);

$t = new rg_form_text();
$t->number=4;
$t->name= "misura";
$t->label="La misura associatà all'unità";
$t->help="L'unità di misura e la misura costituiscono l'unità di vendita.";
$t->size=10;
$t->value= $misura;

$f->item[] = $t->create_form_text_item();
unset($t);

$t = new rg_form_text();
$t->number=5;
$t->name= "prezzo";
$t->label="Il prezzo di UNA unità di vendita.";
$t->help="";
$t->size=10;
$t->value= $prezzo;

$f->item[] = $t->create_form_text_item();
unset($t);

$t = new rg_form_text();
$t->number=6;
$t->name= "ingombro";
$t->label="Note brevi";
$t->help="";
$t->size=50;
$t->value= $ingombro;
$f->item[] = $t->create_form_text_item();
unset($t);

$t = new rg_form_text();
$t->number=7;
$t->name= "qta_scatola";
$t->label="Quantità in una scatola";
$t->help="La scatola raggruppa n unità di vendita";
$t->size=10;
$t->value= $qta_scatola;
$f->item[] = $t->create_form_text_item();
unset($t);

$t = new rg_form_text();
$t->number=8;
$t->name= "qta_minima";
$t->label="Quantità di multiplo";
$t->help="La quantità multiplo rappresenta il minimo di articoli in cui può essere frazionata una scatola";
$t->size=10;
$t->value= $qta_minima;
$f->item[] = $t->create_form_text_item();
unset($t);

$t = new rg_form_textarea();
$t->number=9;
$t->name= "articoli_note";
$t->class = "ckeditor";
$t->label="Note";
$t->help="";
$t->value= $articoli_note;
$f->item[] = $t->create_form_textarea_item();
unset($t);

$t = new rg_form_select();
$t->number=9;
$t->name= "articoli_unico";
$t->value = $articoli_unico;
$t->label="Selezionare se l'articolo è raggruppabile";
$t->help="Seleziona se è un listino che si può vedere solo dal tuo GAS oppure da tutti";

$t->options[]=$t->create_option_item("Raggruppabile",0);
$t->options[]=$t->create_option_item("Univoco",1);

$f->item[] = $t->create_form_select_item();
unset($t);

$t = new rg_form_text();
$t->number=10;
$t->name= "articoli_opz_1";
$t->label="Variante 1";
$t->help="";
$t->size=50;
$t->value= $articoli_opz_1;
$f->item[] = $t->create_form_text_item();
unset($t);

$t = new rg_form_text();
$t->number=11;
$t->name= "articoli_opz_2";
$t->label="Variante 2";
$t->help="";
$t->size=50;
$t->value= $articoli_opz_2;
$f->item[] = $t->create_form_text_item();
unset($t);

$t = new rg_form_text();
$t->number=12;
$t->name= "articoli_opz_3";
$t->label="Variante 3";
$t->help="";
$t->size=50;
$t->value= $articoli_opz_3;
$f->item[] = $t->create_form_text_item();
unset($t);


$t = new rg_form_submit();
$t->number=13;
$t->name= "submit_form";
$t->label="...e infine";
$t->value= "Salva le modifiche";

$f->item[] = $t->create_form_submit_item();
unset($t);

$h = new rg_form_hidden();
$h->name="do";
$h->value="mod";
$f->item[] =$h->create_form_hidden_item(); 
unset($h);

$h = new rg_form_hidden();
$h->name="id_listini";
$h->value=$id_listini;
$f->item[] =$h->create_form_hidden_item(); 
unset($h);

$h = new rg_form_hidden();
$h->name="id_articoli";
$h->value=$id_articoli;
$f->item[] =$h->create_form_hidden_item(); 

$form = $f->create_form();


//Questo è¨ il contenuto della pagina
$r->contenuto = "<div class=\"rg_widget rg_widget_helper\">
                 <h3>Modifica questo articolo</h3>
                 $form</div>";
$r->javascripts_header[] = java_head_ckeditor();
//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>