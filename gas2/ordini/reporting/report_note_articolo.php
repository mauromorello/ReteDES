<?php
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via();
}



//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = menu_lat::ordini;
//Assegno il titolo che compare nella barra delle info
$r->title = "Note Articoli";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
$r->menu_orizzontale = ordini_menu_all($id_ordine);

//Assegno le due tabelle a tablesorter
$r->javascripts[]=java_tablesorter("output_1");


if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}
//Contenuto
$h .= " <div class=\"rg_widget rg_widget_helper\">
                <h3>Lista note su articoli specifici</h3>
                <table id=\"output_1\">
                    <thead>
                        <tr>
                            <th>Utente</th>
                            <th>Gas</th>
                            <th>Articolo</th>
                            <th>Descrizione</th>
                            <th>Misura</th>
                            <th>Q (ord / arr)</th>
                            <th>Sue note:</th>
                        </tr>
                    <thead>
                    <tbody>";


$sql = "select * from retegas_options WHERE id_ordine = '$id_ordine' and chiave='_NOTE_DETTAGLIO';";
$res = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($res)){

    $utenteid = $row["id_user"];
    $nome = fullname_from_id($utenteid);
    $testo = $row["valore_text"];
    $id_articolo = $row["id_articolo"];
    $gas = gas_nome(id_gas_user($utenteid));

    $sqla = "select * from retegas_dettaglio_ordini WHERE id_ordine = '$id_ordine' and id_utenti='$utenteid' and id_articoli='$id_articolo' LIMIT 1;";
    $resa = $db->sql_query($sqla);
    $rowa = $db->sql_fetchrow($resa);

    $descrizione = $rowa["art_desc"];
    $codice = $rowa["art_codice"];
    $um = $rowa["art_um"];
    $q = _nf($rowa["qta_ord"])." / "._nf($rowa["qta_arr"]);

    //$h.= "<p>$nome, ".$row["id_articolo"].", $testo</p>";
   $h .="<tr>";
        $h .="<td>$nome</td>";
        $h .="<td>$gas</td>";
        $h .="<td>$codice</td>";
        $h .="<td>$descrizione</td>";
        $h .="<td>$um</td>";
        $h .="<td>$q</td>";
        $h .="<td>$testo</td>";
   $h .="</tr>";

}
$h .="</tbody>
        </table>";
$h .="</div>";


//Questo ?? il contenuto della pagina
$r->contenuto = schedina_ordine($id_ordine). $h;

//Mando all'utente la sua pagina
echo $r->create_retegas();
//Distruggo l'oggetto r
unset($r)
?>