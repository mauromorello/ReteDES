<?php
   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLO SE HA PARTECIPATO
if (id_referente_ordine_globale($id_ordine)<>_USER_ID){
     pussa_via();
}

if(!is_printable_from_id_ord($id_ordine)){
    pussa_via();
}

$value=CAST_TO_INT($value,0,5);

if($tipo=="logistica"){
   if(($value>0)){
        write_option_opinione_ordine($id_ordine,_USER_ID,opinioni::logistica,CAST_TO_INT($value,1,5));
        echo "Registrato il tuo voto: <span style=\"font-size:1.1em;font-weight:bold\">$value</span>.";
        die(); 
   }else{
        delete_option_opinione_ordine($id_ordine,_USER_ID,opinioni::logistica);
        echo "Cancellato !";
        die(); 
    }
     
}
if($tipo=="rapporto"){
   if(($value>0)){
        write_option_opinione_ordine($id_ordine,_USER_ID,opinioni::rapporti,CAST_TO_INT($value,1,5));
        echo "Registrato il tuo voto: <span style=\"font-size:1.1em;font-weight:bold\">$value</span>.";
        die(); 
   }else{
        delete_option_opinione_ordine($id_ordine,_USER_ID,opinioni::rapporti);
        echo "Cancellato !";
        die(); 
    }
     
}
if($tipo=="velocita"){
   if(($value>0)){
        write_option_opinione_ordine($id_ordine,_USER_ID,opinioni::velocita,CAST_TO_INT($value,1,5));
        echo "Registrato il tuo voto: <span style=\"font-size:1.1em;font-weight:bold\">$value</span>.";
        die(); 
   }else{
        delete_option_opinione_ordine($id_ordine,_USER_ID,opinioni::velocita);
        echo "Cancellato !";
        die(); 
    }
     
}

if($do=="do_opinione"){

    bacheca_write_commento_ordine_referente(_USER_ID,$id_ordine,$titolo,$commento);
    $msg = "OK";

}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Opinione referente";
$r->javascripts_header[]=java_head_rateit();
$r->javascripts_header[]=java_head_ckeditor();

//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);

//Assegno le due tabelle a tablesorter

$opi_logistica = CAST_TO_INT(read_option_opinione_ordine($id_ordine,_USER_ID,opinioni::logistica),0,5);
$opi_rapporto = CAST_TO_INT(read_option_opinione_ordine($id_ordine,_USER_ID,opinioni::rapporti),0,5);
$opi_velocita = CAST_TO_INT(read_option_opinione_ordine($id_ordine,_USER_ID,opinioni::velocita),0,5);


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
                 
                 if(productID == \'logistica\'){
                    $(\'#logistica_text\').html(data);
                 }
                 if(productID == \'rapporto\'){
                    $(\'#rapporto_text\').html(data);
                 }
                 if(productID == \'velocita\'){
                    $(\'#velocita_text\').html(data);
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
$i="<p>Le opinioni inserite dai gestori servono a poter valutare i fornitori dal punto di vista della logistica e semplicità nella gestione di un ordine.</p>
    <p>Per cambiare la propria valutazione sugli argomenti proposti, cliccando sulle stelline. I voti sono salvati immediatamente.</p>
    <p>Si può cancellare la propria opionione con il pulsantino a sinistra delle stelline.</p>
    <p>Si può inserire un commento a piacere. I commenti NON sono anonimi, e possono essere letti da tutti gli utenti iscritti a retedes.</p>
    <p>Per cancellare un commento salvare la schermata lasciando il titolo del commento vuoto.</p>
    ";
$i = rg_toggable("ISTRUZIONI","istr",$i,false);    

//Contenuto

$h .= "<div class=\"rg_widget rg_widget_helper\">";

$h .= "<form method=\"POST\" action=\"\">";
$h .= "<table><tr><td style=\"width:50%;vertical-align:top;\">";
$h .= "<h4>Condividi le tue opinioni da REFERENTE ORDINE,<br> cliccando sulle stelline</h4>
        <span>Logistica dell'ordine</span>
        <div style=\"height:4em; font-size:1.1em\">
            <div    id=\"logistica\" 
                    data-rateit-step=\"1\" 
                    data-rateit-value=\"$opi_logistica\" 
                    data-productid=\"logistica\" 
                    class=\"rateit\">
            </div><br>
       <span id=\"logistica_text\"></span><br>
       </div>";
$h .= " <span>Rapporti con il fornitore</span>
        <div style=\"height:4em; font-size:1.1em\">
            <div    id=\"rapporto\" 
                    data-rateit-step=\"1\" 
                    data-rateit-value=\"$opi_rapporto\" 
                    data-productid=\"rapporto\" 
                    class=\"rateit\">
            </div><br>
            <span id=\"rapporto_text\" style=\"font-size:2;margin-left:2em;\"></span><br>
            
       </div>";
$h .= " <span>Velocità nelle spedizioni</span>
        <div style=\"height:4em; font-size:1.1em\">
            <div    id=\"velocita\" 
                    data-rateit-step=\"1\" 
                    data-rateit-value=\"$opi_velocita\" 
                    data-productid=\"velocita\" 
                    class=\"rateit\">
            </div><br>
            <span id=\"velocita_text\" style=\"font-size:2;margin-left:2em;\"></span><br>
            
       </div>";
$h .= "</td><td style=\"width:50%;vertical-align:top;\">";                     
$h .='
        <h4>Scrivi un commento </h4>
        <label for="titolo">Titolo:</label>
        <input type="text" id="titolo" name="titolo" size="50" value="'.bacheca_read_titolo_commento_ordine_referente(_USER_ID,$id_ordine).'">
        <textarea type="text" id="commento" class="ckeditor" name="commento">'.bacheca_read_commento_ordine_referente(_USER_ID,$id_ordine).'</textarea>
        <input type="hidden" name="id_ordine" value="'.$id_ordine.'">
        <input type="hidden" name="do" value="do_opinione"><br>
        <input type="submit" class="awesome large green" name="submit" value="Salva il tuo commento">      
      ';                    
$h .= "</td></tr></table>";        
$h .= "</form>";
$h .= "</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = schedina_ordine($id_ordine).$i.$h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);