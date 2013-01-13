<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if(!(_USER_PERMISSIONS & perm::puo_postare_messaggi)){
    pussa_via();
}


if($do=="mod"){
    $sql = "SELECT * FROM retegas_bacheca WHERE id_bacheca='$id_bacheca' LIMIT 1;";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    $f .= "<P>Tipo : <strong>".$RG_lista_argomenti_messaggi[$row["code_uno"]]."</strong><br> ";
    $f .= "Ruolo : ".($row["code_due"])."<br> ";
    $f .= "Ditta #".$row["id_ditta"]." ".ditta_nome($row["id_ditta"])."<br>  ";
    $f .= "Ordine #".$row["id_ordine"]." ".descrizione_ordine_from_id_ordine($row["id_ordine"])."</p>";
    $f .= "<form action=\"\" method=\"POST\">";
    $f .= "<label for=\"titolo\">Titolo: </label>";
    $f .= "<input type=\"text\" id=\"titolo\" name=\"titolo\" value=\"".$row["titolo_messaggio"]."\" size=\"30\">";
    $f .= "<textarea name=\"messaggio\" id=\"editor_".$row["id_bacheca"]."\" class=\"ckeditor\">".$row["messaggio"]."</textarea><br>";
    $f .= "<input type=\"hidden\" name=\"do\" value=\"save_mods\">";
    $f .= "<input type=\"hidden\" name=\"id_bacheca\" value=\"".$row["id_bacheca"]."\">";
    $f .= "<input type=\"submit\" class=\"awesome green large\" name=\"submit\" value=\"SALVA\">";
    $f .= "<a class=\"awesome red large destra\" href=\"?do=do_del&id_bacheca=".$row["id_bacheca"]."\">ELIMINA</a>";
    $f .= "<a class=\"awesome silver large\" href=\"\">ANNULLA</a>";
    $f .= "</form>";
    $f .= '<script type="text/javascript">
                CKEDITOR.replace( \'editor_'.$row["id_bacheca"].'\' );
            </script>';
    
    echo $f;
    die();
}

if($do=="save_mods"){
    
    bacheca_update_messaggio($id_bacheca,$titolo,$messaggio);
    //$msg="Modificato !";
}

if($do=="do_del"){
    
    bacheca_delete_messaggio($id_bacheca);
    //$msg="Cancellato";
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::bacheca;
//Assegno il titolo che compare nella barra delle info
$r->title = "Mia valutazione fornitori";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = null;

//Assegno le due tabelle a tablesorter
$r->javascripts_header[] = java_head_ckeditor();
$r->javascripts[]='<script type="text/javascript">                
                        $(document).ready(function() 
                            {
                                $("#output_1").tablesorter({widgets: [\'zebra\',\'saveSort\',\'filter\'],
                                                        cancelSelection : true,
                                                        dateFormat : \'ddmmyyyy\'                                                               
                                                        }); 
                                } 
                            );
</script>';
//AJAX QTIP
$r->javascripts[]='
<script type="text/javascript">
$(document).ready(function()
{

    $(\'.edit\').each(function()
    {
       
        $(this).qtip(
        {
            content: {
                
                text: \'<img class="throbber" src="'.$RG_addr["ajax_loader"].'" alt="Loading..." />\',
                ajax: {
                    url: \''.$RG_addr["bacheca_user_table"].'\',
                    data: { id_bacheca : $(this).attr(\'rel\'), 
                            do: \'mod\'
                    }
                },
                title: {
                    text: \'Gestione commenti ai fornitori\', 
                    button: true
                }
            },
            position: {
                at: \'top center\',
                my: \'top center\',
                target: $(window), 
                effect: false 
            },
            show: {
                event: \'click\',
                solo: true,
                modal: true 
            },
            hide: \'unfocus\',
            style: {
                classes: \'ui-tooltip-light\'
            }
        })
    })

    .click(function(event) { event.preventDefault(); });
});                        
</script>';

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
if($msg)$r->messaggio=$msg;

//Contenuto
$query = "SELECT * FROM retegas_bacheca WHERE id_utente='"._USER_ID."' ORDER BY id_bacheca DESC;";
$res = $db->sql_query($query);

$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>tuoi commenti sui fornitori</h3>";
$h .= "<table id=\"output_1\">";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th>Id</th>";
    $h .="<th>Titolo</th>";
    $h .="<th>Testo</th>";
    $h .="<th>Ordine</th>";
    $h .="<th>Ditta</th>";
    $h .="<th class=\"filter-select\">Tipo</th>";
    $h .="<th class=\"filter-select\">Ruolo</th>";
    $h .="<th data-sorter=\"shortDate\">DATA</th>";
    
    $h .="<th>OPZ</th>";
    $h .="</tr>";
$h .= "</thead>";
$h .= "<tbody>";


while ($row = $db->sql_fetchrow($res)){
    
    if($row["id_ordine"]==0){
        $ordine="";
    }else{
        $ordine = myTruncate($row["id_ordine"]." ". descrizione_ordine_from_id_ordine($row["id_ordine"]),30);
    }
    
    $opt_geo = "<a class=\"awesome blue option\" href=\"".$RG_addr["bacheca_georeferenzia_messaggio"]."?id_bacheca=".$row["id_bacheca"]."\">GEO</a>";
    $opt_edit = "<a class=\"edit awesome yellow option\" rel=\"".$row["id_bacheca"]."\">E</a>";
    
    $ditta = myTruncate($row["id_ditta"]." ". ditta_nome($row["id_ditta"]),20,20);
    
    $h .="<tr>";
    $h .="<td>".$row["id_bacheca"]."</td>";
    $h .="<td>".$row["titolo_messaggio"]."</td>";    
    $h .="<td><span class=\"small_link\" style=\"overflow:hidden; height:2em;\">".myTruncate(strip_tags($row["messaggio"]),100,100)."</span></td>";
    $h .="<td>$ordine</td>";
    $h .="<td>$ditta</td>";
    $h .="<td>".$RG_lista_argomenti_messaggi[$row["code_uno"]]."</td>";
    $h .="<td>".$row["code_due"]."</td>";
    $h .="<td>".conv_date_from_db($row["timbro_bacheca"])."</td>";
    
    $h .="<td>$opt_edit $opt_geo</td>";

    $h .="</tr>";
}
$h .="</tbody>";
$h .="</table>";
$h .="</div>";





//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);   
