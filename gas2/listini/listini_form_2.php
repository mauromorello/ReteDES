<?php


// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");
include_once ("listini_renderer.php");

// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
}

//CONTROLLI
   if(listino_is_privato($id_listino)){
        if(id_gas_user(listino_proprietario($id_listino))<>_USER_ID_GAS){
          go("sommario",_USER_ID,"Listino privato");
       }
   }

if($do=="upload"){
            if($tipo_file=="CSV"){
                $msg = do_upload($fname,$id_listino);
                go("listini_form_2",_USER_ID,$msg,"?id_listino=$id_listino");
                exit;
            }

            if($tipo_file=="XLS"){
                $msg = do_upload_xls($fname,$id_listino);
                go("listini_form_2",_USER_ID,$msg,"?id_listino=$id_listino");
                exit;
            }

            if($tipo_file=="GOO"){

                //http://retegas.altervista.org/gas2/listini/listini_form.php?do=upload&fname=https%3A%2F%2Fdocs.google.com%2Fspreadsheet%2Fpub%3Fkey%3D0An0LoUdzBJs0dDZ4UENCSVpvZ21yWVhaUHRla1JaVkE%26output%3Dcsv&listino=67&tipo_file=GOO&quanti_caricarne=9
                $msg = do_upload_goo(urldecode($fname),$listino,$quanti_caricarne);
                unset($do);
                $id=$listino;
                include("listini_form.php");
                exit;
            }

      }

if($do=="clone"){
    if(!isset($id_articolo)){pussa_via();}

    $sql="SELECT * FROM retegas_articoli WHERE id_articoli='$id_articolo'";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    $codice = $row["codice"]."-".rand(50000,500000);

    $sql="INSERT INTO  `my_retegas`.`retegas_articoli` (

        `id_listini` ,
        `codice` ,
        `u_misura` ,
        `misura` ,
        `descrizione_articoli` ,
        `qta_scatola` ,
        `prezzo` ,
        `ingombro` ,
        `qta_minima` ,
        `qta_multiplo` ,
        `articoli_note` ,
        `articoli_unico` ,
        `articoli_opz_1` ,
        `articoli_opz_2` ,
        `articoli_opz_3`
        )
         SELECT
        `id_listini` ,
        '$codice',
        `u_misura` ,
        `misura` ,
        `descrizione_articoli` ,
        `qta_scatola` ,
        `prezzo` ,
        `ingombro` ,
        `qta_minima` ,
        `qta_multiplo` ,
        `articoli_note` ,
        `articoli_unico` ,
        `articoli_opz_1` ,
        `articoli_opz_2` ,
        `articoli_opz_3`
        FROM retegas_articoli
        WHERE id_articoli = '$id_articolo';";


    $res=$db->sql_query($sql);
    $msg = "Record clonato";

}

if($do=="delete"){
    $e=0;

    if(!isset($id_articolo)){pussa_via();}
    if(articoli_user($id_articolo)<>_USER_ID){$e++;$msg="Articolo non tuo;";}
    if(articoli_in_ordine($id_articolo)<>0){$e++;$msg="Articolo già usato;";}

    if($e==0){
        $sql = "DELETE FROM retegas_articoli WHERE id_articoli='$id_articolo' LIMIT 1;";
        $res = $db->sql_query($sql);
        $msg = "Articolo Eliminato";

    }




}

if($do=="edit_desc"){
    $e=0;

    if(!isset($elementid)){die();}
    $elementid= CAST_TO_INT($elementid);
    $sql = "SELECT * FROM retegas_articoli WHERE id_articoli='$elementid' LIMIT 1;";
    $res = $db->sql_query($sql);
    $row =$db->sql_fetchrow($res);
    $old_value = $row["descrizione_articoli"];

    if($newvalue==""){echo $old_value;die();}
    if(articoli_user($elementid)<>_USER_ID){echo $old_value;die();}
    if(articoli_in_ordine($elementid)<>0){echo $old_value;die();}

    $newvalue_db= mysql_real_escape_string($newvalue);
    $newvalue=trim($newvalue);
    if($e==0){
        $sql = "UPDATE retegas_articoli SET descrizione_articoli = '$newvalue_db' WHERE id_articoli='$elementid' LIMIT 1;";
        $res = $db->sql_query($sql);
        echo $newvalue;
        die();

    }
}
if($do=="edit_cod"){
    $e=0;

    if(!isset($elementid)){die();}
    $elementid= CAST_TO_INT($elementid);
    $id_listino = CAST_TO_INT($id_listino);
    $sql = "SELECT * FROM retegas_articoli WHERE id_articoli='$elementid' LIMIT 1;";
    $res = $db->sql_query($sql);
    $row =$db->sql_fetchrow($res);
    $old_value = $row["codice"];


    if($newvalue==""){echo $old_value;die();}
    if($id_listino==0){echo $old_value;die();}
    if(articoli_user($elementid)<>_USER_ID){echo $old_value;die();}
    if(articoli_in_ordine($elementid)<>0){echo $old_value;die();}


    $sql ="SELECT COUNT(id_articoli) as qt FROM retegas_articoli WHERE id_listini='$id_listino' AND codice='$newvalue' ;";
    $res = $db->sql_query($sql);
    $row = $db->sql_fetchrow($res);
    //echo "QT $sql = ".$row["qt"]." ";
    if($row["qt"]>0){echo $old_value;die();}

    $newvalue_db= mysql_real_escape_string($newvalue);
    $newvalue=trim($newvalue);
    if($e==0){
        $sql = "UPDATE retegas_articoli SET codice = '$newvalue_db' WHERE id_articoli='$elementid' LIMIT 1;";
        $res = $db->sql_query($sql);
        echo $newvalue;
        die();

    }
}
if($do=="edit_prezzo"){
    $e=0;

    if(!isset($elementid)){die();}
    $elementid= CAST_TO_INT($elementid);
    $sql = "SELECT * FROM retegas_articoli WHERE id_articoli='$elementid' LIMIT 1;";
    $res = $db->sql_query($sql);
    $row =$db->sql_fetchrow($res);
    $old_value = $row["prezzo"];

    if($newvalue==""){echo _nf($old_value);die();}
    if(articoli_user($elementid)<>_USER_ID){echo _nf($old_value);die();}
    if(articoli_in_ordine($elementid)<>0){echo _nf($old_value);die();}

    $newvalue=CAST_TO_FLOAT(trim(str_replace(array(",","€"),array(".",""),$newvalue)),0);
    if (!valuta_valida($newvalue)){echo _nf($old_value);die();};



    $newvalue_db= mysql_real_escape_string($newvalue);
    $newvalue=trim($newvalue);
    if($e==0){
        $sql = "UPDATE retegas_articoli SET prezzo = '$newvalue_db' WHERE id_articoli='$elementid' LIMIT 1;";
        $res = $db->sql_query($sql);
        echo _nf($newvalue);
        die();

    }
}

if($do=="edit_u_misura"){
    $e=0;

    if(!isset($elementid)){die();}
    $elementid= CAST_TO_INT($elementid);
    $sql = "SELECT * FROM retegas_articoli WHERE id_articoli='$elementid' LIMIT 1;";
    $res = $db->sql_query($sql);
    $row =$db->sql_fetchrow($res);
    $old_value = $row["u_misura"];

    if($newvalue==""){echo $old_value;die();}
    if(articoli_user($elementid)<>_USER_ID){echo $old_value;die();}
    if(articoli_in_ordine($elementid)<>0){echo $old_value;die();}

    $newvalue = trim($newvalue);
    $newvalue_db= mysql_real_escape_string($newvalue);
    $newvalue=trim($newvalue);
    if($e==0){
        $sql = "UPDATE retegas_articoli SET u_misura = '$newvalue_db' WHERE id_articoli='$elementid' LIMIT 1;";
        $res = $db->sql_query($sql);
        echo $newvalue;
        die();

    }
}

if($do=="edit_opz_3"){
    $e=0;

    if(!isset($elementid)){die();}
    $elementid= CAST_TO_INT($elementid);
    $sql = "SELECT * FROM retegas_articoli WHERE id_articoli='$elementid' LIMIT 1;";
    $res = $db->sql_query($sql);
    $row =$db->sql_fetchrow($res);
    $old_value = $row["articoli_opz_3"];

    $newvalue = trim($newvalue);
    if($newvalue==""){echo $old_value;die();}
    if(articoli_user($elementid)<>_USER_ID){echo $old_value;die();}
    if(articoli_in_ordine($elementid)<>0){echo $old_value;die();}

    $newvalue_db= mysql_real_escape_string($newvalue);
    $newvalue=trim($newvalue);
    if($e==0){
        $sql = "UPDATE retegas_articoli SET articoli_opz_3 = '$newvalue_db' WHERE id_articoli='$elementid' LIMIT 1;";
        $res = $db->sql_query($sql);
        echo $newvalue;
        die();

    }
}
if($do=="edit_opz_2"){
    $e=0;

    if(!isset($elementid)){die();}
    $elementid= CAST_TO_INT($elementid);
    $sql = "SELECT * FROM retegas_articoli WHERE id_articoli='$elementid' LIMIT 1;";
    $res = $db->sql_query($sql);
    $row =$db->sql_fetchrow($res);
    $old_value = $row["articoli_opz_2"];

    $newvalue = trim($newvalue);
    if($newvalue==""){echo $old_value;die();}
    if(articoli_user($elementid)<>_USER_ID){echo $old_value;die();}
    if(articoli_in_ordine($elementid)<>0){echo $old_value;die();}

    $newvalue_db= mysql_real_escape_string($newvalue);
    $newvalue=trim($newvalue);
    if($e==0){
        $sql = "UPDATE retegas_articoli SET articoli_opz_2 = '$newvalue_db' WHERE id_articoli='$elementid' LIMIT 1;";
        $res = $db->sql_query($sql);
        echo $newvalue;
        die();

    }
}
if($do=="edit_opz_1"){
    $e=0;

    if(!isset($elementid)){die();}
    $elementid= CAST_TO_INT($elementid);
    $sql = "SELECT * FROM retegas_articoli WHERE id_articoli='$elementid' LIMIT 1;";
    $res = $db->sql_query($sql);
    $row =$db->sql_fetchrow($res);
    $old_value = $row["articoli_opz_1"];

    if($newvalue==""){echo $old_value;die();}
    if(articoli_user($elementid)<>_USER_ID){echo $old_value;die();}
    if(articoli_in_ordine($elementid)<>0){echo $old_value;die();}

    $newvalue = trim($newvalue);
    $newvalue_db= mysql_real_escape_string($newvalue);
    $newvalue=trim($newvalue);
    if($e==0){
        $sql = "UPDATE retegas_articoli SET articoli_opz_1 = '$newvalue_db' WHERE id_articoli='$elementid' LIMIT 1;";
        $res = $db->sql_query($sql);
        echo $newvalue;
        die();

    }
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::anagrafiche;
//Assegno il titolo che compare nella barra delle info
$r->title = "Scheda listino";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = listini_menu_completo($id_listino);;

$r->javascripts_header[]=java_head_jeditable();
//Assegno le due tabelle a tablesorter

$r->javascripts[]="<script type=\"text/javascript\">

                                $.tablesorter.addWidget({
                                id: 'FilterSave',
                                format: function(table, init){
                                    var sl, time, c = table.config,
                                        wo = c.widgetOptions,
                                        ss = wo.FilterSave !== false; // make FilterSave active/inactive; default to true

                                    var count_filters = $(table).find('input.tablesorter-filter').length;
                                    var filter = new Array();
                                    for (var i=0; i<count_filters;i++)
                                    {
                                        filter[i]=$(table).find('input.tablesorter-filter').eq(i).val();
                                    }

                                    if (c.debug){
                                        time = new Date();
                                    }
                                    if ($(table).hasClass('hasSaveFilter')){
                                        if (ss && table.hasInitialized && $.tablesorter.storage){
                                            $.tablesorter.storage( table, 'tablesorter-savefilter', filter );
                                            // console.log(\"saved\"+filter)
                                            if (c.debug){
                                                $.tablesorter.benchmark('FilterSave widget: Saving last filter: ' + filter, time);
                                            }
                                        }
                                    } else if (table.hasInitialized){
                                        // set table sort on initial run of the widget
                                        $(table).addClass('hasSaveFilter');
                                        filter = '';
                                        // get data
                                        if ($.tablesorter.storage){
                                            fl = $.tablesorter.storage( table, 'tablesorter-savefilter' );
                                            filter = (fl && $.isArray(fl)) ? fl : '';
                                            if (c.debug){
                                                $.tablesorter.benchmark('FilterSave: Last filter loaded: \"' + filter + '\"', time);
                                            }
                                        }
                                        // init is true when widget init is run, this will run this widget before all other widgets have initialized
                                        // this method allows using this widget in the original tablesorter plugin; but then it will run all widgets twice.
                                        if (filter && filter.length > 0)
                                        {
                                            $(table).trigger('search', [filter]);
                                        }
                                    }
                                },
                                remove: function(table, c, wo){
                                    // clear storage
                                    $.tablesorter.storage( table, 'tablesorter-savefilter', '' );
                                }
                            });

</script>";

$r->javascripts[]='<script type="text/javascript">
                        $(document).ready(function()
                            {
                                $("#output_1").tablesorter({widgets: [\'zebra\',\'saveSort\',\'filter\',\'resizable\',\'FilterSave\'],
                                                        cancelSelection : true,
                                                        dateFormat : \'ddmmyyyy\',
                                                        });
                                }
                            );
</script>';

//DESCIZIONE
$r->javascripts[]=" <script type=\"text/javascript\">
                    $(document).ready(function() {
                         $('.edit_desc').editable('', {
                             id   : 'elementid',
                             name : 'newvalue',
                             submitdata : {do: 'edit_desc'},
                             submit : 'OK'
                         });
                     });
                     </script>";
//CODICE
$r->javascripts[]=" <script type=\"text/javascript\">
                    $(document).ready(function() {
                         $('.edit_cod').editable('', {
                             id   : 'elementid',
                             name : 'newvalue',
                             submitdata : {do: 'edit_cod',id_listino:'$id_listino'},
                             submit : 'OK'
                         });
                     });
                     </script>";
$r->javascripts[]=" <script type=\"text/javascript\">
                    $(document).ready(function() {
                         $('.edit_prezzo').editable('', {
                             id   : 'elementid',
                             name : 'newvalue',
                             submitdata : {do: 'edit_prezzo',id_listino:'$id_listino'},
                             submit : 'OK'
                         });
                     });
                     </script>";
$r->javascripts[]=" <script type=\"text/javascript\">
                    $(document).ready(function() {
                         $('.edit_u_misura').editable('', {
                             id   : 'elementid',
                             name : 'newvalue',
                             submitdata : {do: 'edit_u_misura',id_listino:'$id_listino'},
                             submit : 'OK'
                         });
                     });
                     </script>";
$r->javascripts[]=" <script type=\"text/javascript\">
                    $(document).ready(function() {
                         $('.edit_opz_3').editable('', {
                             id   : 'elementid',
                             name : 'newvalue',
                             submitdata : {do: 'edit_opz_3',id_listino:'$id_listino'},
                             submit : 'OK'
                         });
                     });
                     </script>";
$r->javascripts[]=" <script type=\"text/javascript\">
                    $(document).ready(function() {
                         $('.edit_opz_2').editable('', {
                             id   : 'elementid',
                             name : 'newvalue',
                             submitdata : {do: 'edit_opz_2',id_listino:'$id_listino'},
                             submit : 'OK'
                         });
                     });
                     </script>";
$r->javascripts[]=" <script type=\"text/javascript\">
                    $(document).ready(function() {
                         $('.edit_opz_1').editable('', {
                             id   : 'elementid',
                             name : 'newvalue',
                             submitdata : {do: 'edit_opz_1',id_listino:'$id_listino'},
                             submit : 'OK'
                         });
                     });
                     </script>";

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}else{
    $r->messaggio=$msg;
}
//Contenuto
$h = "Listini form 2";

//Questo ?? il contenuto della pagina
$h = listini_form($id_listino,false).
        "<div id=\"container_articolo\"></div>"
        .listini_articoli_table("output_1",$id_listino);

$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r);