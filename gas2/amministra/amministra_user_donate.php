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

$id_utente = mimmo_decode($id_utente);


if($do=="donate"){
    $euros = CAST_TO_FLOAT($euros,0);
    if (valuta_valida($euros)){
     write_option_decimal($id_utente,"DONATE",$euros);
    }
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::user;
//Assegno il titolo che compare nella barra delle info
$r->title = "User Donate";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = amministra_menu_completo();

//Assegno le due tabelle a tablesorter
$r->javascripts[]='<script type="text/javascript">
                         // addWidget tmTotals 
      // ******************* Written by Tim Miller
      $.tablesorter.addWidget({ 
          id: \'tmTotals\', 
          // The init function (added in v2.0.28) is called only after tablesorter has 
          // initialized, but before initial sort & before any of the widgets are applied. 
          init: function(table, allWidgets, thisWidget){ 
              // widget initialization code - this is only *RUN ONCE* 
              // but in this example, only the format function is called to from here 
              // to keep the widget backwards compatible with the original tablesorter 
              thisWidget.format(table, true); 
          }, 
          format: function(table, initFlag) { 
              // widget code to apply to the table *AFTER EACH SORT* 
              // the initFlag is true when this format is called from the init 
              // function above otherwise initFlag is undefined 
              // * see the saveSort widget for a full example * 
              mytablefoot = table.getElementsByTagName("tfoot")[0];
              for(var i = 0; i < mytablefoot.rows[0].cells.length; i++)
              {
                if (mytablefoot.rows[0].cells[i].className == "sum") {
                  var subTotal = 0;
                  mytablebody = table.getElementsByTagName("tbody")[0];
                  for(var j = 0; j < mytablebody.rows.length; j++)
                  {
                    if (mytablebody.rows[j].style.display != "none") {
                      subTotal = parseFloat(subTotal) + parseFloat(mytablebody.rows[j].cells[i].innerHTML);
                    }
                  }
                  mytablefoot.rows[0].cells[i].innerHTML = subTotal;
                } else {
                  mytablefoot.rows[0].cells[i].innerHTML = "";
                }
              }

          } 
      });                
                        $(document).ready(function() 
                            {
                                $("#output_1").tablesorter({widgets: [\'zebra\',\'saveSort\',\'filter\',\'tmTotals\'],
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

$sql = "SELECT * from retegas_options WHERE chiave='DONATE';";
$res = $db->sql_query($sql);

$h .= "<div class=\"rg_widget rg_widget_helper\">";
$h .= "<h3>Utenti che hanno donato</h3>";
$h .= "<table id=\"output_1\">";
$h .= "<thead>";
    $h .="<tr>";
    $h .="<th>ID</td>";
    $h .="<th>Nome</td>";
    $h .="<th>Gas</td>";
    $h .="<th>Mail</td>";
    $h .="<th class=\"filter-false\">Date</td>";
    $h .="<th>Donation</td>";    
    $h .="</tr>";
$h .= "</thead>";
$h .= "<tbody>";
while ($row = $db->sql_fetchrow($res)){
    $h .="<tr>";
    $h .="<td><a href=\"".$RG_addr["pag_users_form"]."\">".$row["id_user"]."</a></td>";
    $h .="<td>".fullname_from_id($row["id_user"])."</td>";
    $h .="<td>".gas_nome(id_gas_user($row["id_user"]))."</td>";
    $h .="<td>".email_from_id($row["id_user"])."</td>";
    $h .="<td>".conv_date_from_db($row["timbro"])."</td>";
    $h .="<td class=\"destra\">"._nf($row["valore_real"])." Eu.</td>";
    $h .="</tr>";
}
$h .="</tbody>";
$h .= "<tfoot>";
    $h .="<tr>";
    $h .="<th>&nbsp;</td>";
    $h .="<th>&nbsp;</td>";
    $h .="<th>&nbsp;</td>";
    $h .="<th>&nbsp;</td>";
    $h .="<th>&nbsp;</td>";
    $h .="<th class=\"sum\">&nbsp;</td>";    
    $h .="</tr>";
$h .= "</tfoot>";
$h .="</table>";
$h .="</div>";


//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r    
unset($r)   
?>