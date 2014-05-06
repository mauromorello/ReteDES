<?php


// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");

include_once ("gas_renderer.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
    pussa_via();
    exit;
}

    if($out=="xls"){

    }

    if($out=="csv_all"){
        $sql = "SELECT userid, isactive, fullname, email FROM maaking_users WHERE id_gas="._USER_ID_GAS.";";
        query_to_csv($sql, "Utenti_tutti.csv", true);
        die();
    }
    if($out=="csv_act"){
        $sql = "SELECT userid, isactive, fullname, email FROM maaking_users WHERE id_gas="._USER_ID_GAS." and isactive=1;";
        query_to_csv($sql, "Utenti_attivi.csv", true);
        die();
    }
    if($out=="csv_sus"){
        $sql = "SELECT userid, isactive, fullname, email FROM maaking_users WHERE id_gas="._USER_ID_GAS." and isactive=2;";
        query_to_csv($sql, "Utenti_sospesi.csv", true);
        die();
    }
    if($out=="csv_eli"){
        $sql = "SELECT userid, isactive, fullname, email FROM maaking_users WHERE id_gas="._USER_ID_GAS." and isactive=3;";
        query_to_csv($sql, "Utenti_eliminati.csv", true);
        die();
    }

    // ISTANZIO un nuovo oggetto "retegas"
    // Prender? come variabile globale $user, nel caso di user loggato
    // allora visualizza la barra info ed il menu verticale,
    // nel caso di user non loggato visualizza la pagina con "benvenuto" e
    //nel men? verticale i campi per il login
    $retegas = new sito;

    // assegno la posizione che sar? indicata nella barra info
    $retegas->posizione = "Utenti Attivi del mio GAS";

    // Dico a retegas come sar? composta la pagina, cio? da che sezioni ? composta.
    // Queste sono contenute in un array che ho chiamato HTML standard

    $retegas->sezioni = $retegas->html_standard;

    // Il menu' orizzontale ? pronto ma ? vuoto. Con questa istruzione lo riempio con un elemento
    $retegas->menu_sito = gas_menu_completo($user);

    // dico a retegas quali sono i fogli di stile che dovr? usare
    // uso quelli standard per la maggior parte delle occasioni
    $retegas->css = $retegas->css_standard;


    if(_USER_HAVE_MSG){
        $retegas->messaggio = _USER_MSG;
        delete_option_text(_USER_ID,"MSG");
    }


    // dico a retegas quali file esterni dovr? caricare
    $retegas->java_headers = array("rg");  // ordinatore di tabelle

      // creo  gli scripts per la gestione dei menu

      $retegas->java_scripts_header[]=java_accordion(null, menu_lat::gas); // laterale
      $retegas->java_scripts_header[]='<script type="text/javascript">
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
      $retegas->java_scripts_header[]=java_superfish();
          // orizzontale



          // qui ci va la pagina vera e proria


      $retegas->content  =  gas_render_user(_USER_ID_GAS,"user_table");


      //  Adesso ho tutti gli elementi per poter costruire la pagina, che metto nella variabile "html"
      $html = $retegas->sito_render();
      // Butto fuori la variabile "html" e l'utente riceve la pagina sul suo browser"
      echo $html;


      //distruggo retegas per recuperare risorse sul server
      unset($retegas);



?>