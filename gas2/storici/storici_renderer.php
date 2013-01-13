<?php

function storici_ordini_miei($id_user){
    global $db; 
    global $RG_addr;
   $id_gas = id_gas_user($id_user);
   $query = "SELECT
                *
                FROM
                retegas_ordini
                Inner Join retegas_dettaglio_ordini ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
                WHERE
                retegas_dettaglio_ordini.id_utenti =  '$id_user'
                AND
                retegas_ordini.id_stato > 2
                GROUP BY
                retegas_ordini.id_ordini
                ORDER BY 
                retegas_ordini.id_ordini DESC";
                
   $result = $db->sql_query($query);
   
   $euro = "&#8364";
   
   $h    = '<div class="rg_widget rg_widget_helper">
            <h3>Ordini ai quali ho partecipato</h3>
            <table id="miei_ordini">
            <thead>
            <tr>
            <th>ID</th>
            <th>Ordine</th>
            <th>Chiuso il</th>
            <th class="{sorter: \'currency\'}">Netto</th>
            <th class="{sorter: \'currency\'}">Trasp.</th>
            <th class="{sorter: \'currency\'}">Gest.</th>
            <th class="{sorter: \'currency\'}">Gas</th>
            <th class="{sorter: \'currency\'}">% Gas</th>
            <th class="{sorter: \'currency\'}">Totale</th>          
            </tr>
            </thead>
            <tbody>';
  
  
   
   $style_number        = "style=\"text-align:right; font-size:0.9em\" ";
   $style_number_small  = "style=\"text-align:right; font-size:0.7em\" ";
         
   while ($row = mysql_fetch_array($result)){

   //azzero i vecchi totali;    
   $riga++;    
       

   $id_ordine           =   $row["id_ordini"];
   $descrizione_ordine  =   $row["descrizione_ordini"];
   $chiuso_il           =   conv_only_date_from_db($row["data_chiusura"]);
    $valore_globale_attuale_netto_qarr = valore_totale_ordine_qarr($id_ordine);
    $valore_miogas_attuale_netto_qarr = valore_totale_mio_gas($id_ordine,$id_gas); 
    $costo_globale_trasporto = valore_trasporto($id_ordine,100);
    $costo_globale_gestione = valore_gestione($id_ordine,100);
    $maggiorazione_percentuale_mio_gas = valore_percentuale_maggiorazione_mio_gas($id_ordine,$id_gas);
    
    $costo_globale_mio_gas = valore_assoluto_costo_mio_gas($id_ordine,$id_gas);
    $costo_maggiorazione_mio_gas = ($valore_miogas_attuale_netto_qarr /100) * $maggiorazione_percentuale_mio_gas;
    
    if($maggiorazione_percentuale_mio_gas>0){
        $motivazione_maggiorazione = "(".testo_maggiorazione_mio_gas($id_ordine,$id_gas).")";
    }      
    
    if($valore_globale_attuale_netto_qarr>0){
        $valore_personale_attuale_netto_qarr = valore_totale_mio_ordine($id_ordine,$id_user);
        $valore_gas_attuale_netto_qarr = valore_totale_mio_gas($id_ordine,$id_gas);
        if($valore_gas_attuale_netto_qarr>0){
            $percentuale_mio_ordine_gas = ($valore_personale_attuale_netto_qarr / $valore_gas_attuale_netto_qarr) *100;    
        }else{
            $percentuale_mio_ordine_gas = 0;
        }
        $percentuale_mio_ordine = ($valore_personale_attuale_netto_qarr / $valore_globale_attuale_netto_qarr) *100;
        $costo_trasporto =  ($costo_globale_trasporto / 100) * $percentuale_mio_ordine;
        $costo_gestione =  ($costo_globale_gestione / 100) * $percentuale_mio_ordine;

        $percentuale_mio_gas = ($valore_miogas_attuale_netto_qarr / $valore_globale_attuale_netto_qarr) *100;
        $costo_trasporto_mio_gas = ($costo_globale_trasporto / 100) * $percentuale_mio_gas;
        $costo_gestione_mio_gas = ($costo_globale_gestione / 100) * $percentuale_mio_gas;
          
        $costo_personale_mio_gas = ($costo_globale_mio_gas /100)*$percentuale_mio_ordine_gas;
        $valore_maggiorazione_mio_gas = ($valore_personale_attuale_netto_qarr / 100) * $maggiorazione_percentuale_mio_gas;
        

        
        
        $totale_ordine =  $valore_personale_attuale_netto_qarr +
                          $costo_trasporto +
                          $costo_gestione +
                          $costo_personale_mio_gas +
                          $valore_maggiorazione_mio_gas ;
                          
                           
        
        
    }else{
        $valore_personale_attuale_netto_qarr = 0;
        $costo_trasporto=   0;
        $costo_gestione=    0;
        $costo_personale_mio_gas =0;
        $valore_maggiorazione_mio_gas =0;
    }
        
        $totale_ordine =    $valore_personale_attuale_netto_qarr +
                            $costo_trasporto +
                            $costo_gestione +
                            $costo_personale_mio_gas +
                            $valore_maggiorazione_mio_gas ; 
   
   
   $h.=       '<tr>';
   $h.=         '<td>'.$id_ordine.'</td>';
   $h.=         '<td><a href="'.$RG_addr["ordini_form"].'?id='.$id_ordine.'">'.$descrizione_ordine.'</a></td>';
   $h.=         '<td>'.$chiuso_il.'</td>';
   $h.=         '<td '.$style_number_small.'>'.number_format($valore_personale_attuale_netto_qarr,2,".","").'</td>';
   $h.=         '<td '.$style_number_small.'>'.number_format($costo_trasporto,2,".","").'</td>';
   $h.=         '<td '.$style_number_small.'>'.number_format($costo_gestione,2,".","").'</td>';
   $h.=         '<td '.$style_number_small.'>'.number_format($costo_personale_mio_gas,2,".","").'</td>';
   $h.=         '<td '.$style_number_small.'>'.number_format($valore_maggiorazione_mio_gas,2,".","").'</td>';
   $h.=         '<td '.$style_number.'>'.number_format($totale_ordine,2,",","").' '.$euro.'</td>';
  
   $h.=       '</tr>';
   
   $totalone_netto = $totalone_netto + $valore_personale_attuale_netto_qarr;
   $totalone_lordo = $totalone_lordo + $totale_ordine;
   
   }
   
   $h   .= '<tfoot>
            <tr>
                <th></th>
                <th>TOTALI ('.$riga.' ordini) :</th>
                <th></th>
                <th>'.number_format($totalone_netto,2,",","").'</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>'.number_format($totalone_lordo,2,",","").' '.$euro.'</th>
            </tr>
            </tfoot>
            </tbody>
            </table>
            </div>';
   
      // END TABELLA ----------------------------------------------------------------------------
return $h;      
    
}
function storici_ditte_gas($id_user,$filter = null,$arg1 = null ,$arg2 = null){
    global $db; 
    global $RG_addr;
   $id_gas = id_gas_user($id_user);
   
   //echo $arg1; 
   $filter_human = "Dati non filtrati";
   
   if ($filter=="date"){
       
       if(controllodata($arg1) AND controllodata($arg2)){
       
       $filter_human = "Data di chiusura ordine compresa tra $arg1 e $arg2";
       
       $argom_1=conv_date_to_db(($arg1));
       $argom_2=conv_date_to_db($arg2); 
      
       $filter_sql = "AND
                     ( retegas_ordini.data_chiusura >= '$argom_1'
                       AND
                       retegas_ordini.data_chiusura < '$argom_2'
                     )
                     ";
       }                               
   }
   
   $query = "SELECT
                retegas_ditte.descrizione_ditte,
                retegas_ditte.id_ditte,
                Sum(retegas_dettaglio_ordini.qta_arr * retegas_articoli.prezzo) as PRZ,
                Sum(retegas_dettaglio_ordini.qta_arr) as QTA
                FROM
                retegas_ordini
                Inner Join maaking_users ON retegas_ordini.id_utente = maaking_users.userid
                Inner Join retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini
                Inner Join retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte
                Inner Join retegas_dettaglio_ordini ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
                Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
                Inner Join maaking_users AS mk2 ON mk2.userid = retegas_dettaglio_ordini.id_utenti
                WHERE
                mk2.id_gas =  '$id_gas'
                AND
                retegas_ordini.id_stato > 2
                $filter_sql
                GROUP BY
                retegas_ditte.id_ditte,
                retegas_ditte.descrizione_ditte";
                
   $result = $db->sql_query($query);
   
   $euro = "&#8364";
   
   $h    = '<div class="rg_widget rg_widget_helper">
            <h3>Ditte alle quali abbiamo ordinato</h3>
            <div class="ui-state-error ui-corner-all">
            <ul>
                <li>I totali per ogni ditta comprendono anche gli ordini proposti da altri gas ai quali utenti del nostro gas hanno partecipato</li>
                <li>I totali sono NETTI, cioè non includono nessuna spesa (trasporto, gestione ecc)</li>
                <li>E\' possibile filtare i dati in base alle chiusure degli ordini.</li>
            </ul>
            </div>
            <br>    
                <div id="filter">
                    <form class="RG_form" method="POST">
                    <label for="data_da">Data iniziale (compresa)</label>
                    <input name="data_da" type="text" id="dat_1" size="10" value="'.$arg1.'"></input>
                    <label for="data_a">Data finale (esclusa)</label>
                    <input name="data_a" type="text" id="dat_2" size="10" value="'.$arg2.'"></input>
                    <input type="hidden" name="do" value="filter">
                    <input type="hidden" name="filter" value="date">
                    <input type="submit" name="submit" value="Filtra i dati" align="left" >
                    </form>           

            <br>
            <strong>'.$filter_human.'</strong>
            
            <table id="miei_ordini">
            <thead>
            <tr>
            <th>Nome Ditta</th>
            <th class="{sorter: \'currency\'}">Totale</th>          
            </tr>
            </thead>
            <tbody>';
  
  
   
   $style_number        = "style=\"text-align:right; font-size:0.9em\" ";
   $style_number_small  = "style=\"text-align:right; font-size:0.7em\" ";
         
   while ($row = mysql_fetch_array($result)){

   //azzero i vecchi totali;    
   $riga++;    

   $ditta = $row["descrizione_ditte"];
   $totale_ordine = $row["PRZ"];
   
   $totale_max = $totale_max + $totale_ordine;
   
   
   $h.=       '<tr>';
   $h.=         '<td><a href="'.$RG_addr["form_ditta"].'?id='.$row["id_ditte"].'">'.$ditta.'</a></td>';
   $h.=         '<td '.$style_number.'>'.number_format($totale_ordine,2,",","").' '.$euro.'</td>';
   $h.=       '</tr>';
   

   
   }
   
   $h   .= '<tfoot>
            <tr>
                <th>TOTALI ('.$riga.' ditte) :</th>
                <th '.$style_number.'>'.number_format($totale_max,2,",","").' '.$euro.'</th>
            </tr>
            </tfoot>
            </tbody>
            </table>
            </div>';
   
      // END TABELLA ----------------------------------------------------------------------------
return $h;      
    
}
function storici_ditte_gas_grafico($id_user,$filter = null,$arg1 = null ,$arg2 = null){
    global $db; 
    global $RG_addr;
   $id_gas = id_gas_user($id_user);
   
   //echo $arg1; 
   $filter_human = "Dati non filtrati";
   
   if ($filter=="date"){
       
       if(controllodata($arg1) AND controllodata($arg2)){
       
       $filter_human = "Data di chiusura ordine compresa tra $arg1 e $arg2";
       
       $argom_1=conv_date_to_db(($arg1));
       $argom_2=conv_date_to_db($arg2); 
      
       $filter_sql = "AND
                     ( retegas_ordini.data_chiusura >= '$argom_1'
                       AND
                       retegas_ordini.data_chiusura < '$argom_2'
                     )
                     ";
       }                               
   }
   
   $query = "SELECT
                retegas_ditte.descrizione_ditte,
                retegas_ditte.id_ditte,
                Sum(retegas_dettaglio_ordini.qta_arr * retegas_articoli.prezzo) as PRZ,
                Sum(retegas_dettaglio_ordini.qta_arr) as QTA
                FROM
                retegas_ordini
                Inner Join maaking_users ON retegas_ordini.id_utente = maaking_users.userid
                Inner Join retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini
                Inner Join retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte
                Inner Join retegas_dettaglio_ordini ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
                Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
                Inner Join maaking_users AS mk2 ON mk2.userid = retegas_dettaglio_ordini.id_utenti
                WHERE
                mk2.id_gas =  '$id_gas'
                AND
                retegas_ordini.id_stato > 2
                $filter_sql
                GROUP BY
                retegas_ditte.id_ditte,
                retegas_ditte.descrizione_ditte
                ORDER BY Sum(retegas_dettaglio_ordini.qta_arr * retegas_articoli.prezzo) DESC";
                
   $result = $db->sql_query($query);
   

         
   while ($row = mysql_fetch_array($result)){


   $ditta = $row["descrizione_ditte"];
   $totale_ordine = $row["PRZ"];
   

   
   //['Firefox',   45.0],
   //['IE',       26.8],
   
   $h.=       '[\''.substr(strip_tags($ditta),0,30)." ...".'\', '.number_format($totale_ordine,2,",","").'], 
   ';
 
   
   }
   $h = rtrim($h,",");
   
      // END TABELLA ----------------------------------------------------------------------------
return $h;      
    
}
function storici_ditte_des($id_user,$filter = null,$arg1 = null ,$arg2 = null){
    global $db; 
    global $RG_addr;
   $id_gas = id_gas_user($id_user);
   
   //echo $arg1; 
   $filter_human = "Dati non filtrati";
   
   if ($filter=="date"){
       
       if(controllodata($arg1) AND controllodata($arg2)){
       
       $filter_human = "Data di chiusura ordine compresa tra $arg1 e $arg2";
       
       $argom_1=conv_date_to_db(($arg1));
       $argom_2=conv_date_to_db($arg2); 
      
       $filter_sql = "AND
                     ( retegas_ordini.data_chiusura >= '$argom_1'
                       AND
                       retegas_ordini.data_chiusura < '$argom_2'
                     )
                     ";
       }                               
   }
   
   $query = "SELECT
                retegas_ditte.descrizione_ditte,
                retegas_ditte.id_ditte,
                Sum(retegas_dettaglio_ordini.qta_arr * retegas_articoli.prezzo) as PRZ,
                Sum(retegas_dettaglio_ordini.qta_arr) as QTA
                FROM
                retegas_ordini
                Inner Join maaking_users ON retegas_ordini.id_utente = maaking_users.userid
                Inner Join retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini
                Inner Join retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte
                Inner Join retegas_dettaglio_ordini ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
                Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
                Inner Join maaking_users AS mk2 ON mk2.userid = retegas_dettaglio_ordini.id_utenti
                WHERE
                retegas_ordini.id_stato > 2
                $filter_sql
                GROUP BY
                retegas_ditte.id_ditte,
                retegas_ditte.descrizione_ditte";
                
   $result = $db->sql_query($query);
   
   $euro = "&#8364";
   
   $h    = '<div class="rg_widget rg_widget_helper">
            <h3>Ditte alle quali abbiamo ordinato (DES)</h3>
            <div class="ui-state-error ui-corner-all">
            <ul>
                <li>I totali sono NETTI, cioè non includono nessuna spesa (trasporto, gestione ecc)</li>
                <li>E\' possibile filtare i dati in base alle chiusure degli ordini.</li>
            </ul>
            </div>
            <br>    
                <div id="filter">
                    <form class="RG_form" method="POST">
                    <label for="data_da">Data iniziale (compresa)</label>
                    <input name="data_da" type="text" id="dat_1" size="10" value="'.$arg1.'"></input>
                    <label for="data_a">Data finale (esclusa)</label>
                    <input name="data_a" type="text" id="dat_2" size="10" value="'.$arg2.'"></input>
                    <input type="hidden" name="do" value="filter">
                    <input type="hidden" name="filter" value="date">
                    <input type="submit" name="submit" value="Filtra i dati" align="left" >
                    </form>           

            <br>
            <strong>'.$filter_human.'</strong>
            
            <table id="miei_ordini">
            <thead>
            <tr>
            <th>Nome Ditta</th>
            <th class="{sorter: \'currency\'}">Totale</th>          
            </tr>
            </thead>
            <tbody>';
  
  
   
   $style_number        = "style=\"text-align:right; font-size:0.9em\" ";
   $style_number_small  = "style=\"text-align:right; font-size:0.7em\" ";
         
   while ($row = mysql_fetch_array($result)){

   //azzero i vecchi totali;    
   $riga++;    

   $ditta = $row["descrizione_ditte"];
   $totale_ordine = $row["PRZ"];
   
   $totale_max = $totale_max + $totale_ordine;
   
   
   $h.=       '<tr>';
   $h.=         '<td><a href="'.$RG_addr["form_ditta"].'?id='.$row["id_ditte"].'">'.$ditta.'</a></td>';
   $h.=         '<td '.$style_number.'>'.number_format($totale_ordine,2,",","").' '.$euro.'</td>';
   $h.=       '</tr>';
   

   
   }
   
   $h   .= '<tfoot>
            <tr>
                <th>TOTALI ('.$riga.' ditte) :</th>
                <th '.$style_number.'>'.number_format($totale_max,2,",","").' '.$euro.'</th>
            </tr>
            </tfoot>
            </tbody>
            </table>
            </div>';
   
      // END TABELLA ----------------------------------------------------------------------------
return $h;      
    
}



function storici_ditte_mie($id_user){
    global $db; 
    global $RG_addr;
   $id_gas = id_gas_user($id_user);
   
   $query = "SELECT
retegas_ditte.descrizione_ditte,
Sum(retegas_dettaglio_ordini.qta_arr * retegas_articoli.prezzo) as PRZ,
Sum(retegas_dettaglio_ordini.qta_arr)
FROM
retegas_ordini
Inner Join maaking_users ON retegas_ordini.id_utente = maaking_users.userid
Inner Join retegas_listini ON retegas_ordini.id_listini = retegas_listini.id_listini
Inner Join retegas_ditte ON retegas_listini.id_ditte = retegas_ditte.id_ditte
Inner Join retegas_dettaglio_ordini ON retegas_ordini.id_ordini = retegas_dettaglio_ordini.id_ordine
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
Inner Join maaking_users AS mk2 ON mk2.userid = retegas_dettaglio_ordini.id_utenti
WHERE
retegas_dettaglio_ordini.id_utenti =  '$id_user'
AND
retegas_ordini.id_stato > 2
GROUP BY
retegas_ditte.id_ditte,
retegas_ditte.descrizione_ditte";
                
   $result = $db->sql_query($query);
   
   $euro = "&#8364";
   
   $h    = '<div class="rg_widget rg_widget_helper">
            <h3>Ditte alle quali ho ordinato</h3>
            <div class="ui-state-error ui-corner-all">
            <ul>
                <li>I totali sono NETTI, cioè non includono nessuna spesa (trasporto, gestione ecc)</li>
                <li>Per ora non è possibile nessun tipo di filtro sui dati.</li>
                <li>I dati si riferiscono a tutto il periodo che va dal primo all\'ultimo ordine chiuso.</li>
            </ul>
            <br>
            </div>
            <table id="miei_ordini">
            <thead>
            <tr>
            <th>Nome Ditta</th>
            <th class="{sorter: \'currency\'}">Totale</th>          
            </tr>
            </thead>
            <tbody>';
  
  
   
   $style_number        = "style=\"text-align:right; font-size:0.9em\" ";
   $style_number_small  = "style=\"text-align:right; font-size:0.7em\" ";
         
   while ($row = mysql_fetch_array($result)){

   //azzero i vecchi totali;    
   $riga++;    

   $ditta = $row["descrizione_ditte"];
   $totale_ordine = $row["PRZ"];
   
   $totale_max = $totale_max + $totale_ordine;
   
   
   $h.=       '<tr>';
   $h.=         '<td><a href="'.$RG_addr["form_ditta"].'?id='.$row["id_ditte"].'">'.$ditta.'</a></td>';
   $h.=         '<td '.$style_number.'>'.number_format($totale_ordine,2,",","").' '.$euro.'</td>';
   $h.=       '</tr>';
   

   
   }
   
   $h   .= '<tfoot>
            <tr>
                <th>TOTALI ('.$riga.' ditte) :</th>
                <th '.$style_number.'>'.number_format($totale_max,2,",","").' '.$euro.'</th>
            </tr>
            </tfoot>
            </tbody>
            </table>
            </div>';
   
      // END TABELLA ----------------------------------------------------------------------------
return $h;      
    
}

//Famiglie
function storici_famiglie_gas($filter = null,$arg1 = null ,$arg2 = null){
    global $db; 
    global $RG_addr;
   $id_gas = id_gas_user(_USER_ID);
   
   //echo $arg1; 
   $filter_human = "Dati non filtrati";
   
   if ($filter=="date"){
       
       if(controllodata($arg1) AND controllodata($arg2)){
       
       $filter_human = "Data di chiusura ordine compresa tra $arg1 e $arg2";
       
       $argom_1=conv_date_to_db(($arg1));
       $argom_2=conv_date_to_db($arg2); 
      
       $filter_sql = "AND
                     ( retegas_dettaglio_ordini.data_inserimento >= '$argom_1'
                       AND
                       retegas_dettaglio_ordini.data_inserimento < '$argom_2'
                     )
                     ";
       }                               
   }
   
   $query = "SELECT
maaking_users.fullname,
maaking_users.userid,
Count(retegas_dettaglio_ordini.id_dettaglio_ordini),
Sum(retegas_dettaglio_ordini.qta_arr),
Sum(retegas_dettaglio_ordini.qta_arr*retegas_articoli.prezzo) AS PRZ
FROM
maaking_users
Right Join retegas_dettaglio_ordini ON maaking_users.userid = retegas_dettaglio_ordini.id_utenti
Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
WHERE
maaking_users.id_gas =  '$id_gas'
$filter_sql
GROUP BY
maaking_users.fullname";
                
   $result = $db->sql_query($query);
   
   $euro = "&#8364;";
   
   $h    = '<div class="rg_widget rg_widget_helper">
            <h3>Spesa singoli utenti</h3>
            <div class="ui-state-error ui-corner-all">
            <ul>
                <li>I totali per ogni utente comprendono anche gli ordini gestiti da altri gas ai quali ha partecipato</li>
                <li>I totali sono NETTI, cioè non includono nessuna spesa (trasporto, gestione ecc)</li>
                <li>E\' possibile filtare i dati in base alle date di inserimento ordinativi.</li>
            </ul>
            </div>
            <br>    
                <div id="filter">
                    <form class="RG_form" method="POST">
                    <label for="data_da">Data iniziale (compresa)</label>
                    <input name="data_da" type="text" id="dat_1" size="10" value="'.$arg1.'"></input>
                    <label for="data_a">Data finale (esclusa)</label>
                    <input name="data_a" type="text" id="dat_2" size="10" value="'.$arg2.'"></input>
                    <input type="hidden" name="do" value="filter">
                    <input type="hidden" name="filter" value="date">
                    <input type="submit" name="submit" value="Filtra i dati" align="left" >
                    </form>           

            <br>
            <strong>'.$filter_human.'</strong>
            
            <table id="miei_ordini">
            <thead>
            <tr>
            <th class="sinistra">Utente</th>
            <th class="sinistra">Ordini come referente</th>
            <th class="sinistra">Ordini come partecipante</th>
            <th class="{sorter: \'currency\'}">Totale</th>          
            </tr>
            </thead>
            <tbody>';
  
  
   
   $style_number        = "style=\"text-align:right; font-size:0.9em\" ";
  
   $style_number_small  = "style=\"text-align:right; font-size:0.7em\" ";
         
   while ($row = mysql_fetch_array($result)){

   //azzero i vecchi totali;    
   $riga++;    

   $utente = $row["fullname"];
   $totale_ordine = $row["PRZ"];
   
   $totale_max = $totale_max + $totale_ordine;
   
   $ref_ordine =ordini_user($row["userid"]);
   $part_ordine = ordini_user_partecipato($row["userid"]) ;
   
   
   
   $h.=       '<tr>';
   $h.=         '<td><a href="'.$RG_addr["user_form_public"].'?id_utente='.mimmo_encode($row["userid"]).'">'.$utente.'</a></td>';
   $h.=         '<td>'.$ref_ordine.'</td>';
   $h.=         '<td>'.$part_ordine.'</td>';
   $h.=         '<td '.$style_number.'>'.number_format($totale_ordine,2,",","").' '.$euro.'</td>';
   $h.=       '</tr>';
   

   
   }
   
   $h   .= '<tfoot>
            <tr>
                <th>TOTALI ('.$riga.' utenti) :</th>
                <th>&nbsp</th>
                <th>&nbsp</th>
                <th '.$style_number.'>'.number_format($totale_max,2,",","").' '.$euro.'</th>
            </tr>
            </tfoot>
            </tbody>
            </table>
            </div>';
   
      // END TABELLA ----------------------------------------------------------------------------
return $h;      
    
}
function storici_famiglie_gas_grafico($filter = null,$arg1 = null ,$arg2 = null){
    global $db; 
    global $RG_addr;
   $id_gas = id_gas_user(_USER_ID);
   
   //echo $arg1; 
   $filter_human = "Dati non filtrati";
   
   if ($filter=="date"){
       
       if(controllodata($arg1) AND controllodata($arg2)){
       
       $filter_human = "Data di chiusura ordine compresa tra $arg1 e $arg2";
       
       $argom_1=conv_date_to_db(($arg1));
       $argom_2=conv_date_to_db($arg2); 
      
       $filter_sql = "AND
                     ( retegas_dettaglio_ordini.data_inserimento >= '$argom_1'
                       AND
                       retegas_dettaglio_ordini.data_inserimento < '$argom_2'
                     )
                     ";
       }                               
   }
   
   $query = "SELECT
            maaking_users.fullname,
            maaking_users.userid,
            Count(retegas_dettaglio_ordini.id_dettaglio_ordini),
            Sum(retegas_dettaglio_ordini.qta_arr),
            Sum(retegas_dettaglio_ordini.qta_arr*retegas_articoli.prezzo) AS PRZ
            FROM
            maaking_users
            Inner Join retegas_dettaglio_ordini ON maaking_users.userid = retegas_dettaglio_ordini.id_utenti
            Inner Join retegas_articoli ON retegas_dettaglio_ordini.id_articoli = retegas_articoli.id_articoli
            WHERE
            maaking_users.id_gas =  '$id_gas'
            $filter_sql
            GROUP BY
            maaking_users.fullname
                ORDER BY PRZ DESC";
                
   $result = $db->sql_query($query);
   

         
   while ($row = mysql_fetch_array($result)){


   $utente = $row["fullname"];
   $totale_ordine = $row["PRZ"];
   

   
   //['Firefox',   45.0],
   //['IE',       26.8],
   
   $h.=       '[\''.substr(strip_tags($utente),0,15)." ...".'\', '.number_format($totale_ordine,2,",","").'], 
   ';
 
   
   }
   $h = rtrim($h,",");
   
      // END TABELLA ----------------------------------------------------------------------------
return $h;      
    
}
