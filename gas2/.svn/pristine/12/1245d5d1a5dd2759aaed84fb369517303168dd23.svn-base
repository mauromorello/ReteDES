<?php


   
// immette i file che contengono il motore del programma
include_once ("../rend.php");
include_once ("../ordini/ordini_renderer.php");
include_once ("../retegas.class.php");



// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    

//Se non è settato il gas lo imposto come quello dell'utente
if(!isset($id_gas)){$id_gas = _USER_ID_GAS;}

if (!(_USER_PERMISSIONS & perm::puo_partecipare_ordini)){
     go("sommario",_USER_ID,"Non puoi partecipare agli ordini. Contatta il tuo referente GAS.");
}

if (ordine_inesistente($id_ordine)){
     go("sommario",_USER_ID,"Ordine insesistente");
}


//Se posso vedere tutti gli ordini
if(!(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini)){

        //Se non sono almeno referente GAS allora non posso vedere nulla.
        $mio_Stato = ordine_io_cosa_sono($id_ordine,_USER_ID);
        if ($mio_Stato<3){
            go("sommario",_USER_ID,"Questo ordine non mi compete");
        }

        //Se sono referente gas controllo di vedere il MIO gas
        if ($mio_Stato==3){
            if($id_gas<>_USER_ID_GAS){
                go("sommario",_USER_ID,"Solo il referente ordine può vedere tutti i gas.");
            }
        }

}


$stato_ordine = stato_from_id_ord($id_ordine);


if($stato_ordine==2){
    $alert = "<div class=\"ui-state-error ui-corner-all padding_6px\">
                <h4>Finchè l'ordine non è confermato, questi dati sono da considerarsi NON ATTENDIBILI<br>
                </h4>
              </div>  ";    
    
}


//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del menù verticale dovrà essere aperta
$r->voce_mv_attiva = menu_lat::gas;
//Assegno il titolo che compare nella barra delle info
$r->title = "Riepilogo spesa utenti per cassa";

$ref_table = "output_1";

//Messaggio popup;
//$r->messaggio = "Pagina di test"; 
//Dico quale menù orizzontale dovrà  essere associato alla pagina.
//$r->menu_orizzontale = ordini_menu_completo($user,$id_ordine);
    
    //SE l'ordine è chiuso allora posso stamparlo
    //if(is_printable_from_id_ord($id_ordine)){
    $r->menu_orizzontale[] = '  <li><a class="medium silver awesome">Esporta</a>
                                    <ul>
                                        <li><a class="awesome medium silver"  href="?id_ordine='.$id_ordine.'&output=html">HTML</a></li>
                                        <li><a class="awesome medium silver"  href="?id_ordine='.$id_ordine.'&output=pdf&cod='.rand(0,999999999).'">PDF</a></li>
                                        <li><a class="awesome medium silver"  href="#" onclick="getCSVData();
                                                                                                $(\'#form_csv\').submit();">CSV</a></li>

                                    </ul>
                                </li>';
    //}
    $r->javascripts_header[]=java_head_table2csv();
    $r->javascripts[]=java_tablesorter($ref_table);
    $r->javascripts[]="<script type=\"text/javascript\">
                        function getCSVData(){
                         var csv_value=$('#$ref_table').table2CSV({delivery:'value'});
                         $(\"#csv_text\").val(csv_value);
                         }
                        </script>";

$r->messaggio = $msg;
//Creo la pagina dell'aggiunta




      $h .= "               <form action=\"cassa_export_valori_ordine.php\"  method =\"post\" id=\"form_csv\" class=\"hidden\"> 
                                        <input type=\"hidden\" name=\"csv_text\" id=\"csv_text\">
                                        <input type=\"hidden\" name=\"output\" value=\"csv\">
                                        <input type=\"hidden\" name=\"id_ordine\" value=\"$id_ordine\"> 
                                        <input type=\"submit\" value=\"Esporta in CSV\" 
                                               onclick=\"getCSVData();\">
                                        </form>
                
                <table id=\"$ref_table\">
                    <thead>     
                        <tr class=\"destra\">
                            <th class=\"sinistra\">Ordine</th>
                            <th class=\"sinistra\">Descrizione</th> 
                            <th class=\"sinistra\">Id Utente</th>
                            <th class=\"sinistra\">Utente</th>
                            <th class=\"sinistra\">Gas</th>
                            <th>Netto</th>
                            <th>Trasporto</th>
                            <th>Gestione</th>
                            <th>Totale Pubblico</th>
                            <th>Costo GAS</th>
                            <th>% GAS</th>
                            <th>Totale Privato</th> 
                        </tr>
                    <thead>
                    <tbody>";


       $col_5 = " class=\"destra\" ";             
                    
       $result = $db->sql_query("SELECT
                                    Sum(retegas_dettaglio_ordini.qta_arr * retegas_articoli.prezzo) as importo_totale,
                                    Sum(retegas_dettaglio_ordini.qta_arr) as somma_articoli,
                                    Count(retegas_dettaglio_ordini.id_articoli) as conto_articoli,
                                    maaking_users.fullname,
                                    maaking_users.userid,
                                    retegas_gas.id_gas,
                                    retegas_gas.descrizione_gas
                                    FROM
                                    retegas_dettaglio_ordini
                                    Inner Join maaking_users ON retegas_dettaglio_ordini.id_utenti = maaking_users.userid
                                    Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
                                    Inner Join retegas_gas ON maaking_users.id_gas = retegas_gas.id_gas
                                    WHERE
                                    retegas_dettaglio_ordini.id_ordine =  '$id_ordine'
                                    GROUP BY
                                    retegas_dettaglio_ordini.id_utenti
                                    ORDER BY Sum(retegas_dettaglio_ordini.qta_arr * retegas_articoli.prezzo) DESC");


       $riga=0;
       $descrizione_ordine = descrizione_ordine_from_id_ordine($id_ordine);
         
         while ($row = $db->sql_fetchrow($result)){
         


              $id_ut = $row["userid"];
              $nome_ut = $row['fullname'];
              $gas_app = $row['descrizione_gas'];
              $id_gas_app = $row['id_gas'];
              $conto_articoli = $row['conto_articoli'];
              $somma_articoli = $row['somma_articoli'];
              $importo_totale = $row["importo_totale"];
              
              
              $totalone = $totalone+ $importo_totale;
              $totalone_articoli = $totalone_articoli + $somma_articoli;
              
              
              $somma_articoli = (float)$somma_articoli;
              
              $costo_trasporto = valore_costo_trasporto_ordine_user($id_ordine,$id_ut);
              $costo_gestione = valore_costo_gestione_ordine_user($id_ordine,$id_ut);
              $costo_mio_gas = valore_costo_mio_gas($id_ordine,$id_ut);
              $costo_maggiorazione = valore_costo_maggiorazione_mio_gas($id_ordine,$id_ut);
              $totale_lordo = $costo_trasporto +
                              $costo_gestione +
                              $costo_mio_gas +
                              $costo_maggiorazione +
                              $importo_totale;    
              $totale_pubblico = $importo_totale +
                                 $costo_gestione +
                                 $costo_trasporto;
                                 
              
              $importo_totale = _nf($importo_totale);
              $totale_lordo = _nf($totale_lordo);
              $totale_pubblico = _nf($totale_pubblico);
              $costo_trasporto = _nf($costo_trasporto);
              $costo_gestione = _nf($costo_gestione);
              $costo_maggiorazione = _nf($costo_maggiorazione);
              $costo_mio_gas = _nf($costo_mio_gas);
              $somma_articoli = _nf($somma_articoli);
              
              $h.= "<tr>";
                $h.= "<td class=\"sinistra\">$id_ordine</td>";
                $h.= "<td class=\"sinistra\">$descrizione_ordine</td>";    
                $h.= "<td class=\"sinistra\">$id_ut</td>";
                $h.= "<td class=\"sinistra\">$nome_ut</td>"; 
                $h.= "<td class=\"sinistra\">$gas_app</td>";
                //$h.= "<td $col_5>$conto_articoli</td>";
                //$h.= "<td $col_5>$somma_articoli</td>";
                $h.= "<td $col_5>$importo_totale</td>";
                $h.= "<td $col_5>$costo_trasporto</td>";
                $h.= "<td $col_5>$costo_gestione</td>";
                $h.= "<td $col_5><b>$totale_pubblico</b></td>";
                $h.= "<td $col_5>$costo_mio_gas</td>";
                $h.= "<td $col_5>$costo_maggiorazione</td>";
                $h.= "<td $col_5><b>$totale_lordo</b></td>";
              $h .="</tr>";

         }//end while


         $totalone_articoli = number_format($totalone_articoli,2,",","");
         $totalone = number_format($totalone,2,",","");
         
         $h.= "</tbody>
               
               </table>";





//Creo l'intestazione per il pdf e l'html
//devo assegnare l'url relativa dell'immagine del logo
//Formattazione PDF e HTML
//Uso lo stesso foglio di stile della pagina video
//a cui sovrappongo un po' di margine ai bordi
//I caratteri sono a punti
$s=load_pdf_styles("../css/");

if(_USER_OPT_NO_HEADER=="SI"){
    $i="";
    $o= "<h3>Ordine $id_ordine (".descrizione_ordine_from_id_ordine($id_ordine).") Riepilogo articoli ".gas_nome($id_gas)."</h3>";

}else{
    $i=load_pdf_header("../images/rg.jpg");
    $o=render_scheda_pdf_ordine($id_ordine).
    "<h3>Riepilogo articoli ".gas_nome($id_gas)."</h3>";;    
}

//Mando all'utente la sua pagina
if($output=="pdf"){
    require_once("../lib/dompdf_2/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
    $dompdf->load_html("<html><head>".$s."</head><body>".$i.$o.$h."</body></html>");
    $dompdf->render();
    $dompdf->stream("riepilogo_soldi_".$id_gas."_$id_ordine-$cod.pdf",array("Attachment" => 0));
die();
    
}elseif($output=="html"){
    echo $s.$i.$o.$h;
}elseif($output=="csv"){
    header('Content-Type: text/csv');
    header("Content-Disposition: attachment; filename=\"ordine_".$id_ordine.".csv\"");
    
    $data=stripcslashes($_REQUEST['csv_text']);
    print $data; 
}else{
    $r->contenuto =     schedina_ordine($id_ordine)
                    ."<div class=\"rg_widget rg_widget_helper\">
                    <h3>Ordine $id_ordine (".descrizione_ordine_from_id_ordine($id_ordine).") (Riepilogo spese utenti)</h3>"
                    .$h
                    ."</div>";
    echo $r->create_retegas();
}
//Distruggo l'oggetto r    
unset($r)   
?>