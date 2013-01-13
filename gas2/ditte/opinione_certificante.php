<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLO SE HA PARTECIPATO
if (!(_USER_PERMISSIONS & perm::puo_vedere_retegas)){
     pussa_via();
}

if(!isset($id_ditta)){
     pussa_via();
}

$value=CAST_TO_INT($value,0,5);

if($tipo=="sociale"){
   if(($value>0)){
        write_option_opinione_ditta($id_ditta,_USER_ID,opinioni::sociale,CAST_TO_INT($value,1,5));
        echo "Registrato il tuo voto: <span style=\"font-size:1.1em;font-weight:bold\">$value</span>.";
        die(); 
   }else{
        delete_option_opinione_ditta($id_ditta,_USER_ID,opinioni::sociale);
        echo "Cancellato !";
        die(); 
    }
     
}
if($tipo=="finanza"){
   if(($value>0)){
        write_option_opinione_ditta($id_ditta,_USER_ID,opinioni::finanza,CAST_TO_INT($value,1,5));
        echo "Registrato il tuo voto: <span style=\"font-size:1.1em;font-weight:bold\">$value</span>.";
        die(); 
   }else{
        delete_option_opinione_ditta($id_ditta,_USER_ID,opinioni::finanza);
        echo "Cancellato !";
        die(); 
    }
     
}
if($tipo=="ambiente"){
   if(($value>0)){
        write_option_opinione_ditta($id_ditta,_USER_ID,opinioni::ambiente,CAST_TO_INT($value,1,5));
        echo "Registrato il tuo voto: <span style=\"font-size:1.1em;font-weight:bold\">$value</span>.";
        die(); 
   }else{
        delete_option_opinione_ditta($id_ditta,_USER_ID,opinioni::ambiente);
        echo "Cancellato !";
        die(); 
    }
     
}

if($do=="do_opinione"){
    
    bacheca_write_commento_ditta_certificante(_USER_ID,$id_ditta,$titolo,$commento);
    $msg = "OK";
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::anagrafiche;
//Assegno il titolo che compare nella barra delle info
$r->title = "Certificazione DES";
$r->javascripts_header[]=java_head_rateit();
$r->javascripts_header[]=java_head_ckeditor();

//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ditte_menu_completo($id_ditta);

//Assegno le due tabelle a tablesorter

$opi_sociale = CAST_TO_INT(read_option_opinione_ditta($id_ditta,_USER_ID,opinioni::sociale),0,5);
$opi_finanza = CAST_TO_INT(read_option_opinione_ditta($id_ditta,_USER_ID,opinioni::finanza),0,5);
$opi_ambiente = CAST_TO_INT(read_option_opinione_ditta($id_ditta,_USER_ID,opinioni::ambiente),0,5);


//QUALITA
$r->javascripts[]='<script type="text/javascript">
     $(\'.rateit\').bind(\'rated reset\', function (e) {
         var ri = $(this);
 
         var value = ri.rateit(\'value\');
         var productID = ri.data(\'productid\'); // if the product id was in some hidden field: ri.closest(\'li\').find(\'input[name="productid"]\').val()
 
         $.ajax({
             url: \'\', //your server side script
             data: { tipo: productID, value: value, id_ditta: '.$id_ditta.' }, //our data
             type: \'POST\',
             success: function (data) {
                 
                 if(productID == \'sociale\'){
                    $(\'#sociale_text\').html(data);
                 }
                 if(productID == \'finanza\'){
                    $(\'#finanza_text\').html(data);
                 }
                 if(productID == \'ambiente\'){
                    $(\'#ambiente_text\').html(data);
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
$i="<p>I certificati sono gestibili poi dalla pagina CER</p>
    <p>Per cambiare la propria valutazione sugli argomenti proposti, clicca sulle stelline, il salvataggio è immediato.</p>
    <p>Si può inserire un commento a piacere. I commenti NON sono anonimi, e possono essere letti da tutti gli utenti iscritti a retedes.</p>
    <p></p>
    <p>Per cancellare un commento salvare la schermata lasciando il titolo del commento vuoto.</p>
    ";
$i = rg_toggable("ISTRUZIONI","istr",$i,false);    

//Contenuto

$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<form  method=\"POST\" action=\"\">";
$h .= "<table><tr><td style=\"width:50%;vertical-align:top;\">";
$h .= "<h4>Imposta la valutazione del DES <br> cliccando sulle stelline</h4>
        
        <span>Valore aggiunto SOCIALE della ditta in questione</span>
        <div style=\"height:4em; font-size:1.1em\">
            <div    id=\"sociale\" 
                    data-rateit-step=\"1\" 
                    data-rateit-value=\"$opi_sociale\" 
                    data-productid=\"sociale\" 
                    class=\"rateit\">
            </div><br>
            <span id=\"sociale_text\" ></span><br>
            
       </div>";
$h .= " <span>Trasparenza FINANZIARIA</span>
        <div style=\"height:4em; font-size:1.1em\">
            <div    id=\"finanza\" 
                    data-rateit-step=\"1\" 
                    data-rateit-value=\"$opi_finanza\" 
                    data-productid=\"finanza\" 
                    class=\"rateit\">
            </div><br>
            <span id=\"finanza_text\"></span><br>
            
       </div>";
$h .= " <span>Tutela aspetto Ambientale</span>
        <div style=\"height:4em; font-size:1.1em\">
            <div    id=\"ambiente\" 
                    data-rateit-step=\"1\" 
                    data-rateit-value=\"$opi_ambiente\" 
                    data-productid=\"ambiente\" 
                    class=\"rateit\">
            </div><br>
            <span id=\"ambiente_text\"></span><br>
            
       </div>";
       $h .= "</td><td style=\"width:50%;vertical-align:top;\">";              
$h .='
        <h4>Questa è una "certificazione". Puoi inserire testo a piacere o link a siti o a pagine esterne. Le certificazioni verranno presentate nella scheda della ditta sotto la voce "certificazioni"</h4>
        <label for="titolo">Titolo:</label>
        <input type="text" id="titolo" name="titolo" size="50" value="">
        <textarea type="text" id="commento" class="ckeditor" name="commento"></textarea>
        <input type="hidden" name="id_ditta" value="'.$id_ditta.'">
        <input type="hidden" name="do" value="do_opinione">
        <input type="submit" class="awesome large green" name="submit" value="Salva">      
      ';
                    
$h .= "</td></tr></table>";         
$h .= "</form>";


$sql = "SELECT * FROM retegas_bacheca WHERE id_ditta='$id_ditta' AND code_due='".ruoli::certificante."' ORDER BY id_bacheca DESC; ";
$res = $db->sql_query($sql);

$h .= "<h3>CERTIFICAZIONI GIA' PRESENTI :</h3>";
while ($row = $db->sql_fetchrow($res)){

 $h .= bacheca_render_fullwidth_messaggio($row["id_bacheca"]);
     
}

$h .= "</div>";
//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);