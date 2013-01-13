<?php
   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//CONTROLLI
if (!(_USER_PERMISSIONS & perm::puo_gestire_retegas)){
     pussa_via();
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "Amministra ordini";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = amministra_menu_completo();

//Assegno le due tabelle a tablesorter
$r->javascripts[]='<script type="text/javascript">                
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
                                $("#output_1").tablesorter({widgets: [\'zebra\',\'saveSort\',\'filter\',\'FilterSave\'],
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

    $query="SELECT * FROM  retegas_ordini ORDER BY id_ordini DESC";
 
      $result= mysql_query($query);

      $h_table .="<div class=\"rg_widget rg_widget_helper\">";
      $h_table .= $intestazione;
      $h_table .= "<table id=\"output_1\">
      <thead>
      <tr>";
      //$h_table .= '<th> OPZ </th>';
      $h_table .="<th>ID</th>";
      $h_table .="<th>DESC</th>"; 
      $h_table .="<th>STATO</th>"; 
      $h_table .="<th>USER</th>"; 
      $h_table .="<th>GAS</th>"; 
      $h_table .="<th>LISTINO</th>";
      $h_table .="<th>DITTA</th>"; 
      $h_table .="<th data-sorter=\"shortDate\">APERTURA</th>"; 
      $h_table .="<th data-sorter=\"shortDate\">CHIUSURA</th>"; 
      $h_table .="<th>OPERAZIONI</th>"; 
      
      $h_table .= "</tr>
      </thead>
      <tbody>";
   
     
   
      while ($row = mysql_fetch_array($result)) 
      {
          
         
      $h_table.= '<tr>
                        <td>
                        '.$row["id_ordini"].'
                        </td>
                        <td>
                        '.$row["descrizione_ordini"].'
                        </td>
                        <td>
                        '.$row["id_stato"].'
                        </td>
                        <td>
                            '.fullname_from_id($row["id_utente"]).'
                        </td>
                        <td>
                            '.gas_user($row["id_utente"]).'
                        </td>
                        <td>
                            '.listino_nome($row["id_listini"]).'
                        </td>
                        <td>
                            '.ditta_nome_from_listino($row["id_listini"]).'
                        </td>
                        <td>
                            '.conv_datetime_from_db($row["data_apertura"]).'
                        </td>
                        <td>
                            '.conv_datetime_from_db($row["data_chiusura"]).'
                        </td>
                        <td>
                            <a class="awesome black small" href="'.$RG_addr["amministra_ordini_del_all"].'?id_ordine='.$row["id_ordini"].'">elimina</a>
                        </td>
                    </tr>';
      
       
      //$h_table .= '<tr><td></td><td  style="background-color:#'.$colo["$row[2]"].';display: block;">'.implode($row,'</td><td>')."</td></tr>\n"; 
      }
      $h_table .= "
      </tbody>
      </table>
      </div>";



//Questo ?? il contenuto della pagina
$r->contenuto = $h_table;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>