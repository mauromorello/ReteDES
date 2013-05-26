<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("ditte_renderer.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::anagrafiche;
//Assegno il titolo che compare nella barra delle info
$r->title = "Ditte";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ditte_menu_completo();
$r->javascripts_header[]=java_head_jeditable();
$r->javascripts_header[]=java_head_rateit();


//Assegno le due tabelle a tablesorter


if(_USER_PERMISSIONS & perm::puo_gestire_retegas){                        
    $r->javascripts[]=" <script type=\"text/javascript\">
                        $(document).ready(function() {
                             $('.edit_address').editable('".$RG_addr["ditte_edit_address"]."', { 
                                 id   : 'elementid',
                                 name : 'newvalue',
                                 style: 'inherit',
                                 submitdata : {type: 'address_ditte'},
                                 height : 20,
                                 width  : 200,
                                 submit    : 'OK'
                             });
                         });
                         </script>";
    $r->javascripts[]=" <script type=\"text/javascript\">
                        $(document).ready(function() {
                             $('.edit_tags').editable('".$RG_addr["ditte_edit_address"]."', { 
                                 id   : 'elementid',
                                 name : 'newvalue',
                                 style: 'inherit',
                                 submitdata : {type: 'tags_ditte'},
                                 height : 20,
                                 width  : 200,
                                 submit    : 'OK'
                             });
                         });
                         
                                                 </script>";                                                 
}
$r->javascripts[]='<script type="text/javascript">                
                        $(document).ready(function(){
                         
                         //PARSER FOR TABLESORTER
                          $.tablesorter.addParser({ 
                            // set a unique id 
                            id: \'myParser\', 
                            is: function(s) { 
                              // return false so this parser is not auto detected 
                              return false; 
                            }, 
                            format: function(s, table, cell, cellIndex) { 
                              // get data attributes from $(cell).attr(\'data-rateit-value\');
                              // check specific column using cellIndex
                              console.log($(cell).attr(\'data-rateit-value\'));
                              return $(cell).attr(\'data-rateit-value\');
                              
                            }, 
                            // set type, either numeric or text 
                            type: \'text\' 
                          }); 


                        

                        $("#output_1").tablesorter({widgets: [\'zebra\',\'saveSort\',\'filter\'],
                                                    cancelSelection : true,
                                                    dateFormat : \'ddmmyyyy\',
                                                    headers : { 
                                                        0 : { sorter: \'myParser\' }
                                                    }
                                                    });
                        
                        });
                        </script>';

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}


if($search){
    $filtro = ", filtrate con \"$search\"";
}

//Contenuto
$query = "SELECT * FROM retegas_ditte ORDER BY retegas_ditte.descrizione_ditte ASC";
   $result = $db->sql_query($query);
   
   $t    .= '<div class="rg_widget rg_widget_helper">
            <h3>Ditte di ReteDes.it'.$filtro.'</h3>';
   
   $t .= "<table id=\"output_1\">";
   $t .= "<thead>";
   $t .= "<tr>";
       $t .= "<th></th>";
       $t2 .= "<th>ID</th>";
       $t .= "<th>NOME</th>";
       $t .= "<th>INDIRIZZO</th>";
       $t .= "<th>DISTANZA</th>";
       
       $t .= "<th>PROPONENTE</th>";
       $t .= "<th class=\"filter-select\">GAS</th>";
       
       $t .= "<th>LISTINI</th>";
       $t .= "<th>TAGS</th>";
   $t .= "</tr>";
   
   $t .= "</thead>";
   $t .= "<tbody>";
   
   //Mio Gas
   $gas_gc_lat = db_val_q("id_gas",_USER_ID_GAS,"gas_gc_lat","retegas_gas");
   $gas_gc_lng = db_val_q("id_gas",_USER_ID_GAS,"gas_gc_lng","retegas_gas");
   
         
   while ($row = $db->sql_fetchrow($result)){
   
   if(_USER_ID==$row["id_proponente"]){
   }
  
   $proponente      = fullname_from_id($row["id_proponente"]);
   $gas_proponente  = gas_nome(id_gas_user($row["id_proponente"]));
   $indirizzo = $row["indirizzo"];
   $nome_ditta = $row["descrizione_ditte"];
   $listini_attivi = listini_ditte($row["id_ditte"]);
   
   if($listini_attivi>0){
       $pal = '<IMG SRC="'.$RG_addr["img_pallino_verde"].'" ALT="Listini attivi presenti" style="height:16px; width:16px;vertical_align:middle;border=0; padding-right:1em;">';
   }else{
       $pal = '<IMG SRC="'.$RG_addr["img_pallino_rosso"].'" ALT="Listini attivi ASSENTI" style="height:16px; width:16px;vertical_align:middle;border=0; padding-right:1em;">';
   }
   
   $listini_totali = listini_ditte_totali($row["id_ditte"]);
   
   
   $mail_ditta = $row["mail_ditte"];
   $website = $row["website"];
   
   $tags = $proponente." ".$gas_proponente." ".$nome_ditta.' '.$row["tag_ditte"]; 
    
   if($search){
     if(strrpos(strtolower($tags),strtolower($search))>0){
        $show="OK"; 
     }else{
        $show="NO";
     }
       
   }else{
       $show="OK";
   }
   
   
   
   if($row["ditte_gc_lng"]>0){
        $distance =  round(getDistanceBetweenPointsNew($row["ditte_gc_lat"], $row["ditte_gc_lng"], $gas_gc_lat, $gas_gc_lng),2);
   }else{
        $distance = 0;    
   }
   
   $val_valu = media_opinione_ditta($row["id_ditte"]);
   $valutazione= "<div class=\"rateit\"    data-rateit-value=\"$val_valu\" 
                                            data-rateit-ispreset=\"true\" 
                                            data-rateit-readonly=\"true\">";
   $valu_sorter = " data-rateit-value=\"$val_valu\" ";
   
   if($show=="OK"){
       $t .= "<tr>";
            $t .= "<td $valu_sorter style=\"padding:2px;\" title=\"Valutazione media : $val_valu\">$valutazione</td>";
            $t2 .= "<td>".$row["id_ditte"]."</td>";
            $t .= "<td><a href=\"".$RG_addr["ditte_form_new"]."?id_ditta=".$row["id_ditte"]."\">$nome_ditta</a></td>";
            $t .= "<td class=\"edit_address\" id=\"".$row["id_ditte"]."\">$indirizzo</td>";
            $t .= "<td>$distance</td>";
            $t .= "<td>$proponente</td>";
            $t .= "<td>$gas_proponente</td>";
            $t .= "<td>$listini_attivi/$listini_totali</td>";
            $t .= "<td><span class=\"edit_tags small_link\" id=\"".$row["id_ditte"]."\">".$row["tag_ditte"]."</span></td>";  
       $t .= "</tr>";
   }
   }
   $t .= "</tbody>";
   $t .= "<tfoot>";
   $t .= "</tfoot>";
   $t .= "</table>";
   $t .= "</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $t;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);