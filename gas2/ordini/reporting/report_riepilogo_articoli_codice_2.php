<?php

   
// immette i file che contengono il motore del programma
include_once ("../../rend.php");
include_once ("../../retegas.class.php");


// controlla se l'user ha effettuato il login oppure no
if (!_USER_LOGGED_IN){
     pussa_via(); 
}    
if(!(_USER_PERMISSIONS & perm::puo_vedere_tutti_ordini)){
    if(!posso_gestire_ordine_full($id_ordine,_USER_ID)){
        go("ordini_form",_USER_ID,"Questa operazione ti è preclusa.","?id_ordine=$id_ordine");
        exit;
    }
}

//Creazione della nuova pagina uso un oggetto rg_simplest
$r = new rg_simplest();
//Dico quale voce del men? verticale dovr? essere aperta
$r->voce_mv_attiva = 0;
//Assegno il titolo che compare nella barra delle info
$r->title = "Articoli per codice (raggruppati per gas)";


//Dico quale men? orizzontale dovr?? essere associato alla pagina.
 $r->menu_orizzontale[] = '  <li><a class="medium silver awesome">Esporta</a>
                                    <ul>
                                        <li><a class="awesome medium silver"  href="?id_ordine='.$id_ordine.'&output=html">Versione stampabile</a></li>
                                        <li><a class="awesome medium silver"  href="?id_ordine='.$id_ordine.'&output=pdf&cod='.rand(0,999999999).'">Pdf</a></li>
                                    </ul>
                                </li>';
    //}
$r->menu_orizzontale = array_merge(ordini_menu_all($id_ordine),$r->menu_orizzontale);



if(_USER_HAVE_MSG){
    $r->messaggio = _USER_MSG;
    delete_option_text(_USER_ID,"MSG");
}

//CONTENT
      // QUERY LISTINI
      $my_query="SELECT
                    retegas_dettaglio_ordini.id_dettaglio_ordini,
                    retegas_dettaglio_ordini.id_utenti,
                    retegas_dettaglio_ordini.id_articoli,
                    retegas_dettaglio_ordini.id_stati,
                    retegas_dettaglio_ordini.data_inserimento,
                    retegas_dettaglio_ordini.data_convalida,
                    retegas_dettaglio_ordini.qta_ord,
                    retegas_dettaglio_ordini.id_amico,
                    retegas_dettaglio_ordini.id_ordine,
                    retegas_dettaglio_ordini.qta_conf,
                    retegas_dettaglio_ordini.qta_arr,
                    retegas_dettaglio_ordini.timestamp_ord,
                    retegas_articoli.id_articoli,
                    retegas_articoli.id_listini,
                    retegas_articoli.codice,
                    retegas_articoli.u_misura,
                    retegas_articoli.misura,
                    retegas_articoli.descrizione_articoli,
                    retegas_articoli.qta_scatola,
                    retegas_articoli.prezzo,
                    retegas_articoli.ingombro,
                    retegas_articoli.qta_minima,
                    retegas_articoli.qta_multiplo,
                    retegas_articoli.articoli_note
                    FROM
                    retegas_dettaglio_ordini
                    Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
                    WHERE
                    retegas_dettaglio_ordini.id_ordine =  '$id_ordine'
                    ORDER BY retegas_articoli.codice ASC, retegas_dettaglio_ordini.qta_ord DESC
                    LIMIT 1000;";
      
      
      
      // COSTRUZIONE TABELLA  LISTINI -----------------------------------------------------------------------
      
      $result = $db->sql_query($my_query);
        
          
    $h_table .= '<table>';
    $h_table .= '<thead>
                    <tr>
                        <th class="sinistra">Codice</th>
                        <th class="sinistra">Descrizione</th>
                        <th class="sinistra">Gas</th>
                        <th class="sinistra">Utente</th>
                        <th>Q ord.</th>
                        <th>Q arr.</th>
                        <th>Scatole</th>
                        <th>Avanzo</th>
                    </tr>
                 </thead>
                 <tbody>';
    $riga=0;  
       
       
       while ($row = mysql_fetch_array($result)){
           
  
           
           
              $riga++;
              $c0 =$row["id_articoli"];    
              $c1 = fullname_from_id($row["id_utenti"]);
              $c2 = $row["codice"];
              $c3 = $row["descrizione_articoli"];
              $c4 = round($row["qta_ord"],2);
              $c5 = round($row["qta_arr"],2);             

              $c8 = gas_user($row["id_utenti"]);
              $c9 = $row["qta_scatola"]; 
              $c10 =q_articoli_avanzo_articolo_singolo($c9,$c5);
              $c11 =q_scatole_intere_articolo_singolo($c9,$c5);
              
              if($c10==0){$c10="$nbsp";}
              if($c11==0){$c11="$nbsp";}
              
              $misu = "(". $row["u_misura"] ." ". $row["misura"].")";
    
              
              $id_art= $row["id_articoli"];// ID articolo
              
               if($c4<>$c5){
                if(($c5==0) or (empty($c5))){
                  $warning = "<div class=\"campo_alert\">ANNULLATA</div>";
                  }else{
                  $warning = "<div class=\"campo_alert\">MODIFICATA</div>";  
                }   
               }else{
                    $warning = "";    
               }
           

            
            if($old_amico==$row["codice"]){$c2_echo="$nbsp";$c3_echo="$nbsp";$misu_echo="$nbsp";}else
                                      {$c2_echo=$c2;$c3_echo=$c3;$misu_echo=$misu;}
                   $vecchi_codici = $c2 ." - ".$c3; 
           
           if (($old_amico<>$row["codice"]) | $riga==0){
                include("oc_lis_art_subtotal.php");    
           }
        
        if(is_integer($riga/2)){  
            $h_table.= "<tr class=\"odd\">";    // Colore Riga
        }else{
            $h_table.= "<tr>";    
        }
    
       
        
        $h_table.= "        
                            <td $col_1>$c2_echo</td> 
                            <td $col_2>$c3_echo $misu_echo</td> 
                            <td $col_3>$c8</td>     
                            <td $col_4>$c1</td>                
                            <td $col_5>$c4</td>                 
                            <td class=\"centro\">$c5<br />$warning</td>             
                            <td class=\"centro\">$c11</td> 
                            <td class=\"centro\">$c10</td> 
                            <td class=\"centro\">$nbsp</td> 
                            </tr>
        ";

           
         $old_amico = $row["codice"];   
         }//end while
         
         $h = $h_table . '</tbody></table>';


//Creo l'intestazione per il pdf e l'html
//devo assegnare l'url relativa dell'immagine del logo
//Formattazione PDF e HTML
//Uso lo stesso foglio di stile della pagina video
//a cui sovrappongo un po' di margine ai bordi
//I caratteri sono a punti
$title_outp = "Riepilogo per codice";
$s=load_pdf_styles("../../css/");

if(_USER_OPT_NO_HEADER=="SI"){
    $i="";
    $o= "<h2>Ordine $id_ordine (".descrizione_ordine_from_id_ordine($id_ordine).") Riepilogo articoli tutti i GAS</h2>";

}else{
    $i=load_pdf_header("../../images/rg.jpg");
    $o=render_scheda_pdf_ordine($id_ordine).
    "<h2>$title_outp</h2>";;    
}

//Mando all'utente la sua pagina
if($output=="pdf"){
    require_once("../../lib/dompdf_3/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
    $dompdf->load_html("<html><head>".$s."</head><body>".$i.$o.$h."</body></html>");
    $dompdf->render();
    $dompdf->stream("riepilogo_articoli_2_$id_ordine-$cod.pdf",array("Attachment" => 0));
die();
    
}elseif($output=="html"){
    echo $s.$i.$o.$h;
}else{
    $r->contenuto = schedina_ordine($id_ordine)
                    ."<div class=\"rg_widget rg_widget_helper\">
                    <h2>$title_outp</h2>"
                    .$h
                    ."</div>";
    echo $r->create_retegas();
}
//Distruggo l'oggetto r     
unset($r)   
?>