<?php
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLO SE HA PARTECIPATO
if (n_articoli_ordini_user(_USER_ID,$id_ordine)==0){
     pussa_via();
}

if(!is_printable_from_id_ord($id_ordine)){
    go("ordini_form",_USER_ID,"Ordine NON ancora convalidato.","?id_ordine=".$id_ordine);
}

$value=CAST_TO_INT($value,0,5);

if($tipo=="qualita"){
   if(($value>0)){
        write_option_opinione_ordine($id_ordine,_USER_ID,opinioni::qualita,CAST_TO_INT($value,1,5));
        echo "Registrato il tuo voto: <span style=\"font-size:1.1em;font-weight:bold\">$value</span>.";
        die(); 
   }else{
        delete_option_opinione_ordine($id_ordine,_USER_ID,opinioni::qualita);
        echo "Cancellato !";
        die(); 
    }
     
}

if($tipo=="affare"){
    if($value>0){
        write_option_opinione_ordine($id_ordine,_USER_ID,opinioni::affare,CAST_TO_INT($value,1,5));
        echo "Registrato il tuo voto: <span style=\"font-size:1.1em;font-weight:bold\">$value</span>.";
        die();
    }else{
        delete_option_opinione_ordine($id_ordine,_USER_ID,opinioni::affare);
        echo "Cancellato !";
        die();
    }
}

if($do=="do_opinione"){
    
    
    bacheca_write_commento_ordine_partecipante(_USER_ID,$id_ordine,$titolo,$commento);
    $msg = "OK";
    if($submit<>"Salva il tuo commento (e torna alla home)"){
        go("ordini_form",_USER_ID,$msg,"?id_ordine=$id_ordine");
    }else{
        go("sommario",_USER_ID,$msg,null);
    }
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Opinione di partecipante";
$r->javascripts_header[]=java_head_rateit();
$r->javascripts_header[]=java_head_ckeditor();


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);

//Assegno le due tabelle a tablesorter

$opi_affare = CAST_TO_INT(read_option_opinione_ordine($id_ordine,_USER_ID,opinioni::affare),0,5);
$opi_qualita = CAST_TO_INT(read_option_opinione_ordine($id_ordine,_USER_ID,opinioni::qualita),0,5);

//QUALITA
$r->javascripts[]='<script type="text/javascript">
     $(\'.rateit\').bind(\'rated reset\', function (e) {
         var ri = $(this);
 
         var value = ri.rateit(\'value\');
         var productID = ri.data(\'productid\'); // if the product id was in some hidden field: ri.closest(\'li\').find(\'input[name="productid"]\').val()
 
         $.ajax({
             url: \'\', //your server side script
             data: { tipo: productID, value: value, id_ordine: '.$id_ordine.' }, //our data
             type: \'POST\',
             success: function (data) {
                 
                 if(productID == \'affare\'){
                    $(\'#affare_text\').html(data);
                 }
                 if(productID == \'qualita\'){
                    $(\'#qualita_text\').html(data);
                 }
                 
             },
             error: function (jxhr, msg, err) {
                 //$(\'#response\').append(\'<li style="color:red">\' + msg + \'</li>\');
             }
         });
     });
   
   </script>';


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
if($msg) $r->messaggio = $msg;

//istruzioni
$i="<p>Le opinioni inserite dagli utenti servono a poter valutare i fornitori.</p>
    <p>Si può valutare la qualità della merce e la sensazione del rapporto qualità/prezzo, cliccando sulle stelline.</p>
    <p>Si può inserire un commento a piacere. I commenti NON sono anonimi, e possono essere letti da tutti gli utenti iscritti a retedes.</p>
    <p>Per cancellare un commento salvare la schermata lasciando il titolo del commento vuoto.</p>
    ";
$i = rg_toggable("ISTRUZIONI","istr",$i,false);    

//Contenuto

$h .= "<div class=\"rg_widget rg_widget_helper\">";

$h .= "";
$h .= "<table><tr><td style=\"width:50%;vertical-align:top;\">";
$h .= "<h4>Condividi le tue opinioni,<br> cliccando sulle stelline</h4>
        <span>Qualità della merce</span>
        <div style=\"height:4em; font-size:1.1em;\">
            <div    id=\"qualita\" 
                    data-rateit-step=\"1\" 
                    data-rateit-value=\"$opi_qualita\" 
                    data-productid=\"qualita\" 
                    class=\"rateit\">
            </div><br> 
            <span id=\"qualita_text\" class=\"small_link\"></span><br>
            
       </div>";
$h .= " <span>Rapporto Qualità/Prezzo</span>
        <div style=\"height:4em; font-size:1.1em\">
            <div    id=\"affare\" 
                    data-rateit-step=\"1\" 
                    data-rateit-value=\"$opi_affare\" 
                    data-productid=\"affare\" 
                    class=\"rateit\">
            </div><br>
            <span id=\"affare_text\" class=\"small_link\"></span>
       </div>";
$h .= "</td><td style=\"width:50%;vertical-align:top;\">";
$h .='  
        <h4>Scrivi un commento (Basta anche solo il titolo)</h4>
        <form class="" method="POST" action="">
        <label for="titolo">Titolo</label>
        <input style="font-size:1.2em;padding:4px;margin:10px;" type="text" id="titolo" name="titolo" size="50" value="'.bacheca_read_titolo_commento_ordine_partecipante(_USER_ID,$id_ordine).'">
        <br>
        <textarea type="text" id="commento" class="ckeditor" name="commento">'.bacheca_read_commento_ordine_partecipante(_USER_ID,$id_ordine).'</textarea>
        <input type="hidden" name="id_ordine" value="'.$id_ordine.'">
        <input type="hidden" name="do" value="do_opinione"><br>
        <input type="submit" class="awesome large green" name="submit" value="Salva il tuo commento (e torna all\'ordine)">      
        <input type="submit" class="awesome large green" name="submit" value="Salva il tuo commento (e torna alla home)">      
        </form>
      ';                    
$h .= "</td></tr></table>";       
$h .= "";
$h .= "</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = schedina_ordine($id_ordine).$i.$h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);