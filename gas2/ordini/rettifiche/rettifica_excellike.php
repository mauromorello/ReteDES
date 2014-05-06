<?php


// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
}
//controllare gestore
if($do=="do_save"){

//echo "ID_ordine : $id_ordine -- ";
$start = 5;
 // RIGA UTENTI
$sql = "SELECT userid, isactive, fullname, email FROM maaking_users WHERE id_gas="._USER_ID_GAS." ORDER BY userid ASC;";
$res = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($res)){

    $q_ord = round(qta_ord_ordine_user($id_ordine,$row["userid"]));

    if($q_ord>0){
       $indice = (int) $start + $i;
       $utente[$indice] = $row["userid"];
       //echo "Utente pos: ".($i+$start)." -> id ".$row["userid"]."-- q_ord = $q_ord --- utente[$indice] )=".$utente[$indice]." ----// ";
       $i++;
    }
}


 foreach ($data as $row) {
     $i=0;
     $articolo = $row[0];
     foreach ($row as $cell) {

         if($i>=$start){
            $q_arr = CAST_TO_FLOAT($cell,0);
            $q_ord = round(qta_ord_ordine_user($id_ordine,$utente[$i]));

            $sqld = "SELECT id_dettaglio_ordini FROM retegas_dettaglio_ordini WHERE id_ordine='$id_ordine' AND id_utenti='".$utente[$i]."' AND id_articoli='$articolo' LIMIT 1;";
            $resd = $db->sql_query($sqld);

            while ($rowd = $db->sql_fetchrow($resd)){
                $id_dettaglio = $rowd["id_dettaglio_ordini"];
            }

            //echo "($sqld Dettaglio _ $id_dettaglio USER : ".$utente[$i]." ART : $articolo; Qarr:".$q_arr." q_ord = $q_ord) ";



            //$sql = "UPDATE retegas_dettaglio_ordini SET qta_arr='$q_arr' WHERE id_dettaglio_ordini='$id_dettaglio' LIMIT 1;";
            //$db->sql_query($sql);
            //ridistribuisci_quantita_amici_1($id_dettaglio,$q_arr);


         }
         $i++;
     }

 }




 $res = array();
 $res["result"]="ok";
 echo json_encode($res);
 die();
}
//Controllare assenza articoli univoci




//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Rettifica Excel";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);

//Assegno le due tabelle a tablesorter
$r->javascripts_header[]="<script type=\"text/javascript\" src=\"".$RG_addr["js_handsomtable"]."\" ></script>
                          <link rel=\"stylesheet\" media=\"screen\" href=\"".$RG_addr["css_handsomtable"]."\">";

$r->javascripts[]=java_tablesorter("output_1");

// RIGA UTENTI
$sql = "SELECT userid, isactive, fullname, email FROM maaking_users WHERE id_gas="._USER_ID_GAS." ORDER BY userid ASC;";
$res = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($res)){

    $q_ord = round(qta_ord_ordine_user($id_ordine,$row["userid"]));
    if($q_ord>0){
        $q_arr = round(qta_arr_ordine_user($id_ordine,$row["userid"]));
        $u .= '"'.$row["fullname"].'",';
        $ro .= '{readOnly: false},';
        if($q_ord<>$q_arr){

        }
    }
}
$u = rtrim($u,",");
$ro = rtrim($ro,",");

//COLONNE ARTICOLI
$id_listino = id_listino_from_id_ordine($id_ordine);
$sql="SELECT * FROM retegas_articoli
                 WHERE id_listini='$id_listino'
                 ORDER BY retegas_articoli.codice ASC;";
$res = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($res)){

    $q_arr_tot = round(qta_arr_ordine_articolo($id_ordine,$row["id_articoli"]));
    if($q_arr_tot==0){
        $q_arr_tot ="";
    }

    $a = '"'.$row["id_articoli"].'","'.$row["codice"].'",'.'"'.$row["descrizione_articoli"].'",'.'"'.round($row["misura"]).' '.$row["u_misura"].' x '.round($row["prezzo"]).' €","'.$q_arr_tot.'"';
        $sqla = "SELECT userid, isactive, fullname, email FROM maaking_users WHERE id_gas="._USER_ID_GAS.";";
        $resa = $db->sql_query($sqla);
        unset($u2);
        while ($rowa = $db->sql_fetchrow($resa)){
            $q_arr_t = round(qta_ord_ordine_user($id_ordine,$rowa["userid"]));
            if($q_arr_t>0){
                $q_arr = round(qta_arr_ordine_articolo_user($id_ordine,$row["id_articoli"],$rowa["userid"]));
                if($q_arr==0){
                    $q_arr ="";
                }
                $u2 .= '"'.$q_arr.'", ';
            }


        }
        $u2 = rtrim($u2,",");

    $ri .=(string) "[$a,$u2],\n";
}
$ri = rtrim($ri,",");

$r->javascripts[]='
                    <script type="text/javascript">
                    var data = [
                      '.$ri.'
                    ];
                    var $container = $("#example");

                    var $parent = $container.parent();

                    $("#example").handsontable({
                      data: data,
                      colHeaders: ["ID","Codice", "Descrizione", "UM", "TOTALE", '.$u.'],
                      columns: [{readOnly: true},{readOnly: true},{readOnly: true},{readOnly: true},{readOnly: true},'.$ro.'],
                      minSpareRows: 0,
                      manualColumnResize: true,
                      rowHeaders: true,
                      columnSorting: true,
                      fixedColumnsLeft: 5,
                      fixedRowsTop: 0,
                      contextMenu: false
                    });

                    var handsontable = $container.data("handsontable");

                    $("#do_save").click(function () {
                      console.log("Saving...");
                      $.ajax({
                        url: "http://retegas.altervista.org/gas2/ordini/rettifiche/rettifica_excellike.php?id_ordine='.$id_ordine.'&do=do_save",
                        data: {"data": handsontable.getData()},
                        dataType: "json",
                        type: "POST",
                        success: function (res) {
                          if (res.result === "ok") {
                            //$console.text("Data saved");
                            console.log("OK saved");
                          }
                          else {
                            //$console.text("Save error");
                            console.log("Save error");
                          }
                        },
                        error: function () {
                         // $console.text("Save error. POST method is not allowed on GitHub Pages. Run this example on your own server to see the success message.");
                        }
                      });
                    });



                    </script>';

if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$ordine = schedina_ordine($id_ordine);
$h = "
<div class=\"rg_widget rg_widget_helper\">
                <h3>Rettifica quantità arrivate tabellare</h3>
                $ordine
                $data
                <div>
                    <p><button id=\"do_save\" class=\"awesome large green\" name=\"save\">salva</button></p>
                    <div id=\"example\" style=\"width: 100%; height: 300px; overflow: scroll\">
                    </div>
                </div>
</div>";

//Questo ?? il contenuto della pagina
$r->contenuto = $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r);