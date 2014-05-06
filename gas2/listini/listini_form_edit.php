<?php

// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
}


//Mi assicuro che sia un numero

(int)$id_listini;

//MI assicuro che ci sia una ditta che corrisponda all'id
if(_USER_ID<>listino_proprietario($id_listini)){
    go("sommario",_USER_ID,"Listino non tuo");
}


$nome_listino = listino_nome($id_listini);


//se il comando ? quello di aggiungere la ditta
if($do=="mod"){

      $descrizione_listini=sanitize($descrizione_listini);
      $id_tipologie = CAST_TO_INT($id_tipologie,0);
      $is_privato = CAST_TO_INT($is_privato,0);
      $opz_usage = CAST_TO_INT($opz_usage,0);
       // se ? vuoto
      if (($descrizione_listini)==""){$msg.="Devi almeno inserire il nome del listino<br>";$e_empty++;};
      if (($id_tipologie)==""){$msg.="Devi associare una tipologia di merce<br>";$e_empty++;};
      if (($data_valido)==""){$msg.="Devi inserire la data di scadenza<br>";$e_empty++;};

      if (!controllodata($data_valido)){
        $e_logical ++;
        $msg.="Formato della data non riconosciuto<br>";
      };

      //SE E' SCADUTO
      if (gas_mktime($data_valido,null)<gas_mktime(date("d/m/Y"),date("H:i:s"))){
      $msg.="Data antecedente ad oggi<br>
             Se vuoi far scadere il listino dagli la data di oggi.";
      $e_logical ++;
      }


      $msg.="<br>Verifica i dati immessi e riprova";
      $e_total = $e_empty + $e_logical + $e_numerical;

      if($e_total==0){


        $data_app = $data_valido;
        $data_valido = conv_date_to_db($data_valido);
        // QUERY EDIT
        $sql = "UPDATE retegas_listini
              SET
              retegas_listini.descrizione_listini = '$descrizione_listini',
              retegas_listini.id_tipologie = '$id_tipologie',
              retegas_listini.data_valido = '$data_valido',
              retegas_listini.tipo_listino = '$tipo_listino',
              retegas_listini.is_privato ='$is_privato',
              retegas_listini.opz_usage ='$opz_usage'
              WHERE
              retegas_listini.id_listini = '$id_listini' LIMIT 1;";
        $data_valido=$data_app;
        $result = $db->sql_query($sql);
        //echo $result;
        //EDIT BEGIN ---------------------------------------------------------

        if (is_null($result)){
            $msg = "Errore nella modifica dei dati";
        }else{
            $msg = "Dati modificati";
        };

        go("listini_scheda",_USER_ID,$msg,"?id_listino=$id_listini");

        //EDIT END ---------------------------------------------------------

      } // se non ci sono errori

      //ci sono errori
      //Msg ? gi? settato


}else{
//altrimenti riempio i campi
$qry = "SELECT * FROM retegas_listini WHERE id_listini='$id_listini'";
$row = $db->sql_fetchrow($db->sql_query($qry));

$descrizione_listini = $row["descrizione_listini"];
$data_valido = conv_only_date_from_db($row["data_valido"]);
$id_tipologie = $row["id_tipologie"];
$tipo_listino = $row["tipo_listino"];
$is_privato = $row["is_privato"];
$opz_usage = $row["opz_usage"];

}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 2;
//Assegno il titolo che compare nella barra delle info
$r->title = "Modifica intestazione listino";
//Carico il
$r->javascripts_header[]=java_head_datetimepicker();
$r->javascripts[] = java_qtip(".retegas_form h5[title]");

//Messaggio popup;
//$r->messaggio = "Pagina di test";
//Dico quale menù orizzontale dovrà essere associato alla pagina.
$r->menu_orizzontale = "";

//Disegno il grafico
$r->javascripts[]=java_datepicker("datepicker");

$r->messaggio = $msg;
//Creo la pagina dell'aggiunta

$f = new rg_form();

$f->form_name="edit_listini";
//$f->form_action="listini_form_edit.php";

$t = new rg_form_text();
$t->number=1;
$t->name= "descrizione_listini";
$t->label="Nome Listino";
$t->help="Inserisci un nome di listino";
$t->size=50;
$t->value= $descrizione_listini;

$f->item[] = $t->create_form_text_item();
unset($t);

$t = new rg_form_text();
$t->number=2;
$t->name= "data_valido";
$t->label="Scadenza listino";
$t->help="Se inserisci una data di scadenza passata, su questo listino non potranno essere effettuati ordini.";
$t->size=10;
$t->id="datepicker";
$t->value= $data_valido;

$f->item[] = $t->create_form_text_item();
unset($t);

$t = new rg_form_select();
$t->number=3;
$t->name= "tipo_listino";
$t->value = $tipo_listino;
$t->label="Tipo di listino";
$t->help="Seleziona se ? un listino normale o magazzino";

$t->options[]=$t->create_option_item("Standard",0);
$t->options[]=$t->create_option_item("Magazzino",1);


$f->item[] = $t->create_form_select_item();
unset($t);

$t = new rg_form_select();
$t->number=4;
$t->name= "id_tipologie";
$t->value = $id_tipologie;
$t->label="Tipo di merce trattata";
$t->help="Categoria merceologica";

$res = $db->sql_query("SELECT * FROM retegas_tipologia");
while ($row = mysql_fetch_array($res)){
    $t->options[]=$t->create_option_item($row["descrizione_tipologia"],$row["id_tipologia"]);
}




$f->item[] = $t->create_form_select_item();
unset($t);


$t = new rg_form_select();
$t->number=5;
$t->name= "is_privato";
$t->value = $is_privato;
$t->label="Listino Pubblico o privato ?";
$t->help="Seleziona se ? un listino che si pu? vedere solo dal tuo GAS oppure da tutti";

$t->options[]=$t->create_option_item("Pubblico",0);
$t->options[]=$t->create_option_item("Privato",1);

$f->item[] = $t->create_form_select_item();
unset($t);

$t = new rg_form_select();
$t->number=6;
$t->name= "opz_usage";
$t->value = $opz_usage;
$t->label="Utilizzo campi OPZ";
$t->help="Scegli se sono destinati al raggruppamento o alla gestione TAG articoli";

//$t->options[]=$t->create_option_item("Raggruppamento",0);
$t->options[]=$t->create_option_item("Tag",1);

$f->item[] = $t->create_form_select_item();
unset($t);


$t = new rg_form_submit();
$t->number=7;
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

$form = $f->create_form();


//Questo ?? il contenuto della pagina
$r->contenuto = "<div class=\"rg_widget rg_widget_helper\">
                 <h3>Modifica il listino $nome_listino</h3>
                 ".$form."</div>";

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r)
?>