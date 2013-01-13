<?php


$val_ord = valore_totale_ordine_qarr($id);

if ($val_ord>0){
$percent = ($subt_amico / $val_ord) *100;
}

$trasporto_percentuale =valore_trasporto($id,$percent); 
$gestione_percentuale = valore_gestione($id,$percent);

//COSTO GAS
$valore_mio_gas = valore_totale_mio_gas($id,$id_gas);

if($valore_mio_gas>0){
    $percent_gas = ($subt_amico / valore_totale_mio_gas($id,$id_gas)) * 100;
    
}else{
    $percent_gas =0;
}
$costo_gas = (valore_assoluto_costo_mio_gas($id,$id_gas) / 100) * $percent_gas;

//COSTO MAGGIORAZIONE
$percent_maggiorazione_gas = valore_percentuale_maggiorazione_mio_gas($id,$id_gas);
$magg_gas = ($subt_amico /100) * $percent_maggiorazione_gas;


//TOTALI
$costi = number_format((float)round(($trasporto_percentuale +  $gestione_percentuale + $costo_gas + $magg_gas),2),2,",","");
$somma_totale_utente =number_format((float)round(($subt_amico + $trasporto_percentuale + $gestione_percentuale +$costo_gas + $magg_gas),2),2,",","");

//FORMATTAZIONE
$magg_gas = number_format((float)round($magg_gas,2),2,",","");

$gp = number_format($gestione_percentuale,2,",","");
$tp = number_format($trasporto_percentuale,2,",","");




//----------------------------
if($trasporto_percentuale>0){
    

    
    
    //$tp = number_format($trasporto_percentuale,2,",","");
    
    $h_table.= "
  
                <tr class=\"costo1\" style=\"border-bottom: 1px solid #000\">
                    <td $col_1>$nbsp</td> 
                    <td $col_2>$nbsp</td>    
                    <td $col_3>Costo trasporto</td>
                    <td $col_4>$nbsp</td>
                    <td $col_5>$nbsp</td>
                    <td $col_6>$nbsp</td>
                    <td $col_7>$nbsp</td>                   
                    <td $col_8>$tp $euro</td>
                    <td $col_9>$nbsp</td>  
                </tr>
            ";        
    
}
if($gestione_percentuale>0){

    
    
    //$gp = number_format($gestione_percentuale,2,",",""); 
    $h_table.= "
                <tr class=\"costo2\" style=\"border-bottom: 1px solid #000\">
                    <td $col_1>$nbsp</td> 
                    <td $col_2>$nbsp</td>    
                    <td $col_3>Costo Gestione</td>
                    <td $col_4>$nbsp</td>
                    <td $col_5>$nbsp</td>
                    <td $col_6>$nbsp</td>
                    <td $col_7>$nbsp</td>                   
                    <td $col_8>$gp $euro</td>
                    <td $col_9>$nbsp</td>  
                </tr>
            ";        
    
}


/// Costo mio GAS
if($costo_gas>0){
    $riga++;
    if(is_integer($riga/2)){  
            $class= "odd";    // Colore Riga
        }else{
            $class= "";    
        }
      
      $costo_gas = number_format((float)round($costo_gas,2),2,",","");
      $h_table.= "
  
                <tr class=\"costo1\" style=\"border-bottom: 1px solid #000\">
                    <td $col_1>$nbsp</td> 
                    <td $col_2>$nbsp</td>    
                    <td $col_3>Costo mio GAS</td>
                    <td $col_4>$nbsp</td>
                    <td $col_5>$nbsp</td>
                    <td $col_6>$nbsp</td>
                    <td $col_7>$nbsp</td>
                    <td $col_8>$costo_gas $euro</td>
                    <td $col_9>$nbsp</td>
                      
                </tr>
            ";        
    
}

/// Maggiorazione mio GAS
if($percent_maggiorazione_gas>0){
    $riga++;
    $percent_maggiorazione_gas = number_format((float)round($percent_maggiorazione_gas,2),2,",","");

    if(is_integer($riga/2)){  
            $class= "odd";    // Colore Riga
        }else{
            $class= "";    
        }
      $h_table.= "
  
                <tr class=\"costo1\" style=\"border-bottom: 1px solid #000\">
                    <td $col_1>$nbsp</td> 
                    <td $col_2>$nbsp</td>    
                    <td $col_3>Maggiorazione GAS ($percent_maggiorazione_gas %)</td>
                    <td $col_4>$nbsp</td>
                    <td $col_5>$nbsp</td>
                    <td $col_6>$nbsp</td>
                    <td $col_7>$nbsp</td>                 
                    <td $col_8>$magg_gas $euro</td>
                    <td $col_9>$nbsp</td>  
                </tr>
            ";        
    
}





  $sa = number_format((float)round($subt_amico,2),2,",","");
  $h_table.= "
                <tr class=\"subtotal\" style=\"margin-bottom:10px; border-bottom: 1px solid #000\">
                    <td $col_1>$nbsp</td> 
                    <td $col_2>$nbsp</td>    
                    <td $col_3>Subtotale ($subt_nome_amico)</td>
                    <td $col_4>$nbsp</td>
                    <td $col_5>$nbsp</td>
                    <td $col_6>$nbsp</td>
                    <td $col_7>$sa $euro</td>                 
                    <td $col_8>$costi $euro</td>
                    <td $col_9>$somma_totale_utente $euro</td>  
                </tr>
            ";
?>
