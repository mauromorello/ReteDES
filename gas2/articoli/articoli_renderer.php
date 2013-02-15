<?php

function articoli_render_add($id){


        global $db;     
        global $codice,
                $descrizione,
                $u_misura,
                $misura,
                $prezzo,
                $qta_scatola,
                $qta_multiplo,
                $univoco,
                $ingombro,
                $note_articolo,
                $opz_1,
                $opz_2,
                $opz_3;


        if(isset($univoco)){$SU=" SELECTED ";}else{$SN=" SELECTED ";}                      
                
        $help_codice ='Questo è il codice che serve AL FORNITORE per identificare l\'articolo. Se il fornitore non ha un codice specifico, crearne uno seguendo un minimo di logica (ES "001" oppure "A" oppure "COD.1") Fate attenzione nel caso della numerazione ordinale, di porre sempre degli zeri davanti al codice (Es 001) anzichè solo "1" , perchè poi potrebbe risultare difficile metterli nell\'ordine voluto. Un buon metodo è chiamarli "010" "020" "030" "040" ecc.... che permette di infilare tra un articolo e l\'altro altri articoli.';
        $help_descrizione='Inserisci una descrizione chiara e concisa di cosa si sta trattando.';
        $help_u_misura ="L'unità di misura con cui viene venduto l'oggetto (N. ,  Kg, Lt. Pz, Gr. , Bancali, Borse, Cassette, Vasetti). E' da leggersi assieme alla prossima voce.";
        $help_misura ="E' legata alla voce precedente, e compone L'unità di vendita (UDV), il cui prezzo è definito dalla prossima voce.";
        $help_prezzo ="Il prezzo riferito all'UDV (unità di vendita), definita nelle voci 3 e 4. Tutto assieme si potrebbe leggere :  1 (M) KG (UDM)   a 5 Eu. (P)";
        $help_qta_scatola="Una scatola, od un contenitore, può contenere n UDV.  Ad esempio, i pacchetti di pasta singoli sono venduti in scatole da 12 UDV";
        $help_qta_multiplo="Qual'è la quantità minima di UDV che può essere venduta. La scatola  deve essere un suo multiplo. Sempre prendendo la pasta come esempio, io posso avere una scatola da 12 UDV, ma decido che il minimo multiplo (MM) è 3. Questo vuol dire che potrò comprare un minimo di tre pacchetti, e con 12 pacchetti completo una scatola. La scatola può essere naturalmente completata sia dallo stesso utente che da utenti diversi";
        $help_univoco ="se si inserisce -UNIVOCO- in questa voce, vorrà dire che ogni ordine fatto di quell'articolo verrà trattato singolarmente. Ad esempio il parmigiano, se ne acquisto 5 pezzi da 1kg, nell'ordine fatto mi risulteranno 5 richieste da 1 kg a mio carico. Questo serve per poi poter trattare ogni pezzo singolarmente, e modificarne le quantità arrivate od il prezzo.";
        $help_ingombro ="Note Brevi";
        $help_note_articolo ="Le note lunghe possono contenere anche immagini, se importate correttamente da siti esterni a reteDES,";
        
        $help_opz_1 = 
        $help_opz_2 =
        $help_opz_3 ="Le voci 11 12 13 concorrono a formare un articolo RAGGRUPPABILE. Questa opzione serve a gestire le caratteristiche diverse di gruppi di articoli simili, ad esempio taglie, numeri di scarpa, misure dei vestiti, colori di oggetti ecc ecc. Per usarla è più conveniente passare al caricamento multiplo da excel. Nel caso di articoli singoli LASCIARE VUOTO";
        
        $h = '<div class="rg_widget rg_widget_helper">
        <h3>Inserisci un articolo singolo nel listino "'.listino_nome($id).'"</h3>

        <form name="Nuovo ordine Veloce" method="POST" action="articoli_form_add.php" class="retegas_form">

        <div>
        <h4>1</h4>
        <label for="codice">Inserisci il codice di questo articolo</label>
        <input type="text" name="codice" value="'.$codice.'" size="20"></input>
        <h5 title="'.$help_codice.'">Inf.</h5>
        </div>
        
        <div>
        <h4>2</h4>
        <label for="descrizione">... poi una sua descrizione</label>
        <input type="text" name="descrizione" value="'.$descrizione.'" size="50"></input>
        <h5 title="'.$help_descrizione.'">Inf.</h5>
        </div> 
        
        <div>
        <h4>3</h4>
        <label for="u_misura">ora l\'unità di misura con il quale viene venduto (Kg, Lt, Pz, N., Gr, ecc)</label>
        <input type="text" name="u_misura" value="'.$u_misura.'" size="10"></input>
        <h5 title="'.$help_u_misura.'">Inf.</h5>
        </div>

        <div>
        <h4>4</h4>
        <label for="misura">... e logicamente anche la quantità...</label>
        <input type="text" name="misura" value="'.$misura.'" size="10"></input>
        <h5 title="'.$help_misura.'">Inf.</h5>
        </div>
        
        <div>
        <h4>5</h4>
        <label for="prezzo">..ed il prezzo di vendita.</label>
        <input type="text" name="prezzo" value="'.$prezzo.'" size="10"></input>
        <h5 title="'.$help_prezzo.'">Inf.</h5>
        </div>
        
        <div>
        <h4>6</h4>
        <label for="qta_scatola">Adesso inserisci quante delle unità sopra descritte sono contenute in una scatola;</label>
        <input type="text" name="qta_scatola" value="'.$qta_scatola.'" size="10"></input>
        <h5 title="'.$help_qta_scatola.'">Inf.</h5>
        </div>
        
        <div>
        <h4>7</h4>
        <label for="qta_multiplo">... e qual\'è la minima quantità vendibile (la "scatola" deve essere un multiplo di questo numero)</label>
        <input type="text" name="qta_multiplo" value="'.$qta_multiplo.'" size="10"></input>
        <h5 title="'.$help_qta_multiplo.'">Inf.</h5>
        </div>
        
        <div>
        <h4>8</h4>
        <label for="univoco">Qua decidi se sarà un articolo normale oppure univoco</label>
        <!--<input type="text" name="univoco" value="'.$univoco.'" size="50"></input>-->
        <select name="univoco">
          <option value=""          '.$SN.'>Normale</option>
          <option value="UNIVOCO"   '.$SU.'>Univoco</option>
        </select>
        
        <h5 title="'.$help_univoco.'">Inf.</h5>
        </div>
        
        <div>
        <h4>9</h4>
        <label for="ingombro">Se vuoi puoi mettere delle note brevi...</label>
        <input type="text" name="ingombro" value="'.$ingombro.'" size="50"></input>
        <h5 title="'.$help_ingombro.'">Inf.</h5>
        </div>
        
        <div>
        <h4>10</h4>

        <label for="note_articolo">..oppure delle note più corpose, con magari dei link o delle immagini</label>
        <h5 title="'.$help_note_articolo.'">Inf.</h5>
        <textarea id="note_articolo" class ="ckeditor" name="note_articolo" cols="28" style="display:inline-block;">'.$note_ordine.'</textarea>
        
        </div>
        
        <div>
        <h4>11</h4>
        <label for="opz_1">Se l\'articolo fa parte di un insieme raggruppabile qua inserisci il primo livello del raggruppamento</label>
        <input type="text" name="opz_1" value="'.$opz_1.'" size="20"></input>
        <h5 title="'.$help_opz_1.'">Inf.</h5>
        </div>
        
        <div>
        <h4>12</h4>
        <label for="opz_2">... qua il secondo....</label>
        <input type="text" name="opz_2" value="'.$opz_1.'" size="20"></input>
        <h5 title="'.$help_opz_2.'">Inf.</h5>
        </div>
        
        <div>
        <h4>13</h4>
        <label for="opz_3">...ed il terzo;</label>
        <input type="text" name="opz_3" value="'.$opz_3.'" size="20"></input>
        <h5 title="'.$help_opz_3.'">Inf.</h5>
        </div>
        
        
        <div>
        <h4>14</h4>
        <label for="submit">infine... </label>
        <input type="submit" name="submit" value="Aggiungi questo nuovo articolo" align="center" >
        <input type="hidden" name="do" value="add">
        <input type="hidden" name="id" value="'.$id.'">
        </div> 


        </form>
        ';              


        return $h;      

}
function articoli_render_do_add($user,$id_listino){
    
    global $db;    
    global      $codice,
                $descrizione,
                $u_misura,
                $misura,
                $prezzo,
                $qta_scatola,
                $qta_multiplo,
                $univoco,
                $ingombro,
                $note_articolo,
                $opz_1,
                $opz_2,
                $opz_3;
     // se ? vuoto
      
      if (empty($codice)){$msg.="Devi almeno inserire il nome dell'articolo<br>";$e_empty++;};
      if (empty($u_misura)){$msg.="Devi inserire l'unità di misura (Kg, Lt, Gr, Hg, ecc ecc)<br>";$e_empty++;};
      if (empty($misura)){$msg.="Devi inserire la misura<br>";$e_empty++;};
      if (empty($descrizione)){$msg.="Devi inserire la descrizione articolo<br>";$e_empty++;};
      if (empty($qta_scatola)){$msg.="Devi inserire la quantità scatola<br>";$e_empty++;};
      if (empty($prezzo)){$msg.="Devi inserire il prezzo riferito alla quantità indicata in \"misura\"<br>";$e_empty++;};
      if (empty($qta_multiplo)){$msg.="Devi inserire la quantità multiplo.<br>";$e_empty++;};
      
      if (empty($univoco)){$univoco=0;}else{$univoco=1;};
      
      if (empty($id_listino)){$msg.="L'articolo deve essere riferito ad un listino.<br>";$e_empty++;};
      
      
      // se il prezzo ? valuta valida
      $prezzo=floatval(trim(str_replace(array(",","?"),array(".",""),$prezzo))); 
         
      if (!valuta_valida($prezzo)){$msg.="Il prezzo non è in formato valido.<br>";$e_currency++;}; 
      
      
      // se il codice esiste gi?
      
      $result = $db->sql_query("SELECT retegas_articoli.id_articoli
                                FROM retegas_articoli
                                WHERE (((retegas_articoli.codice)=\"$codice\") AND ((retegas_articoli.id_listini)=$id_listino));");    
      if (mysql_numrows($result)>0){$msg.="Esiste già un articolo in questo listino con lo stesso codice.<br>";$e_duplicate++;}; 
      
                                           
      // se misura ? un numro valido
 
      $misura=floatval(trim(str_replace(array(","),array("."),$misura)));
      if(!is_numeric($misura)){$msg.="Il valore nel campo \"quantità\" non è stato riconosciuto<br>";$e_logical++;}
      if($misura<=0){$msg.="La misura non può essere negativa o uguale a zero<br>";$e_logical++;} 
      
      // se q scatola ? un numero valido
      if(!is_numeric($qta_scatola)){$msg.="Il valore nel campo \"quantità scatola\" non è stato riconosciuto<br>";$e_logical++;}
      
      // se q minima ? un numero valido
      
      if(!is_numeric($qta_multiplo)){$msg.="Il valore nel campo \"quantità minima\" non è stato riconosciuto<br>";$e_logical++;}
       if($qta_multiplo>$qta_scatola){$msg.="La \"quantità minima\" non può essere maggiore della \"quantità scatola\"<br>";$e_logical++;}
      
      // IN DATA 2/10/2010 invertiti data_10 con data_7 e usata "multiplo" anzich? "multiplo2" per controllare i multipli 
      if (!is_multiplo($qta_multiplo,$qta_scatola)){$msg.="Devi fare in modo che la quantità scatola sia divisibile per quantità minima.<br>";$e_logical++;};
      
      
      $msg.="<br>Verifica i dati immessi e riprova";
      
      // SANITIZE
      
      
      
      $e_total = $e_empty + $e_logical + $e_numerical+ $e_currency+$e_duplicate;
      
      if($e_total==0){
        
        $note_articolo=sanitize($note_articolo);
        $ingombro = sanitize($ingombro);  
        $codice = sanitize($codice);
        $descrizione = sanitize($descrizione);
          
        // QUERY INSERT
        $my_query="INSERT INTO retegas_articoli ( id_listini,
                                                  codice,
                                                  descrizione_articoli,
                                                  u_misura,
                                                  misura,
                                                  prezzo,
                                                  qta_scatola,
                                                  qta_minima,                                                  
                                                  articoli_unico,
                                                  ingombro,
                                                  articoli_note,
                                                  articoli_opz_1,
                                                  articoli_opz_2,
                                                  articoli_opz_3
                                                  )VALUES(
                                                    '$id_listino',
                                                    '$codice',
                                                    '$descrizione',
                                                    '$u_misura',
                                                    '$misura',
                                                    '$prezzo',
                                                    '$qta_scatola',
                                                    '$qta_multiplo',
                                                    '$univoco',
                                                    '$ingombro',
                                                    '$note_articolo',
                                                    '$opz_1',
                                                    '$opz_2',
                                                    '$opz_3'
                                                    )";
         //echo $my_query;                                         
        
        //INSERT BEGIN ---------------------------------------------------------
         $result = $db->sql_query($my_query);
         if (is_null($result)){
            $msg = "Errore nell'inserimento dell'articolo";
        }else{
            $msg = "OK";
        };
        
        //INSERT END --------------------------------------------------------- 
        
        
        
         
      }                 
                
return $msg;                
                    
}

function schedina_articolo($id_articolo,$open=null){
      Global $db,$RG_addr;
      
      $my_query="SELECT * FROM retegas_articoli WHERE  (id_articoli='$id_articolo') LIMIT 1";

      $result = $db->sql_query($my_query);
      $row =$db->sql_fetchrow($result);  
      if($row["articoli_unico"]==1){
                                    $unico="UNIVOCO";
      }else{
                                    $unico="CUMULABILE";
      }
      
      
         // TITOLO TABELLA         
         
         
       
$h .=  "<table>
            
            <tr style=\"vertical-align:top\">
             <td>
             <table  cellspacing=\"2\">
        <tr class=\"odd sinistra\">
            <th>Codice interno</th>
            <td>".$row["id_articoli"]."</td>
        </tr>
        <tr class=\"odd sinistra\">
            <th>Codice fornitore</th>
            <td>".$row["codice"]."</td>
        </tr>
        <tr  class=\"odd sinistra\">
            <th>Descrizione</th>
            <td>".$row["descrizione_articoli"]."</td>
        </tr>
        <tr class=\"odd sinistra\">
            <th $col_1>Unità di vendita</th>
            <td $col_2>".$row["u_misura"]." ".round($row["misura"])."</td>
        </tr>
        <tr class=\"odd sinistra\">
            <th>Prezzo</th>
            <td>"._nf($row["prezzo"])." Eu.</td>
        </tr>
        <tr class=\"odd sinistra\">
            <th>Ordini con questo Articolo :</th>
            <td>&nbsp;</td>
        </tr>
        <tr class=\"odd sinistra\">
            <th>Listini con questo Articolo :</th>
            <td>&nbsp;</td>
        </tr>
        
        </table>
        </td>
        
        <td  style=\"padding:0px\">
        <table cellpadding=\"0\" style=\"padding:0px\">        
        <tr class=\"odd sinistra\">
            <th>Listino di appartenenza</th>
            <td>".$row["id_listini"]."</td>
        </tr>        
        <tr class=\"odd sinistra\">
            <th>Note brevi</th>
            <td>".$row["ingombro"]."</td>
        </tr>
        <tr class=\"odd sinistra\">
            <th>Unità contenute in una scatola</th>
            <td>"._nf($row["qta_scatola"])."</td>
        </tr>
        <tr class=\"odd sinistra\">
            <th>Quantità multiplo di vendita</th>
            <td>"._nf($row["qta_minima"])."</td>
        </tr>
        <tr class=\"odd sinistra\">
            <th>Tipo articolo</th>
            <td>$unico</td>
        </tr>
        <tr class=\"odd sinistra\">
            <th>Variante</th>
            <td>".$row["articoli_opz_1"]."<br>
                ".$row["articoli_opz_2"]."<br>
                ".$row["articoli_opz_3"]."</td>
        </tr>
        </table>
        </td>
        </tr>
        </table>
         ";

      // END TABELLA DITTA -----------------------------------------------------------------------
      
      $h_table .=rg_toggable("Articolo $id_articolo, cod. \"".$row["codice"]."\" ".$row["descrizione_articoli"].";","pappo",$h,$open);  
      
      if($row["articoli_note"]<>""){
        $h_table .=rg_toggable("note","pippo",$row["articoli_note"],$open);       
      }
return $h_table;  
}

function articoli_ordini_con_questo_articolo($id_articolo){
    global $db,$RG_addr;
    $query = "SELECT * FROM retegas_ordini WHERE ((retegas_ordini.id_listini ='$id_listino'));";    
    $res = $db->sql_query($query);
    $h = "<ul>";
    while ($row = mysql_fetch_array($res)){
    
        $h .= "<li><a href=\"".$RG_addr["ordini_form"]."?id_ordine=".$row["id_ordini"]."\" >".$row["descrizione_ordini"]."</a>, di ".fullname_from_id($row["id_utente"])." chiuso il ".conv_only_date_from_db($row["data_chiusura"])."</li>";
        
    }
    $h .= "</ul>";
    
   return $h; 
}
  
?>