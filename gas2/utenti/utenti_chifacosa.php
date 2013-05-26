<?php

   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

if(!(_USER_PERMISSIONS & perm::puo_gestire_utenti)){
    pussa_via();
}




//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Chi fa che cosa";
$r->menu_orizzontale = gas_menu_completo(_USER_ID_GAS);
$r->javascripts_header[]='<script type="text/javascript">                
                        $.tablesorter.addWidget({
    id: \'FilterSave\',
    format: function(table, init){
        var sl, time, c = table.config,
            wo = c.widgetOptions,
            ss = wo.FilterSave !== false; // make FilterSave active/inactive; default to true

        var count_filters = $(table).find(\'input.tablesorter-filter\').length;
        var filter = new Array();
        for (var i=0; i<count_filters;i++)
        {
            filter[i]=$(table).find(\'input.tablesorter-filter\').eq(i).val();
        }

        if (c.debug){
            time = new Date();
        }
        if ($(table).hasClass(\'hasSaveFilter\')){
            if (ss && table.hasInitialized && $.tablesorter.storage){
                $.tablesorter.storage( table, \'tablesorter-savefilter\', filter );
                 console.log("saved"+filter)
                if (c.debug){
                    $.tablesorter.benchmark(\'FilterSave widget: Saving last filter: \' + filter, time);
                }
            }
        } else {
            // set table sort on initial run of the widget
            $(table).addClass(\'hasSaveFilter\');
            filter = \'\';
            // get data
            if ($.tablesorter.storage){
                fl = $.tablesorter.storage( table, \'tablesorter-savefilter\' );
                filter = (fl && $.isArray(fl)) ? fl : \'\';
                if (c.debug){
                    $.tablesorter.benchmark(\'FilterSave: Last filter loaded: "\' + filter + \'"\', time);
                }
            }
            // init is true when widget init is run, this will run this widget before all other widgets have initialized
            // this method allows using this widget in the original tablesorter plugin; but then it will run all widgets twice.
            //if (filter && filter.length > 0)
            //{
            $(table).trigger(\'search\', [filter]);
            //}
        }
    },
    remove: function(table, c, wo){
        // clear storage
        $.tablesorter.storage( table, \'tablesorter-savefilter\', \'\' );
    }
});
                        
                        
                        $(document).ready(function() 
                            {
                                $("#user_table").tablesorter({widgets: [\'zebra\',\'saveSort\',\'filter\',\'FilterSave\'],
                                                        cancelSelection : true,
                                                        dateFormat : \'ddmmyyyy\',                                                               
                                                        }); 
                                } 
                            );
</script>';


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto

$sql = "SELECT * from maaking_users WHERE id_gas='"._USER_ID_GAS."'";
$res = $db->sql_query($sql);

$h .="<table id=\"user_table\">";
$h .="<thead>";
$h .="<tr>";
$h .="<th>ID</th>";
$h .="<th>Utente</th>";
$h .="<th>Permessi</th>";
$h .="</tr>";
$h .="</thead>";
$h .="<tbody>";



while ($row = $db->sql_fetchrow($res)){

    $h .="<tr>";
    $h .="<td>".$row["userid"]."</td>";
    $h .="<td>".$row["fullname"]."</td>";
    $h .="<td>".utenti_scheda_permessi($row["userid"])."</td>";
    $h .="</tr>";

        
}

$h .="</tbody>";
$h .="</table>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r);